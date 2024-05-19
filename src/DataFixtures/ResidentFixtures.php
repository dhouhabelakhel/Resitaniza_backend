<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Entity\Resident;
class ResidentFixtures extends Fixture
{
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager)
    {
        $resident = new Resident();
        $resident->setFirstname('John');
        $resident->setLastname('Doe');
        $resident->setEmail('john.doe@example.com');
        $resident->setCin('12345678');
        $hashedPassword = $this->passwordHasher->hashPassword($resident, 'password');
        $resident->setPassword($hashedPassword);
        $manager->persist($resident);
        $manager->flush();
}
}