/*
 * Poste de scan — logique caméra QR/code-barres.
 * Charge la lib UMD html5-qrcode (public/vendor/html5-qrcode.min.js) qui expose
 * les globals window.Html5Qrcode / window.Html5QrcodeScanner.
 * (Vite 8 / rolldown ne build pas sur Termux/Android : on sert le build UMD précompilé.)
 */
(function () {
    function goToCode(decoded) {
        // Navigation via l'URL RÉELLE du navigateur (scheme/hôte/port courants,
        // ex. https://IP:8443). Indispensable derrière le reverse proxy HTTPS :
        // sinon Laravel résout url() vers le serveur interne non sécurisé (:8000).
        var url = new URL(window.location.href);
        url.searchParams.set('code', decoded);
        window.location.href = url.toString();
    }

    function bindScan() {
        var scannerBtn = document.getElementById('btn-scanner');
        if (!scannerBtn) return;
        if (scannerBtn.dataset.scanBound) return;
        scannerBtn.dataset.scanBound = '1';

        var msgEl = document.getElementById('scan-msg');
        var readerEl = document.getElementById('reader');

        var scanner = null;
        var started = false;

        scannerBtn.addEventListener('click', function () {
            if (msgEl) msgEl.textContent = '';
            if (readerEl) readerEl.style.display = 'block';

            if (!window.isSecureContext) {
                if (msgEl) msgEl.textContent = 'Caméra requise en origine sécurisée (HTTPS ou http://127.0.0.1:8000). ' +
                    'Ouvrez l\'app via https://<IP_LAN>:8443/admin/scan-station (certificat à accepter).';
                if (readerEl) readerEl.style.display = 'none';
                return;
            }

            if (typeof window.Html5QrcodeScanner === 'undefined') {
                if (msgEl) msgEl.textContent = '⚠️ Librairie de scan non chargée.';
                if (readerEl) readerEl.style.display = 'none';
                return;
            }

            if (started) return;
            started = true;

            scanner = new window.Html5QrcodeScanner(
                'reader',
                { fps: 10, qrbox: { width: 250, height: 250 } },
                false
            );

            scanner.render(
                function (decodedText) {
                    if (scanner) scanner.clear().catch(function () {});
                    if (readerEl) readerEl.style.display = 'none';
                    started = false;
                    goToCode(decodedText);
                },
                function () { /* erreurs partielles ignorées */ }
            ).catch(function () {
                if (msgEl) msgEl.textContent = 'Accès caméra refusé ou indisponible. Saisissez le code manuellement.';
                if (readerEl) readerEl.style.display = 'none';
                started = false;
            });
        });
    }

    function init() {
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', bindScan);
        } else {
            bindScan();
        }
        document.addEventListener('livewire:navigated', bindScan);
        document.addEventListener('livewire:initialized', bindScan);
    }

    init();
})();
