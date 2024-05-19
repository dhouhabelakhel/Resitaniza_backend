<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Doctrine\Bundle\FixturesBundle\Command\LoadDataFixturesDoctrineCommand;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;
use App\DataFixtures\ResidentFixtures;
class ResidentControllerTest extends WebTestCase
{
    private function loadFixtures()
    {
        $kernel = self::bootKernel();
        $application = new Application($kernel);

        $command = $application->find('doctrine:fixtures:load');
        $commandTester = new CommandTester($command);
        $commandTester->execute([
            '--no-interaction' => true,
            '--env' => 'test',
        ]);
    }

    public function testGetResidents()
    {        $client = static::createClient();

        $this->loadFixtures();
        $client->request('GET', '/Api/resident/');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testAddResident()
    {        $client = static::createClient();

        $this->loadFixtures();

        $data = [
            'firstname' => 'Jane',
            'lastname' => 'Doe',
            'email' => 'jane.doe@example.com',
            'cin' => 87654321,
            'password' => 'password',
        ];

        $client->request(
            'POST',
            '/Api/resident/new',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testFindResident()
    {        $client = static::createClient();

        $this->loadFixtures();
        $client->request('GET', '/Api/resident/1');
        $this->assertResponseIsSuccessful();
        $this->assertResponseStatusCodeSame(200);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testUpdateResident()
    {        $client = static::createClient();

        $this->loadFixtures();

        $data = [
            'firstname' => 'Updated Name',
            'lastname' => 'Updated Lastname',
            'email' => 'updated.email@example.com',
            'cin' => 87654321,
            'password' => 'newpassword',
        ];

        $client->request(
            'PUT',
            '/Api/resident/1/edit',
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode($data)
        );

        $this->assertResponseStatusCodeSame(201);
        $this->assertJson($client->getResponse()->getContent());
    }

    public function testDeleteResident()
    {
        $client = static::createClient();

        $this->loadFixtures();
        $client->request('DELETE', '/Api/resident/delete/1');
        $this->assertResponseStatusCodeSame(200);
    }
}
