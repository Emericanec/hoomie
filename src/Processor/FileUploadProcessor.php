<?php

declare(strict_types=1);

namespace App\Processor;

use App\Integration\Wasabi\FileUploadClient;
use App\Response\UploadFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploadProcessor
{
    public FileUploadClient $fileUploadClient;

    public function __construct()
    {
        $this->fileUploadClient = new FileUploadClient();
    }

    public function process(UploadedFile $uploadedFile): UploadFileResponse
    {
        $key = $this->fileUploadClient->generateKey($uploadedFile);
        $result = $this->fileUploadClient->upload($key, $uploadedFile->openFile());

        return new UploadFileResponse($uploadedFile->getFilename(), $key, $result);
    }
}