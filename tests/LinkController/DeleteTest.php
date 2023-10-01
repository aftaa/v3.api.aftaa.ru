<?php

namespace App\Tests\LinkController;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Jwt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class DeleteTest extends ApiTestCase
{
    use Jwt;

    private array $expected = [
        'id' => 1,
        'name' => 'Link 1 in block block 1',
        'href' => 'https://new.aftaa.ru/',
        'icon' => 'https://new.aftaa.ru/favicon.ico',
        'private' => true,
        'deleted' => true,
        'block' => [
            'id' => 1,
            'name' => 'block 1',
            'col' => 1,
            'sort' => 0,
            'private' => true,
            'deleted' => false,
        ],
    ];

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testDelete(): void
    {
        $response = $this->jwtRequest('DELETE', '/private/link/1');
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $response = $this->jwtRequest('GET', '/private/link/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();
        self::assertEquals($this->expected, $arrayResponse);
    }
}
