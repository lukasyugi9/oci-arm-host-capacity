<?php

declare(strict_types=1);

use Hitrov\OciApi;
use Hitrov\OciConfig;

require_once __DIR__ . '/vendor/autoload.php';

// Memaksa pengambilan data lingkungan dengan tipe data murni
$region = (string) getenv('OCI_REGION');
$userId = (string) getenv('OCI_USER_ID');
$tenancyId = (string) getenv('OCI_TENANCY_ID');
$keyFingerprint = (string) getenv('OCI_KEY_FINGERPRINT');
$privateKey = (string) getenv('OCI_PRIVATE_KEY_FILENAME');
$bootVolumeSizeInGbs = (int) (getenv('OCI_BOOT_VOLUME_SIZE_IN_GBS') ?: 100);
$maxInstances = (int) (getenv('OCI_MAX_INSTANCES') ?: 1);

// Membuat konfigurasi menggunakan urutan constructor asli bawaan library versi lama
$config = new OciConfig(
    $region,
    $userId,
    $tenancyId,
    $keyFingerprint,
    $privateKey,
    null, // availabilityDomainConfig
    '',   // subnetId
    '',   // imageId
    $bootVolumeSizeInGbs,
    'VM.Standard.A1.Flex', // shape
    $maxInstances,
    4,  // ocpus murni int
    24  // memoryInGbs murni int
);

$api = new OciApi();
echo "Menghubungkan ke Oracle Cloud Infrastructure Batam...\n";

try {
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status Response: " . $e->getMessage() . "\n";
}
