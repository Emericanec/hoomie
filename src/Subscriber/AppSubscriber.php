<?php

declare(strict_types=1);

namespace App\Subscriber;

use App\Controller\AppControllerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class AppSubscriber implements EventSubscriberInterface
{
    /**
     * @param ControllerEvent $event
     * @return void
     */
    public function onKernelController(ControllerEvent $event): void
    {
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }

        if ($controller instanceof AppControllerInterface) {
            $response = $controller->before();
            if ($response instanceof Response) {
                $event->setController(static function () use ($response): Response {
                    return $response;
                });
            }
        }
    }

    /**
     * @inheritDoc
     */
    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => 'onKernelController',
        ];
    }
}