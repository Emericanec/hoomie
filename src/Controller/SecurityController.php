<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use App\Processor\DefaultMailProcessor;
use App\Security\Security;
use App\Service\Instagram\InstagramApi;
use Exception;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @return Response
     * @throws Exception
     */
    public function login(): Response
    {
        $instagramApi = InstagramApi::getInstance();

        return $this->render('security/login.html.twig', [
            'loginUrl' => $instagramApi->getLoginUrl()
        ]);
    }

    /**
     * @Route("/oauth", name="oauth")
     * @return Response
     * @throws Exception
     */
    public function oauth(): Response
    {
        return $this->redirect('login');
    }

    /**
     * @Route("/local", name="local")
     * @return Response
     * @throws Exception
     */
    public function local(): Response
    {
        return $this->redirect('login');
    }

    /**
     * @Route("/oauth/error", name="oauth_error")
     * @return Response
     */
    public function oauth_error(): Response
    {
        return $this->render('security/oauth_error.html.twig');
    }

    /**
     * @Route("/step2", name="step2")
     * @param MailerInterface $mailer
     * @param Security $security
     * @param Request $request
     * @return Response
     * @throws TransportExceptionInterface
     */
    public function step2(MailerInterface $mailer, Security $security, Request $request): Response
    {
        // todo refactor this
        $this->denyAccessUnlessGranted(User::ROLE);

        $email = $request->request->get('email');

        /** @var User $user */
        $user = $security->getUser();
        $emailTemplate = new EmailTemplateController();
        if (null !== $user->getEmail()) {
            return $this->redirectToRoute('app_main');
        }

        if (null !== $email && is_string($email)) {
            $user->setEmail($email);
            $personalHash = md5($user->getInstagramNickname());
            $user->setPersonalHash($personalHash);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();
            $confirmUri = $this->createConfirmEmailUri($user->getInstagramNickname());
            $siteUrl = $_SERVER["REQUEST_SCHEME"] . '://' . $_SERVER["HTTP_HOST"];
            $mailProcessor = new DefaultMailProcessor($mailer, 'Confirm your email', 'email/confirm_email.html.twig');
            $mailProcessor->send($email, [
                'siteUrl'=> $siteUrl,
                'confirmEmail' => $confirmUri
            ]);

            return $this->redirectToRoute('app_main');
        }

        return $this->render('security/step2.html.twig');
    }

    private function createConfirmEmailUri(string $forEncrypt) : string
    {
        $uri = $this->generateUrl('confirmed', [
            'userHash' => md5($forEncrypt)
        ], UrlGeneratorInterface::ABSOLUTE_URL);
        return $uri;
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(): void
    {
        throw new RuntimeException('Don\'t forget to activate logout in security.yaml');
    }
}