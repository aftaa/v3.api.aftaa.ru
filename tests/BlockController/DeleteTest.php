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

class DeleteTest extends ApiTestCase
{
    use Jwt;

    private array $expect = [
        'id' => 1,
        'name' => 'block 1',
        'col' => 1,
        'sort' => 0,
        'private' => true,
        'deleted' => true,
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
        $response = $this->jwtRequest('DELETE', '/private/block/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);

        $response = $this->jwtRequest('GET', '/private/block/1');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();

        self::assertEquals($this->expect, $arrayResponse);
    }
}
