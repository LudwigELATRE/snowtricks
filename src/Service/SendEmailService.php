<?php

namespace App\Service;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailService
{
    public function __construct(private MailerInterface $mailer)
    {}

    public function sendEmail(string $from, string $to, string $subject, string $htmlTemplate, array $context): void
    {
        // On cree le mail
        $email = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate("emails/$htmlTemplate.html.twig")
        ->context($context);

        //On envoie le mail
        $this->mailer->send($email);
    }
}