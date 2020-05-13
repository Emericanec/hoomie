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
     *   @Route("email/confirm_email/{userId}", name="confirm_email")
     *   @return Response
     */
    public function confirmEmail(int $userId) : Response
    {
        $confirmUrlForUser = $this->generateUrl('confirm_email', [
            'userId' => $userId,
        ]);
        $lol = $_SERVER['HTTPS'];
        $siteAddress = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"];
        var_dump($siteAddress);
        return $this->render('email/confirm_email.html.twig', [
            'confirmEmail' => $confirmUrlForUser,
            'lol' => $siteAddress
        ]);
    }

    /**
     *   @Route("email/confirmed/{userId}", name="confirmed")
     *   @return Response
     */
    public function sendMessageToConfirm(int $userId)
    {
        $message = '';
        $user = $this->getDoctrine()->getManager();
        $currentUser = $user->getRepository(User::class)->find($userId);
        var_dump($currentUser->getInstagramNickname());
//        var_dump($currentUser->lol());
        var_dump($currentUser->getIsActivated());


        if (empty($currentUser)) {
            $message = 'Что-то пошло не так';

        } else {
//            $currentUser->setIsActivated(true);
//            $currentUser->getIsActivated();
            $message = 'Аккаунт активирован';
        }
        return $this->render('email/confirmed.html.twig', [
            'message' => $message,
        ]);
    }

}