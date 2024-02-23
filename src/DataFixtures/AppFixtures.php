<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;


class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $admin = new User();
        // $admin->setEmail("admin@mail.fr");
        // $admin->setUsername("Administrateur");
        // $admin->setPassword("Administrateur");
        // $admin->setRoles(["ROLE_ADMIN"]);
        // $admin->setAccountActivate(true);
        // $admin->setClientActivate(true);

        // $manager->persist($admin);

        // $manager->flush();
    }
}
