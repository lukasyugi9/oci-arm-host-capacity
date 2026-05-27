<?php

declare(strict_types=1);

use Hitrov\OciApi;
use Hitrov\OciConfig;

require_once __DIR__ . '/vendor/autoload.php';

// Peta pencocokan nama parameter internal OciConfig dengan Environment Variables GitHub
$envMap = [
    'region'              => getenv('OCI_REGION') ?: 'ap-batam-1',
    'ociUserId'           => getenv('OCI_USER_ID') ?: '',
    'ociTenancyId'        => getenv('OCI_TENANCY_ID') ?: '',
    'ociKeyFingerprint'   => getenv('OCI_KEY_FINGERPRINT') ?: '',
    'ociPrivateKey'       => getenv('OCI_PRIVATE_KEY_FILENAME') ?: '',
    'availabilityDomain'  => null,
    'subnetId'            => '',
    'imageId'             => '',
    'bootVolumeSizeInGbs' => 100,
    'memoryInGbs'         => 24,
    'ocpus'               => 4,
    'shape'               => 'VM.Standard.A1.Flex',
    'maxInstances'        => 1,
];


// Menggunakan PHP Reflection untuk mendeteksi secara otomatis urutan parameter constructor yang dibutuhkan oleh library Anda
$reflection = new ReflectionClass(OciConfig::class);
$constructor = $reflection->getConstructor();
$parameters = $constructor->getParameters();
$args = [];

foreach ($parameters as $param) {
    $paramName = $param->getName();
    $paramType = $param->getType() ? $param->getType()->getName() : 'string';
    
    // Ambil nilai default dari peta di atas jika ada
    $value = array_key_exists($paramName, $envMap) ? $envMap[$paramName] : null;
    
    // Paksa konversi tipe data secara dinamis sesuai kebutuhan constructor internal
    if ($paramType === 'int' && $value !== null) {
        $args[] = (int) $value;
    } elseif ($paramType === 'string' && $value !== null) {
        $args[] = (string) $value;
    } else {
        $args[] = $value;
    }
}

// Inisialisasi konfigurasi dengan urutan parameter yang sudah diurutkan otomatis oleh sistem
$config = $reflection->newInstanceArgs($args);
$api = new OciApi();

echo "Memulai perburuan server OCI ARM di region Batam dengan deteksi parameter otomatis...\n";

try {
    $api->createAvailabilityDomainInstances($config);
} catch (\Exception $e) {
    echo "Status Response Oracle: " . $e->getMessage() . "\n";
}
