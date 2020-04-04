<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Form\LoginForm;
use App\Entity\User;
use App\Form\Type\LoginType;
use App\Security\Security;
use App\Service\Instagram\InstagramApi;
use Exception;
use RuntimeException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws Exception
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        $loginForm = new LoginForm();
        $loginForm->setEmail($lastUsername);
        $form = $this->createForm(LoginType::class, $loginForm);

        $instagramApi = InstagramApi::getInstance();

        return $this->render('security/login.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
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
     * @Route("/step2", name="step2")
     * @param Security $security
     * @param Request $request
     * @return Response
     */
    public function step2(Security $security, Request $request): Response
    {
        $this->denyAccessUnlessGranted(User::ROLE);

        $email = $request->request->get('email');
        $user = $security->getUser();

        if (null !== $email && null !== $user) {
            $user->setEmail($email);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirect('app_main');
        }

        return $this->render('security/step2.html.twig');
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(): void
    {
        throw new RuntimeException('Don\'t forget to activate logout in security.yaml');
    }
}