<?php

declare(strict_types=1);

namespace App\Integration\Wasabi;

use Aws\Result;
use Aws\S3\S3Client;
use Ramsey\Uuid\Uuid;
use SplFileObject;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadClient
{
    private const DEFAULT_BUCKET = 'hoomie-image';

    private S3Client $client;

    public function __construct()
    {
        $this->client = new S3Client([
            'endpoint' => 'https://s3.wasabisys.com',
            'region' => 'us-east-1',
            'version' => 'latest',
            'credentials' => [
                'key' => self::getAccessKey(),
                'secret' => self::getSecretKey(),
            ]
        ]);
    }

    public function getS3Client(): S3Client
    {
        return $this->client;
    }

    public function upload(string $key, SplFileObject $body): Result
    {
        return $this->getS3Client()->putObject([
            'ACL' => 'public-read',
            'ContentLength' => $body->getSize(),
            'Bucket' => self::DEFAULT_BUCKET,
            'Key' => $key,
            'Body' => file_get_contents($body->getRealPath()),
        ]);
    }

    public function get($key): Result
    {
        return $this->getS3Client()->getObject([
            'Bucket' => self::DEFAULT_BUCKET,
            'Key' => $key,
        ]);
    }

    public function generateKey(UploadedFile $uploadedFile): string
    {
        $uuid = Uuid::uuid4();
        $extension = $uploadedFile->guessExtension();
        return $uuid . '.' . $extension;
    }

    private static function getAccessKey(): string
    {
        return $_SERVER['WASABI_ACCESS_KEY'] ?? '';
    }

    private static function getSecretKey():string
    {
        return $_SERVER['WASABI_SECRET_KEY'] ?? '';
    }
}