<?php

namespace App\Service;

use JetBrains\PhpStorm\NoReturn;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;

class SendEmailService
{
    public function __construct(private readonly MailerInterface $mailer)
    {}

    /**
     * @throws TransportExceptionInterface
     */
    #[NoReturn]
    public function sendEmail(string $from, string $to, string $subject, string $template, array $context): void
    {
        // On cree le mail
        $email = (new TemplatedEmail())
        ->from($from)
        ->to($to)
        ->subject($subject)
        ->htmlTemplate("emails/$template.html.twig")
        ->context($context);

        //On envoie le mail
        $this->mailer->send($email);
    }
}