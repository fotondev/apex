<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Mail\LostpassEmail;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class LostpassController extends AbstractController
{
    public function __construct(
        private readonly Validator              $validator,
        private readonly EntityManagerInterface $em,
        private readonly LostpassEmail          $lostpassEmail,
        private readonly UserPasswordHasherInterface $passwordHasher,
    )
    {
    }

    #[Route('/lostpass', name: 'security_lostpass', methods: 'GET')]
    public function index(): Response
    {
        return $this->render('security/lostpass.html.twig');
    }


    #[Route('/lostpass', name: 'security_lostpass_handle', methods: 'POST')]
    public function handleLostpassRequest(Request $request): JsonResponse
    {
        $errors = null;
        $code = Response::HTTP_OK;
        $email = json_decode($request->getContent(), true);

        try {
            $this->validator->validateEmail($email);
            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            if (!$user) {
                throw new HttpException('User not found', Response::HTTP_NOT_FOUND);
            }
            $user->setLostpassToken(md5(uniqid('lostpass', true) . time()));
            $this->em->persist($user);
            $this->em->flush();

            $this->lostpassEmail->send($user);

        } catch (ValidationException $e) {
            $errors = $e->errors;
            $code = $e->getCode();
        }

        return new JsonResponse(['errors' => $errors], $code);

    }

    #[Route('/reset-password/{token}', name: 'security_reset_password', methods: 'GET')]
    public function resetPassword(string $token): Response
    {
        $user = $this->em->getRepository(User::class)->findOneBy(['lostpassToken' => $token]);
        if (!$user) {
            throw new HttpException('User not found', Response::HTTP_NOT_FOUND);
        }
        return $this->render('security/reset_password.html.twig', ['lostpassToken' => $token]);
    }

    #[Route('/reset-password', name: 'security_reset_password_handle', methods: 'POST')]
    public function handleResetPassword(Request $request): JsonResponse
    {
        $errors = null;
        $code = Response::HTTP_OK;

        $data = json_decode($request->getContent(), true);
        $token = $data['lostpass_token'];

        try {
            $user = $this->em->getRepository(User::class)->findOneBy(['lostpassToken' => $token]);
            if (!$user) {
                throw new ValidationException(['lostpass_token' => 'Invalid token']);
            }

           $password =  $this->validator->validatePassword($data['password']);
           $password2 =  $this->validator->validatePassword($data['confirm_password']);

            if ($password !== $password2) {
                throw new ValidationException(['password' => 'Passwords do not match']);
            }

            $user->setPassword($this->passwordHasher->hashPassword($user, $password));
            $this->em->persist($user);
            $this->em->flush();
            $user->setLostpassToken(null);
            $this->em->persist($user);
            $this->em->flush();

        } catch (ValidationException $e) {
            $errors = $e->errors;
            $code = $e->getCode();
        }
        return new JsonResponse(['errors' => $errors], $code);
    }


}
