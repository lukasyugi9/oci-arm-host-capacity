<?php

require_once __DIR__ . '/vendor/autoload.php';

$config = new Hitrov\OciConfig(
    (string)(getenv('OCI_REGION') ?: 'ap-batam-1'),
    (string)(getenv('OCI_USER_OCID') ?: ''),
    (string)(getenv('OCI_TENANCY_OCID') ?: ''),
    (string)(getenv('OCI_COMPARTMENT_OCID') ?: ''),
    (string)(getenv('OCI_FINGERPRINT') ?: ''),
    (string)(getenv('OCI_PRIVATE_KEY') ?: ''),
    (int)4,   // Memaksa tipe data integer untuk CPU
    (int)24,  // Memaksa tipe data integer untuk RAM
    (int)100, // Memaksa tipe data integer untuk Storage
    (string)(getenv('OCI_DISPLAY_NAME') ?: 'openclaw-server')
);

echo "Memulai perburuan server OCI ARM di region: " . (getenv('OCI_REGION') ?: 'ap-batam-1') . "\n";

$api = new Hitrov\OciApi();
try {
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status: " . $e->getMessage() . "\n";
}
