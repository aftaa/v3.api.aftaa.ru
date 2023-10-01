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

class PostTest extends ApiTestCase
{
    use Jwt;

    private array $expected = [
        'name' => 'New link in block block 1',
        'href' => 'https://new.aftaa.ru/',
        'icon' => 'https://new.aftaa.ru/favicon.ico',
        'private' => true,
        'deleted' => false,
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
    public function testPost(): void
    {
        $response = $this->jwtRequest('POST', '/private/link/', [
            'block_id' => 1,
            'name' => 'New link in block block 1',
            'href' => 'https://new.aftaa.ru/',
            'icon' => 'https://new.aftaa.ru/favicon.ico',
            'private' => true,
        ]);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $expected = $this->expected;
        $linkId = $response->toArray()['id'];
        $expected['id'] = $linkId;

        $response = $this->jwtRequest('GET', '/private/link/' . $linkId);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        self::assertEquals($expected, $response->toArray());
    }
}
