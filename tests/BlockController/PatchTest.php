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

class PatchTest extends ApiTestCase
{
    use Jwt;

    private array $expect = [
        'id' => 3,
        'name' => 'block 3',
        'col' => 1,
        'sort' => 2,
        'private' => true,
        'deleted' => false,
    ];

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function testPatch(): void
    {
        $response = $this->jwtRequest('PATCH', '/private/block/3');
        self::assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);

        $response = $this->jwtRequest('GET', '/private/block/3');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();

        self::assertEquals($this->expect, $arrayResponse);
    }
}
