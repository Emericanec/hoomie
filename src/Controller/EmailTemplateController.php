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
     * @Route("email/confirmed/{userHash}", name="confirmed")
     * @param string $userHash
     * @return Response
     */
    public function sendMessageToConfirm(string $userHash) : Response
    {
        $em = $this->getDoctrine()->getManager();
        $currentUser = $em->getRepository(User::class)->findOneBy(['personalHash' => $userHash]);

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