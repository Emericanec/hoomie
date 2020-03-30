<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Form\LoginForm;
use App\Entity\Form\RegistrationForm;
use App\Entity\User;
use App\Form\Type\LoginType;
use App\Form\Type\RegistrationType;
use App\Processor\RegistrationProcessor;
use App\Service\Instagram\InstagramApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="login")
     * @param AuthenticationUtils $authenticationUtils
     * @return Response
     * @throws \Exception
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
            'loginUrl' => $instagramApi->getLoginUrl(['user_profile', 'user_media'])
        ]);
    }

    /**
     * @Route("/registration", name="registration")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function registration(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $registrationForm = new RegistrationForm();
        $form = $this->createForm(RegistrationType::class, $registrationForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $objectManager = $this->getDoctrine()->getManager();
            (new RegistrationProcessor($form, $user, $passwordEncoder, $objectManager))->process();
            return $this->redirectToRoute('home');
        }

        return $this->render('security/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout()
    {
        // controller can be blank: it will never be executed!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }
}