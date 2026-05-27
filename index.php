<?php

require_once __DIR__ . '/vendor/autoload.php';

// Mengambil data aman langsung dari sistem environment GitHub Actions
$config = new Hitrov\OciConfig(
    getenv('OCI_REGION') ?: 'ap-batam-1',
    getenv('OCI_USER_OCID') ?: '',
    getenv('OCI_TENANCY_OCID') ?: '',
    getenv('OCI_COMPARTMENT_OCID') ?: '',
    getenv('OCI_FINGERPRINT') ?: '',
    getenv('OCI_PRIVATE_KEY') ?: '',
    getenv('OCI_OCPUS') ?: 4,
    getenv('OCI_MEMORY_IN_GBS') ?: 24,
    getenv('OCI_BOOT_VOLUME_SIZE_IN_GBS') ?: 100,
    getenv('OCI_DISPLAY_NAME') ?: 'openclaw-server'
);

// Jalankan script utama pemburu server ARM
$api = new Hitrov\OciApi();
echo "Memulai perburuan server OCI ARM di region: " . getenv('OCI_REGION') . "\n";

try {
    // Memanggil fungsi bawaan dari repositori untuk membuat server
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status: " . $e->getMessage() . "\n";
}
