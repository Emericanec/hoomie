<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Form\LoginForm;
use App\Entity\Form\RegistrationForm;
use App\Entity\User;
use App\Form\Type\LoginType;
use App\Form\Type\RegistrationType;
use App\Processor\RegistrationProcessor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class IndexController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('index/index.html.twig', [
            'name' => 'Andrey'
        ]);
    }

    /**
     * @Route("/login", name="login")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return Response
     */
    public function login(Request $request, UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $loginForm = new LoginForm();
        $form = $this->createForm(LoginType::class, $loginForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            return $this->redirectToRoute('home');
        }

        return $this->render('index/login.html.twig', [
            'form' => $form->createView()
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
        $objectManager = $this->getDoctrine()->getManager();
        $registrationForm = new RegistrationForm();
        $form = $this->createForm(RegistrationType::class, $registrationForm);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = new User();
            $objectManager = $this->getDoctrine()->getManager();
            (new RegistrationProcessor($form, $user, $passwordEncoder, $objectManager))->process();
            return $this->redirectToRoute('home');
        }

        return $this->render('index/registration.html.twig', [
            'form' => $form->createView()
        ]);
    }
}