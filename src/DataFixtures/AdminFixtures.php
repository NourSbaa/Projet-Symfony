<?php

namespace App\DataFixtures;

use App\Entity\Etudiants;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AdminFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $passwordEncoder,
    ){}

    public function load(ObjectManager $manager): void
    {
        $admin = new Etudiants();
        $admin->setEmail('admin@demo.tn');
        $admin->setUsername('admin');
        $admin->setPrenomEtudiants('admin');
        $admin->setNomEtudiants('admin');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, 'admin')
        );
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);
        $manager->flush();
    }
}
