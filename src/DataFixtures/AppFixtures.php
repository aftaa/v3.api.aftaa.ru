<?php

namespace App\DataFixtures;

use App\Entity\Block;
use App\Entity\Link;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    final public const USERNAME = 'test';
    final public const PASSWORD = 'test';

    public function __construct(
        private UserPasswordHasherInterface $hasher,
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
