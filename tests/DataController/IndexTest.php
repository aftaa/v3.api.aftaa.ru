<?php

namespace App\Tests\DataController;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Jwt;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class IndexTest extends ApiTestCase
{
    use Jwt;

    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testIndex(): void
    {
        $response = $this->jwtRequest('GET', '/private/data/index');
        dump($response->toArray());die;
    }
}
