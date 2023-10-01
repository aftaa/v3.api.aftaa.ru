<?php

namespace App\Tests;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\AppFixtures;
use JsonException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\String\UnicodeString;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function Symfony\Component\String\u;

class JwtTest extends ApiTestCase
{
    public UnicodeString $token;

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testLoginOK(): void
    {
        $response = $this->login(AppFixtures::USERNAME, AppFixtures::PASSWORD);
        self::assertResponseIsSuccessful();
        $arrayResponse = $response->toArray();
        self::assertArrayHasKey('token', $arrayResponse);
        $token = u($response->toArray()['token']);
        self::assertNotTrue($token->isEmpty());

    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws JsonException
     */
    public function testLoginNOK(): void
    {
        $this->login('reader', 'wrong password');
        self::assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
        self::assertResponseHeaderSame('content-type', 'application/json');
        self::assertJsonEquals([
            'code' => Response::HTTP_UNAUTHORIZED,
            'message' => 'Invalid credentials.',
        ]);
        self::assertJsonContains([
            'message' => 'Invalid credentials.',
        ]);
    }

    /**
     * JSON Login try with a given email and password.
     * @throws TransportExceptionInterface
     */
    public function login($username, $password): ResponseInterface
    {
        return self::createClient()->request('POST', '/login_check', [
            'json' => compact('username', 'password'),
            'headers' => [
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testTestController()
    {
        $response = $this->login(AppFixtures::USERNAME, AppFixtures::PASSWORD);
        $token = u($response->toArray()['token'])->toString();

        $response = self::createClient()->request('GET', '/private/test', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);

        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();
        self::assertArrayHasKey('user', $arrayResponse);
    }
}
