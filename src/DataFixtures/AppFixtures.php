<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $admin = new User();
        $admin->setEmail("admin@mail.fr");
        $admin->setUsername("Administrateur");
        $admin->setPassword("Administrateur");
        $admin->setRoles(["ROLE_ADMIN"]);
        // $admin->setNom("Admin");
        $admin->setAccountActivate(true);
        $admin->setClientActivate(true);

        $manager->persist($admin);
        
        // createUser("marceaurodrigues@adrar-formation.com", ["ROLE_DEV", "ROLE_ADMIN"], "1234", $manager);

        $manager->flush();
    }
}
