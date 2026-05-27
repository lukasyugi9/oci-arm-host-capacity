<?php

require_once __DIR__ . '/vendor/autoload.php';

// Masukkan angka murni langsung ke dalam konfigurasi OCI tanpa getenv()
$config = new Hitrov\OciConfig(
    getenv('OCI_REGION') ?: 'ap-batam-1',
    getenv('OCI_USER_OCID') ?: '',
    getenv('OCI_TENANCY_OCID') ?: '',
    getenv('OCI_COMPARTMENT_OCID') ?: '',
    getenv('OCI_FINGERPRINT') ?: '',
    getenv('OCI_PRIVATE_KEY') ?: '',
    4,   // $ociOcpus langsung diisi angka murni
    24,  // $ociMemory langsung diisi angka murni
    100, // $ociBootVolume langsung diisi angka murni
    getenv('OCI_DISPLAY_NAME') ?: 'openclaw-server'
);

echo "Memulai perburuan server OCI ARM di region: " . (getenv('OCI_REGION') ?: 'ap-batam-1') . "\n";

$api = new Hitrov\OciApi();
try {
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status: " . $e->getMessage() . "\n";
}
