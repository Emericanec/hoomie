<?php

declare(strict_types=1);

namespace App\Processor;

use App\Entity\Form\RegistrationFormInterface;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class RegistrationProcessor
{
    private FormInterface $form;

    private UserInterface $user;

    private UserPasswordEncoderInterface $userPasswordEncoder;

    private ObjectManager $objectManager;

    public function __construct(FormInterface $form, User $user, UserPasswordEncoderInterface $userPasswordEncoder, ObjectManager $objectManager)
    {
        $this->form = $form;
        $this->user = $user;
        $this->userPasswordEncoder = $userPasswordEncoder;
        $this->objectManager = $objectManager;
    }

    public function process(): void
    {
        $registrationForm = $this->getRegistrationForm();
        $this->user->setEmail($registrationForm->getEmail());
        $this->user->setPassword($this->userPasswordEncoder->encodePassword($this->user, $registrationForm->getPassword()));
        $this->objectManager->persist($this->user);
        $this->objectManager->flush();
    }

    protected function getRegistrationForm(): RegistrationFormInterface
    {
        return $this->form->getData();
    }
}