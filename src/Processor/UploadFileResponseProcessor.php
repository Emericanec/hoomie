<?php

declare(strict_types=1);

namespace App\Processor;

use App\Entity\File;
use App\Entity\User;
use App\Response\UploadFileResponse;
use Doctrine\Persistence\ObjectManager;

class UploadFileResponseProcessor
{
    private UploadFileResponse $uploadFileResponse;

    private ObjectManager $objectManager;

    private User $user;

    public function __construct(User $user, ObjectManager $objectManager, UploadFileResponse $uploadFileResponse)
    {
        $this->user = $user;
        $this->uploadFileResponse = $uploadFileResponse;
        $this->objectManager = $objectManager;
    }

    public function process(): File
    {
        $url = (string)$this->uploadFileResponse->getResult()->get('ObjectURL');
        $model = new File();
        $model->setUser($this->user);
        $model->setTitle($this->uploadFileResponse->getFileName());
        $model->setS3Key($this->uploadFileResponse->getKey());
        $model->setUrl($url);

        $this->objectManager->persist($model);
        $this->objectManager->flush();

        return $model;
    }
}