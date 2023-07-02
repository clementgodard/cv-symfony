<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher) {}

    /**
     * @inheritDoc
     */
    public function load(ObjectManager $manager): void
    {
        $clement = new User();
        $clement->setUsername('clement');
        $clement->setPassword($this->passwordHasher->hashPassword($clement, 'clement'));

        $manager->persist($clement);
        $manager->flush();
    }
}
