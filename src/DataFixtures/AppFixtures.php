<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $hasher) {}

    public function load(ObjectManager $manager): void
    {
        // Formateur
        $formateur = new User();
        $formateur->setEmail('formateur@c2rt.fr');
        $formateur->setFirstName('Jean');
        $formateur->setLastName('Formateur');
        $formateur->setRoles(['ROLE_FORMATEUR']);
        $formateur->setPassword($this->hasher->hashPassword($formateur, 'password123'));
        $manager->persist($formateur);

        // Apprenant
        $apprenant = new User();
        $apprenant->setEmail('apprenant@c2rt.fr');
        $apprenant->setFirstName('Marie');
        $apprenant->setLastName('Apprenant');
        $apprenant->setRoles(['ROLE_APPRENANT']);
        $apprenant->setPassword($this->hasher->hashPassword($apprenant, 'password123'));
        $manager->persist($apprenant);

        $manager->flush();
    }
}