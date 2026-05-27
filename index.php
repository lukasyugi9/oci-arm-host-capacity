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

$ociAvailabilityDomain = getenv('OCI_AVAILABILITY_DOMAIN') ? json_decode(getenv('OCI_AVAILABILITY_DOMAIN'), true) : null;

$config = new OciConfig(
    (string) getenv('OCI_REGION'),
    (string) getenv('OCI_USER_ID'),
    (string) getenv('OCI_TENANCY_ID'),
    (string) getenv('OCI_KEY_FINGERPRINT'),
    (string) getenv('OCI_PRIVATE_KEY_FILENAME'),
    $ociAvailabilityDomain,
    (string) getenv('OCI_SUBNET_ID'),
    (string) getenv('OCI_IMAGE_ID'),
    (int) getenv('OCI_BOOT_VOLUME_SIZE_IN_GBS'),
    (string) getenv('OCI_SHAPE'),
    (int) getenv('OCI_MAX_INSTANCES')
);

$api = new Hitrov\OciApi();

$api->createAvailabilityDomainInstances($config);
