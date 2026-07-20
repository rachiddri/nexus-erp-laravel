import * as Html5QrcodeLib from 'html5-qrcode';

// La librairie est bundlée proprement par Vite (npm + cache-busting automatique).
// On ré-expose volontairement les globals et on relie directement le bouton depuis
// CE module : pas de dépendance à @script/Livewire, pas de course critique.
const Html5Qrcode = Html5QrcodeLib.Html5Qrcode;
const Html5QrcodeScanner = Html5QrcodeLib.Html5QrcodeScanner;

window.Html5Qrcode = Html5Qrcode;
window.Html5QrcodeScanner = Html5QrcodeScanner;

function goToCode(decoded) {
    // Navigation via l'URL RÉELLE du navigateur (scheme/hôte/port courants,
    // ex. https://IP:8443) et NON via l'action du formulaire : avec le reverse
    // proxy HTTPS, Laravel résout url()->current() vers le serveur interne
    // non sécurisé (:8000), ce qui faisait perdre le contexte sécurisé au submit.
    const url = new URL(window.location.href);
    url.searchParams.set('code', decoded);
    window.location.href = url.toString();
}

function bindScan() {
    const scannerBtn = document.getElementById('btn-scanner');
    if (!scannerBtn) return;                        // page pas encore rendue
    if (scannerBtn.dataset.scanBound) return;       // déjà relié
    scannerBtn.dataset.scanBound = '1';

    const msg = () => document.getElementById('scan-msg');
    const reader = () => document.getElementById('reader');

    // Scan via CAMÉRA (Html5QrcodeScanner, UI caméra intégrée).
    // Nécessite une origine sécurisée : HTTPS (reverse proxy :8443) ou 127.0.0.1.
    // Démarrage uniquement au tap (jamais auto au chargement — contrainte iOS Safari).
    let scanner = null;
    let scannerStarted = false;
    scannerBtn.addEventListener('click', function () {
        msg().textContent = '';
        reader().style.display = 'block';

        if (!window.isSecureContext) {
            msg().textContent = 'Caméra requise en origine sécurisée (HTTPS ou http://127.0.0.1:8000). '
                + 'Astuce : ouvrez l\'app via https://<IP_LAN>:8443/admin/scan-station (certificat à accepter).';
            reader().style.display = 'none';
            return;
        }

        if (scannerStarted) return;
        scannerStarted = true;

        scanner = new Html5QrcodeScanner(
            'reader',
            { fps: 10, qrbox: { width: 250, height: 250 } },
            /* verbose= */ false
        );

        scanner.render(
            (decodedText) => {
                scanner.clear().catch(() => {});
                reader().style.display = 'none';
                scannerStarted = false;
                goToCode(decodedText);
            },
            () => { /* erreurs de décodage partielles ignorées */ }
        ).catch((err) => {
            msg().textContent = 'Accès caméra refusé ou indisponible. Saisissez le code manuellement.';
            reader().style.display = 'none';
            scannerStarted = false;
        });
    });
}

function init() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', bindScan);
    } else {
        bindScan();
    }
    // Re-relié après les navigations Livewire/Filament (SPA) si les éléments sont remplacés.
    document.addEventListener('livewire:navigated', bindScan);
    document.addEventListener('livewire:initialized', bindScan);
}

init();
