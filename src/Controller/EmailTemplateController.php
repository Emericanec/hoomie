<?php
/**
 * Created by PhpStorm.
 * User: kirill
 * Date: 25.04.20
 * Time: 19:54
 */

namespace App\Controller;


use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class EmailTemplateController extends AbstractController
{

    /**
     *   @Route("email/confirmed/{userId}", name="confirmed")
     *   @return Response
     */
    public function sendMessageToConfirm(int $userId) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $em->getRepository(User::class)->find($userId);

        if (empty($currentUser)) {
            $message = 'Что-то пошло не так';

        } else {
            $currentUser->setIsActivated(true);
            $em->persist($currentUser);
            $em->flush();
            $message = 'Аккаунт активирован';
        }
        return $this->render('email/confirmed.html.twig', [
            'message' => $message,
        ]);
    }

}