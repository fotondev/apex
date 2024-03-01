<?php

namespace App\Mail;

use App\Entity\User;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use function Symfony\Component\DependencyInjection\Loader\Configurator\env;

class LostpassEmail
{
    public function __construct(
        private readonly MailerInterface $mailer,
        private readonly UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function send(User $user): void
    {
        $to = $user->getEmail();
        $token = $user->getLostpassToken();
        $resetLink = $this->urlGenerator->generate('security_reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
//
        $message = (new TemplatedEmail())
            ->from('test@test.test')
            ->to($to)
            ->subject('Your APEX Password Reset Instructions')
            ->htmlTemplate('mail/pass_reset.html.twig')
            ->context([
                'reset_link' => $resetLink,
            ]);

//        $email = (new Email())
//            ->to($to)
//            ->subject('Your APEX Password Reset Instructions')
//            ->html($this->twig->render('orders/mail.html.twig', ['resetLink' => $resetLink]));

        $this->mailer->send($message);

    }
}