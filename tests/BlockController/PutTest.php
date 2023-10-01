<?php

namespace App\Tests\BlockController;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Jwt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class PutTest extends ApiTestCase
{
    use Jwt;

    private array $expect = [
        'id' => 1,
        'name' => 'first block',
        'col' => 1,
        'sort' => 10,
        'private' => true,
        'deleted' => false,
    ];

    /**
     * @return void
     * @throws ClientExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     */
    public function testPost(): void
    {
        $response = $this->jwtRequest('PUT', '/private/block/1', [
            'name' => 'first block',
            'col' => 1,
            'sort' => 10,
            'private' => true,
        ]);
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $response = $this->jwtRequest('GET', '/private/block/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();
        self::assertEquals($this->expect, $arrayResponse);
    }
}
