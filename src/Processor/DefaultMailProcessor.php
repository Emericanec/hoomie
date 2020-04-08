<?php

declare(strict_types=1);

namespace App\Processor;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class DefaultMailProcessor
{
    private const FROM = 'noreply@hoomie.co';

    private MailerInterface $mailer;

    private string $subject;

    private string $template;

    public function __construct(MailerInterface $mailer, string $subject, string $template)
    {
        $this->mailer = $mailer;
        $this->subject = $subject;
        $this->template = $template;
    }

    /**
     * @param string $to
     * @param array $context
     * @throws TransportExceptionInterface
     */
    public function send(string $to, array $context = []): void
    {
        $email = (new TemplatedEmail())
            ->from(self::FROM)
            ->to(new Address($to))
            ->subject($this->subject)
            ->htmlTemplate($this->template)
            ->context($context);

        $this->mailer->send($email);
    }
}