<?php

declare(strict_types=1);

namespace App\Controller\App;

use App\Enum\Error;
use App\Processor\FileUploadProcessor;
use App\Processor\UploadFileResponseProcessor;
use App\Traits\RollBarTrait;
use Exception;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UploadController extends AbstractAppController
{
    use RollBarTrait;

    private const KEY = 'file';

    /**
     * @Route("/app/upload", name="app_upload")
     * @param Request $request
     * @return Response
     */
    public function upload(Request $request): Response
    {
        $file = $request->files->get(self::KEY);

        if (!($file instanceof UploadedFile)) {
            return $this->json([
                'success' => false,
                'error' => 'file is empty'
            ]);
        }

        try {
            $response = (new FileUploadProcessor())->process($file);
        } catch (Exception $exception) {
            self::logger()->error(Error::UPLOAD_FILE_PROCESSING_ERROR, [
                'message' => $exception->getMessage(),
                'trace' => $exception->getTrace()
            ]);

            return $this->json([
                'success' => false,
                'error' => 'Upload file processing error'
            ]);
        }

        $processor = new UploadFileResponseProcessor($this->getCurrentUser(), $this->getDoctrine()->getManager(), $response);
        $file = $processor->process();

        return $this->json([
            'success' => true,
            'file_id' => $file->getId(),
            'key' => $file->getS3Key(),
            'result' => $response->getResult()->toArray()
        ]);
    }
}