<?php

namespace App\Tests;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityTest extends WebTestCase
{


    protected static function getKernelClass(): string
    {
        return \App\Kernel::class;
    }

    /**
     * @dataProvider getUrlsForAnonymousUsers
     */
    public function testAccessDeniedForAnonymousUsers(string $httpMethod, string $url): void
    {
        $client = static::createClient();
        $client->request($httpMethod, $url);

        $this->assertResponseRedirects(
            '/login',
            Response::HTTP_FOUND,
            sprintf('The %s secure URL redirects to the login form.', $url)
        );
    }

    /**
     * @return @dataProvider getPublicUrls
     */
    public function testPublicUrls(string $httpMethod, string $url): void
    {
        $client = static::createClient();
        $client->request($httpMethod, $url);

        $this->assertResponseIsSuccessful();
    }

    public function getUrlsForAnonymousUsers(): \Generator
    {
        yield ['GET', '/dashboard'];
    }

    public function getPublicUrls(): \Generator
    {
        yield ['GET', '/login'];
        yield ['GET', '/lostpass'];
    }


    public function testRequestPassword(): void
    {
        $client = static::createClient();

        $data = [
            'email' => 'test' . time() . '@example.com',
            'name' => 'john',
        ];

        $client->request(
            'POST',
            '/request-password',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertNull($responseData['errors']);
    }


    public function testHandleLostpassRequest(): void
    {
        $client = static::createClient();

        $data = 'test@example.com';

        $client->request(
            'POST',
            '/lostpass',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertNull($responseData['errors']);
    }


    public function testResetPassword(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);

        $client->request('GET', "/reset-password/{$user->getLostpassToken()}");

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $this->assertStringContainsString($user->getLostpassToken(), $response->getContent());;
    }


    public function testHandleResetPassword(): void
    {
        $client = static::createClient();
        $entityManager = $client->getContainer()->get('doctrine')->getManager();

        $user = $entityManager->getRepository(User::class)->findOneBy(['email' => 'test@example.com']);

        $data = [
            'lostpass_token' => $user->getLostpassToken(),
            'password' => 'new_password',
            'confirm_password' => 'new_password',
        ];

        $client->request(
            'POST',
            '/reset-password',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $response = $client->getResponse();

        $this->assertSame(Response::HTTP_OK, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertNull($responseData['errors']);

        $entityManager->refresh($user);
        $hashedPassword = $user->getPassword();
        $this->assertTrue(password_verify('new_password', $hashedPassword));

        $this->assertNull($user->getLostpassToken());


    }


}
