<?php

namespace Authorization;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\AppFixtures;
use App\Tests\Jwt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use function Symfony\Component\String\u;

class JwtTest extends ApiTestCase
{
    use Jwt;

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
}
