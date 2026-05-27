<?php

require_once __DIR__ . '/vendor/autoload.php';

$config = new Hitrov\OciConfig(
    getenv('OCI_REGION') ?: 'ap-batam-1',
    getenv('OCI_USER_OCID') ?: '',
    getenv('OCI_TENANCY_OCID') ?: '',
    getenv('OCI_COMPARTMENT_OCID') ?: '',
    getenv('OCI_FINGERPRINT') ?: '',
    getenv('OCI_PRIVATE_KEY') ?: '',
    4,
    24,
    100,
    getenv('OCI_DISPLAY_NAME') ?: 'openclaw-server'
);

echo "Memulai perburuan server OCI ARM di region: " . (getenv('OCI_REGION') ?: 'ap-batam-1') . "\n";

$api = new Hitrov\OciApi();
try {
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status: " . $e->getMessage() . "\n";
}
