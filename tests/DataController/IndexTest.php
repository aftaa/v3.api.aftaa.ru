<?php

namespace App\Tests\DataController;

use ApiPlatform\Symfony\Bundle\Test\ApiTestCase;
use App\Tests\Jwt;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

class IndexTest extends ApiTestCase
{
    use Jwt;

    private array $expected = [
        'data' => [
            'columns' => [
                1 => [
                    0 => [
                        'id' => 1,
                        'name' => 'block 1',
                        'col' => 1,
                        'sort' => 0,
                        'deleted' => false,
                        'private' => true,
                        'links' => [
                            0 => [
                                'id' => 1,
                                'name' => 'Link 1 in block block 1',
                                'href' => 'https://new.aftaa.ru/',
                                'icon' => 'https://new.aftaa.ru/favicon.ico',
                                'private' => true,
                            ],
                            1 => [
                                'id' => 2,
                                'name' => 'Link 2 in block block 1',
                                'href' => 'https://new.aftaa.ru/',
                                'icon' => 'https://new.aftaa.ru/favicon.ico',
                                'private' => false,
                            ],
                        ],
                    ],
                    1 => [
                        'id' => 2,
                        'name' => 'block 2',
                        'col' => 1,
                        'sort' => 1,
                        'deleted' => false,
                        'private' => false,
                        'links' => [
                            0 => [
                                'id' => 5,
                                'name' => 'Link 1 in block block 2',
                                'href' => 'https://new.aftaa.ru/',
                                'icon' => 'https://new.aftaa.ru/favicon.ico',
                                'private' => true,
                            ],
                            1 => [
                                'id' => 6,
                                'name' => 'Link 2 in block block 2',
                                'href' => 'https://new.aftaa.ru/',
                                'icon' => 'https://new.aftaa.ru/favicon.ico',
                                'private' => false,
                            ],
                        ],
                    ],
                ],
            ],
            'top' => [
                '_1' => [
                    'count' => 4,
                    'name' => 'Link 1 in block block 1',
                    'icon' => 'https://new.aftaa.ru/favicon.ico',
                    'href' => 'https://new.aftaa.ru/',
                    'id' => 1,
                ],
            ],
        ],
    ];


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
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();
        self::assertEquals($this->expected, $arrayResponse);
    }
}
