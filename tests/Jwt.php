<?php

namespace App\Tests;

use App\DataFixtures\AppFixtures;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;
use function Symfony\Component\String\u;

trait Jwt
{
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
     * @return string
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function getToken(): string
    {
        $response = $this->login(AppFixtures::USERNAME, AppFixtures::PASSWORD);
        return u($response->toArray()['token'])->toString();
    }

    /**
     * @param string $method
     * @param string $url
     * @param array|null $body
     * @return ResponseInterface
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function jwtRequest(string $method, string $url, ?array $body = null): ResponseInterface
    {
        static $token = null;
        if (null === $token) {
            $token = $this->getToken();
        }

        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
                'Content-type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ];

        if (null !== $body) {
            $options['body'] = json_encode($body);
        }

        return self::createClient()->request($method, $url, $options);
    }
}
