<?php

namespace App\Mail;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Mailer\MailerInterface;

final class AccessEmail
{
    public function __construct(
        private readonly MailerInterface $mailer
    )
    {
    }

    public function send(User $user): void
    {
        $email = $user->getEmail();
        $password = $user->getPassword();
        $message = (new TemplatedEmail())
            ->to($email)
            ->subject('Welcome to APEX')
            ->htmlTemplate('mail/welcome.html.twig')
            ->context([
                'email' => $email,
                'password' => $password
            ]);

        $this->mailer->send($message);
    }
}