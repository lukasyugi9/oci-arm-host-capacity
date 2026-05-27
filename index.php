<?php

require_once __DIR__ . '/vendor/autoload.php';

// Ambil nilai dari environment dan paksa diubah menjadi angka murni (integer)
$ocpus = getenv('OCI_OCPUS');
$memory = getenv('OCI_MEMORY_IN_GBS');
$bootVolume = getenv('OCI_BOOT_VOLUME_SIZE_IN_GBS');

$ociOcpus = $ocpus ? (int)$ocpus : 4;
$ociMemory = $memory ? (int)$memory : 24;
$ociBootVolume = $bootVolume ? (int)$bootVolume : 100;

// Masukkan variabel angka murni ke dalam konfigurasi OCI
$config = new Hitrov\OciConfig(
    getenv('OCI_REGION') ?: 'ap-batam-1',
    getenv('OCI_USER_OCID') ?: '',
    getenv('OCI_TENANCY_OCID') ?: '',
    getenv('OCI_COMPARTMENT_OCID') ?: '',
    getenv('OCI_FINGERPRINT') ?: '',
    getenv('OCI_PRIVATE_KEY') ?: '',
    $ociOcpus,
    $ociMemory,
    $ociBootVolume,
    getenv('OCI_DISPLAY_NAME') ?: 'openclaw-server'
);

echo "Memulai perburuan server OCI ARM di region: " . (getenv('OCI_REGION') ?: 'ap-batam-1') . "\n";

$api = new Hitrov\OciApi();
try {
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status: " . $e->getMessage() . "\n";
}
