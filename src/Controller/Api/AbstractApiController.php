<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Controller\AppControllerInterface;
use App\Entity\User;
use App\Enum\Error;
use App\Response\Api\ApiResponse;
use App\Response\Api\PermissionDeniedResponse;
use App\Traits\RollBarTrait;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

abstract class AbstractApiController extends AbstractController implements AppControllerInterface
{
    use RollBarTrait;

    public ?User $user = null;

    public function before(): ?Response
    {
        if (!$this->isGranted(User::ROLE)) {
            return $this->json((new PermissionDeniedResponse())->toArray());
        }

        $user = $this->getUser();

        if (!$user instanceof User) {
            self::logger()->error(Error::APP_USER_NOT_FOUND, ['user' => $this->user]);
        }

        $this->user = $user;

        return null;
    }

    public function getCurrentUser(): User
    {
        return $this->user;
    }

    /**
     * @param array $data
     * @param array $groups
     * @return Response
     */
    public function jsonResponse($data, array $groups = ['default']): Response
    {
        /** @var SerializerInterface $serializer */
        $serializer = $this->get('serializer');
        $data = $serializer->serialize($data, 'json', ['groups' => $groups]);
        return new Response($data);
    }
}