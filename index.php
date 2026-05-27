<?php

declare(strict_types=1);

use Hitrov\OciApi;
use Hitrov\OciConfig;

require_once __DIR__ . '/vendor/autoload.php';

// Semua data lingkungan yang dikirim dari GitHub Actions
$envValues = [
    'region'              => getenv('OCI_REGION') ?: 'ap-batam-1',
    'userId'              => getenv('OCI_USER_ID') ?: '',
    'tenancyId'           => getenv('OCI_TENANCY_ID') ?: '',
    'keyFingerprint'      => getenv('OCI_KEY_FINGERPRINT') ?: '',
    'privateKeyFilename'  => getenv('OCI_PRIVATE_KEY_FILENAME') ?: '',
    'availabilityDomain'  => null,
    'subnetId'            => '',
    'imageId'             => '',
    'bootVolumeSizeInGbs' => 100,
    'memoryInGbs'         => 24,
    'ocpus'               => 4,
    'shape'               => 'VM.Standard.A1.Flex',
    'maxInstances'        => 1,
];

$reflection = new ReflectionClass(OciConfig::class);
$constructor = $reflection->getConstructor();
$parameters = $constructor->getParameters();
$args = [];

foreach ($parameters as $param) {
    $paramName = $param->getName();
    $paramType = $param->getType() ? $param->getType()->getName() : 'string';
    
    // Cari kecocokan nama secara fleksibel (misal ociUserId atau tenancyId akan otomatis cocok)
    $cleanParamName = strtolower(str_replace('oci', '', $paramName));
    
    $matchedValue = '';
    foreach ($envValues as $key => $val) {
        $cleanKey = strtolower(str_replace('oci', '', $key));
        if ($cleanKey === $cleanParamName) {
            $matchedValue = $val;
            break;
        }
    }
    
    // Konversi tipe data otomatis sesuai cetakan constructor library Anda
    if ($paramType === 'int') {
        $args[] = (int) ($matchedValue ?: 0);
    } elseif ($paramType === 'string') {
        $args[] = (string) ($matchedValue ?: '');
    } else {
        $args[] = $matchedValue;
    }
}

$config = $reflection->newInstanceArgs($args);
$api = new OciApi();

echo "Memulai perburuan server OCI ARM di region Batam dengan pencocokan parameter fleksibel...\n";

// Menjalankan fungsi bawaan utama untuk mengambil status instance & berburu kapasitas server gratis

try {
    echo "Mengirim permintaan berburu server ARM ke Oracle Batam...\n";
    
    // 1. Ambil data Availability Domains dari Batam
    $ads = $api->getAvailabilityDomains($config);
    
    if (!empty($ads)) {
        echo "Zona Batam Terdeteksi: " . $ads[0]['name'] . "\n";
        echo "Memicu pembuatan VM ARM Gratis...\n";
        
        // 2. Kirim perintah penciptaan instance otomatis ke zona yang terdeteksi
        $response = $api->createAvailabilityDomainInstances($config, $ads);
        
        echo "Respons Utama Oracle Cloud:\n";
        print_r($response);
    } else {
        echo "Zona Availability Domain Batam tidak ditemukan.\n";
    }
} catch (\Exception $e) {
    echo "Status Response Oracle: " . $e->getMessage() . "\n";
}
