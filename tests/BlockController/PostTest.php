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

class PostTest extends ApiTestCase
{
    use Jwt;

    private array $expect = [
        'name' => 'block 5',
        'col' => 1,
        'sort' => 4,
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
        $response = $this->jwtRequest('POST', '/private/block/', [
            'name' => 'block 5',
            'col' => 1,
            'sort' => 4,
            'private' => true,
        ]);
        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $blockId = $response->toArray()['id'];

        $response = $this->jwtRequest('GET', '/private/block/' . $blockId);
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();
        $expect = $this->expect;
        $expect['id'] = $blockId;
        self::assertEquals($expect, $arrayResponse);
    }
}
