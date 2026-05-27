<?php

declare(strict_types=1);

use Hitrov\OciApi;
use Hitrov\OciConfig;

require_once __DIR__ . '/vendor/autoload.php';

// Memaksa sistem membaca data dari environment GitHub Actions dengan casting tipe data murni
$configArray = [
    'region'                => (string) getenv('OCI_REGION'),
    'userId'                => (string) getenv('OCI_USER_ID'),
    'tenancyId'             => (string) getenv('OCI_TENANCY_ID'),
    'keyFingerprint'        => (string) getenv('OCI_KEY_FINGERPRINT'),
    'privateKeyFilename'    => (string) getenv('OCI_PRIVATE_KEY_FILENAME'),
    'subnetId'              => '',
    'imageId'               => '',
    'bootVolumeSizeInGbs'   => (int) (getenv('OCI_BOOT_VOLUME_SIZE_IN_GBS') ?: 100),
    'shape'                 => 'VM.Standard.A1.Flex',
    'maxInstances'          => (int) (getenv('OCI_MAX_INSTANCES') ?: 1),
    'ocpus'                 => (int) 4,
    'memoryInGbs'           => (int) 24,
];

// Menggunakan static method fromArray untuk menghindari eror posisi argument constructor
$config = OciConfig::fromArray($configArray);
$api = new OciApi();

echo "Menghubungkan ke Oracle Cloud Infrastructure (Region Batam) via Array Config...\n";

try {
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status Terkini: " . $e->getMessage() . "\n";
}
