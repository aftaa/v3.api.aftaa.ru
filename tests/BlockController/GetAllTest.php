<?php

namespace App\Tests\BlockController;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Jwt;
use Symfony\Component\HttpFoundation\Response;

class GetAllTest extends ApiTestCase
{
    use Jwt;

    private array $expect= [
        0 => [
            'id' => 1,
            'name' => 'block 1',
            'col' => 1,
            'sort' => 0,
            'private' => true,
            'deleted' => false,
        ],
        1 => [
            'id' => 2,
            'name' => 'block 2',
            'col' => 1,
            'sort' => 1,
            'private' => false,
            'deleted' => false,
        ]
    ];

    public function testGetAll(): void
    {
        $response = $this->jwtRequest('GET', '/private/block/');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();
        self::assertEquals($this->expect, $arrayResponse, '');
    }
}
