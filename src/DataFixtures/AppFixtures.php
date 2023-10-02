<?php

namespace App\DataFixtures;

use App\Entity\Block;
use App\Entity\Link;
use App\Entity\User;
use App\Entity\View;
use App\Repository\BlockRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    final public const USERNAME = 'test@test.test';
    final public const PASSWORD = 'test';

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly BlockRepository             $blockRepository,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        {
            $private = [true, false, true, false];
            $deleted = [false, false, true, true];

            for ($i = 0; $i < 4; $i++) {
                $block = new Block();
                $block->setName('block ' . ($i + 1));
                $block->setCol(1);
                $block->setSort($i);
                $block->setPrivate($private[$i]);
                $block->setDeleted($deleted[$i]);

                for ($j = 0; $j < 4; $j++) {
                    $link = new Link();
                    $link
                        ->setName('Link ' . ($j + 1) . ' in block ' . $block->getName())
                        ->setHref('https://new.aftaa.ru/')
                        ->setIcon('https://new.aftaa.ru/favicon.ico')
                        ->setPrivate($private[$j])
                        ->setDeleted($deleted[$j]);
                    $block->addLink($link);
                }
                $manager->persist($block);
            }
            $manager->flush();

            $block = $this->blockRepository->find(1);

            for ($k = 0; $k < 4; $k++) {
                $view = new View();
                $view
                    ->setDateTime(new \DateTime('2023-10-02 05:28:00'))
                    ->setIp4(ip2long('127.0.0.1'));
                $block->getLinks()[0]->addView($view);
            }

            $manager->persist($block, true);


            $user = new User();
            $user->setEmail(self::USERNAME);
            $user->setRoles(['ROLE_USER']);
            $password = $this->hasher->hashPassword($user, self::PASSWORD);
            $user->setPassword($password);
            $manager->persist($user);
            $manager->flush();
        }
    }
}
