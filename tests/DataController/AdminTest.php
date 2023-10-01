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

class AdminTest extends ApiTestCase
{
    use Jwt;

    private $expected = [
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
        'trash' => [
            1 => [
                1 => [
                    'id' => 1,
                    'name' => 'block 1',
                    'col' => 1,
                    'sort' => 0,
                    'deleted' => false,
                    'private' => true,
                    'links' => [
                        0 => [
                            'id' => 3,
                            'name' => 'Link 3 in block block 1',
                            'href' => 'https://new.aftaa.ru/',
                            'icon' => 'https://new.aftaa.ru/favicon.ico',
                            'private' => true,
                        ],
                        1 => [
                            'id' => 4,
                            'name' => 'Link 4 in block block 1',
                            'href' => 'https://new.aftaa.ru/',
                            'icon' => 'https://new.aftaa.ru/favicon.ico',
                            'private' => false,
                        ],
                    ],
                ],
                2 => [
                    'id' => 2,
                    'name' => 'block 2',
                    'col' => 1,
                    'sort' => 1,
                    'deleted' => false,
                    'private' => false,
                    'links' => [
                        0 => [
                            'id' => 7,
                            'name' => 'Link 3 in block block 2',
                            'href' => 'https://new.aftaa.ru/',
                            'icon' => 'https://new.aftaa.ru/favicon.ico',
                            'private' => true,
                        ],
                        1 => [
                            'id' => 8,
                            'name' => 'Link 4 in block block 2',
                            'href' => 'https://new.aftaa.ru/',
                            'icon' => 'https://new.aftaa.ru/favicon.ico',
                            'private' => false,
                        ],
                    ],
                ],
                3 => [
                    'id' => 3,
                    'name' => 'block 3',
                    'col' => 1,
                    'sort' => 2,
                    'deleted' => true,
                    'private' => true,
                    'links' => [
                        0 => [
                            'id' => 11,
                            'name' => 'Link 3 in block block 3',
                            'href' => 'https://new.aftaa.ru/',
                            'icon' => 'https://new.aftaa.ru/favicon.ico',
                            'private' => true,
                        ],
                        1 => [
                            'id' => 12,
                            'name' => 'Link 4 in block block 3',
                            'href' => 'https://new.aftaa.ru/',
                            'icon' => 'https://new.aftaa.ru/favicon.ico',
                            'private' => false,
                        ],
                    ],
                ],
                4 => [
                    'id' => 4,
                    'name' => 'block 4',
                    'col' => 1,
                    'sort' => 3,
                    'deleted' => true,
                    'private' => false,
                    'links' => [
                        0 => [
                            'id' => 15,
                            'name' => 'Link 3 in block block 4',
                            'href' => 'https://new.aftaa.ru/',
                            'icon' => 'https://new.aftaa.ru/favicon.ico',
                            'private' => true,
                        ],
                        1 => [
                            'id' => 16,
                            'name' => 'Link 4 in block block 4',
                            'href' => 'https://new.aftaa.ru/',
                            'icon' => 'https://new.aftaa.ru/favicon.ico',
                            'private' => false,
                        ],
                    ],
                ],
            ],
        ],
        'views' => [],
    ];
    
    /**
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     */
    public function testAdmin(): void
    {
        $response = $this->jwtRequest('GET', '/private/data/admin');
        self::assertResponseStatusCodeSame(Response::HTTP_OK);
        $arrayResponse = $response->toArray();
        self::assertEquals($this->expected, $arrayResponse);
    }
}
