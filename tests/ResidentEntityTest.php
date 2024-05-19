<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Resident;
class ResidentEntityTest extends TestCase
{
    public function testId()
    {
        $resident = new Resident();
        $resident->setId(1);
        $this->assertEquals(1, $resident->getId());
    }

    public function testEmail()
    {
        $resident = new Resident();
        $email = 'test@example.com';
        $resident->setEmail($email);
        $this->assertEquals($email, $resident->getEmail());
    }

    public function testRoles()
    {
        $resident = new Resident();
        $this->assertEquals(['Resident'], $resident->getRoles());
    }

    public function testPassword()
    {
        $resident = new Resident();
        $password = 'password123';
        $resident->setPassword($password);
        $this->assertEquals($password, $resident->getPassword());
    }

    public function testName()
    {
        $resident = new Resident();
        $resident->setFirstname('John');
        $resident->setLastname('Doe');
        $this->assertEquals('John', $resident->getFirstname());
        $this->assertEquals('Doe', $resident->getLastname());
    }

    public function testCin()
    {
        $resident = new Resident();
        $cin = 123456789;
        $resident->setCin($cin);
        $this->assertEquals($cin, $resident->getCin());
    }

    public function testBirthdate()
    {
        $resident = new Resident();
        $birthdate = new \DateTime('1990-01-01');
        $resident->setBirthdate($birthdate);
        $this->assertEquals($birthdate, $resident->getBirthdate());
    }

    public function testPicture()
    {
        $resident = new Resident();
        $picture = 'profile.jpg';
        $resident->setPicture($picture);
        $this->assertEquals($picture, $resident->getPicture());
    }

    public function testPhoneNumber()
    {
        $resident = new Resident();
        $phoneNumber = '1234567890';
        $resident->setPhonenumber($phoneNumber);
        $this->assertEquals($phoneNumber, $resident->getPhonenumber());
    }
}
