<?php

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\ValidationException;
use App\Mail\AccessEmail;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Throwable;

class SecurityController extends AbstractController
{
    public function __construct(
        private readonly Validator                   $validator,
        private readonly EntityManagerInterface      $em,
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly AccessEmail                 $accessEmail
    )
    {
    }

    #[Route(path: '/login', name: 'security_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/request-password', name: 'security_request_password', methods: 'POST')]
    public function requestPassword(Request $request): Response
    {
        $errors = null;
        $code = Response::HTTP_OK;
        $data = json_decode($request->getContent(), true);

        $email = $data['email'];
        $name = $data['name'];

        try {
            $this->validator->validateEmail($email);
            $this->validator->validateName($name);

            $user = $this->em->getRepository(User::class)->findOneBy(['email' => $email]);
            if ($user) {
                throw new ValidationException(['email' => 'User already exists']);
            }

            $randomBytes = random_bytes(10);
            $user = new User();
            $user->setName($name);
            $user->setEmail($email);
            $hashedPassword = $this->passwordHasher->hashPassword($user, $randomBytes);
            $user->setPassword($hashedPassword);
            $user->setRoles(['ROLE_USER']);
            $this->em->persist($user);
            $this->em->flush();
//            $this->accessEmail->send($user);

        } catch (Throwable $e) {
            $errors = $e->errors;
            $code = $e->getCode();
        }

        return new JsonResponse([
            'errors' => $errors
        ], $code);
    }

    #[Route(path: '/logout', name: 'security_logout')]
    public function logout(): Response
    {
        return $this->redirectToRoute('security_login');
    }
}
