<?php
/**
 * Génère un certificat auto-signé (HTTPS) pour le reverse proxy local.
 * But : obtenir un contexte sécurisé sur le LAN afin que la caméra du
 * poste de scan fonctionne (getUserMedia exige HTTPS ou 127.0.0.1).
 * Les clés restent locales (storage/tls, ignoré par git).
 */
$dir = __DIR__ . '/../storage/tls';
@mkdir($dir, 0777, true);
$certFile = $dir . '/cert.pem';
$keyFile  = $dir . '/key.pem';

if (file_exists($certFile) && file_exists($keyFile)) {
    echo "Certificat déjà présent : $certFile\n";
    exit(0);
}

$dn = [
    'countryName'            => 'DZ',
    'stateOrProvinceName'    => 'Alger',
    'localityName'           => 'Alger',
    'organizationName'       => 'NEXUS ERP',
    'commonName'             => 'nexus-erp.local',
    'emailAddress'           => 'admin@nexus-erp.dz',
];

$priv = openssl_pkey_new([
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
]);

$csr = openssl_csr_new($dn, $priv, ['digest_alg' => 'sha256']);
if ($csr === false) {
    fwrite(STDERR, "Erreur CSR : " . openssl_error_string() . "\n");
    exit(1);
}

// Auto-signé (CA = null).
$cert = openssl_csr_sign($csr, null, $priv, 3650, ['digest_alg' => 'sha256']);
if ($cert === false) {
    fwrite(STDERR, "Erreur signature : " . openssl_error_string() . "\n");
    exit(1);
}

openssl_pkey_export($priv, $keyOut);
openssl_x509_export($cert, $certOut);

file_put_contents($keyFile, $keyOut);
file_put_contents($certFile, $certOut);
echo "Certificat généré : $certFile\n";
