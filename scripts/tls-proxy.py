#!/usr/bin/env python3
"""
Reverse proxy TLS minimaliste pour exposer l'app Laravel en HTTPS sur le LAN.
Permet au poste de scan d'utiliser la caméra (getUserMedia exige un contexte
sécurisé : HTTPS ou 127.0.0.1).

  Terminal A : php artisan serve --host=0.0.0.0 --port=8000
  Terminal B : php scripts/gen-cert.php
              python3 scripts/tls-proxy.py
  Téléphone  : https://<IP_LAN>:8443/admin/scan-station  (accepter le cert.)

Le certificat est auto-signé : le navigateur affiche un avertissement, mais
l'origine devient un contexte sécurisé -> la caméra fonctionne après "Avancé
> Continuer".
"""
import http.server
import os
import socketserver
import ssl
import urllib.request

UPSTREAM = "http://127.0.0.1:8000"
LISTEN_HOST = "0.0.0.0"
LISTEN_PORT = 8443

HERE = os.path.dirname(os.path.abspath(__file__))
CERT = os.path.join(HERE, "..", "storage", "tls", "cert.pem")
KEY = os.path.join(HERE, "..", "storage", "tls", "key.pem")


class Handler(http.server.BaseHTTPRequestHandler):
    protocol_version = "HTTP/1.1"

    def _proxy(self):
        length = int(self.headers.get("Content-Length", 0) or 0)
        body = self.rfile.read(length) if length else None
        url = UPSTREAM + self.path
        req = urllib.request.Request(url, data=body, method=self.command)
        for k in self.headers.keys():
            lk = k.lower()
            if lk in ("host", "content-length", "connection", "accept-encoding"):
                continue
            req.add_header(k, self.headers[k])
        # Évite gzip/chunked : on décoderait mal sinon.
        req.add_header("Accept-Encoding", "identity")
        try:
            resp = urllib.request.urlopen(req, timeout=60)
            data = resp.read()
            self.send_response(resp.status)
            for k, v in resp.getheaders():
                lk = k.lower()
                if lk in ("content-length", "content-encoding",
                          "transfer-encoding", "connection", "host"):
                    continue
                self.send_header(k, v)
            self.send_header("Content-Length", str(len(data)))
            self.end_headers()
            if data:
                self.wfile.write(data)
        except urllib.error.HTTPError as e:
            data = e.read()
            self.send_response(e.code)
            self.send_header("Content-Length", str(len(data)))
            self.end_headers()
            self.wfile.write(data)
        except Exception as e:  # noqa: BLE001
            self.send_error(502, str(e))

    def do_GET(self):
        self._proxy()

    def do_POST(self):
        self._proxy()

    def log_message(self, *args):  # silence
        pass


if __name__ == "__main__":
    if not (os.path.exists(CERT) and os.path.exists(KEY)):
        raise SystemExit("Certificat introuvable. Lance d'abord: php scripts/gen-cert.php")
    httpd = socketserver.ThreadingTCPServer((LISTEN_HOST, LISTEN_PORT), Handler)
    ctx = ssl.SSLContext(ssl.PROTOCOL_TLS_SERVER)
    ctx.load_cert_chain(CERT, KEY)
    httpd.socket = ctx.wrap_socket(httpd.socket, server_side=True)
    print(f"TLS reverse proxy -> https://0.0.0.0:{LISTEN_PORT} (-> {UPSTREAM})")
    httpd.serve_forever()
