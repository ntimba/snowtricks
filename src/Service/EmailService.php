<?php

namespace App\Service;

use Exception;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Twig\Environment;

class EmailService
{

    private Environment $twig;
    private MailerInterface $mailer;

    /**
     * Constructeur de EmailService
     *
     * @param Environment $twig Instance de Twig pour le rendu des templates.
     * @param MailerInterface $mailer Instance de Mailer pour l'envoi d'emails.
     */
    public function __construct(Environment $twig, MailerInterface $mailer)
    {
        $this->twig = $twig;
        $this->mailer = $mailer;
    }


    /**
     * Envoie un e-mail.
     *
     * @param string $from
     * @param string $to
     * @param string $subject
     * @param string $templatePath
     * @param array $parameters
     * @return void
     */
    public function sendEmail(string $from, string $to, string $subject, string $templatePath, array $parameters): void
    {
        $emailContent = $this->twig->render($templatePath, $parameters);

        $email = (new Email())
            ->from($from)
            ->to($to)
            ->subject($subject)
            ->html($emailContent);

        try {
            $this->mailer->send($email);
        } catch (TransportExceptionInterface $e) {
            throw $e;
        }
    }
}
