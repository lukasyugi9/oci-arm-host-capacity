<?php

declare(strict_types=1);

use Hitrov\OciApi;
use Hitrov\OciConfig;

require_once __DIR__ . '/vendor/autoload.php';

$envFilename = '.env';

if (!getenv('OCI_REGION')) {
    $dotenv = Dotenv\Dotenv::createUnsafeImmutable(__DIR__, $envFilename);
    $dotenv->safeLoad();
}

// Mengambil variabel angka murni langsung dengan casting tipe data yang aman
$bootVolumeSize = getenv('OCI_BOOT_VOLUME_SIZE_IN_GBS') ? (int)getenv('OCI_BOOT_VOLUME_SIZE_IN_GBS') : 100;
$maxInstances = getenv('OCI_MAX_INSTANCES') ? (int)getenv('OCI_MAX_INSTANCES') : 1;

$config = new OciConfig(
    (string) getenv('OCI_REGION'),
    (string) getenv('OCI_USER_OCID'),
    (string) getenv('OCI_TENANCY_OCID'),
    (string) getenv('OCI_FINGERPRINT'),
    (string) getenv('OCI_PRIVATE_KEY'),
    null, // availabilityDomainConfig
    '',   // subnetId
    '',   // imageId
    $bootVolumeSize,
    'VM.Standard.A1.Flex', // Shape ARM Gratis
    $maxInstances
);

$api = new OciApi();
echo "Memulai koneksi aman ke Oracle Cloud Infrastructure di region Batam...\n";

try {
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status Response: " . $e->getMessage() . "\n";
}
