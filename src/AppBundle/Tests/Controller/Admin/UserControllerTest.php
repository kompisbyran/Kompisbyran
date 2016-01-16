<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Tests\Phpunit\DatabaseTestCase;
use AppBundle\Tests\Phpunit\Extension\AuthenticationExtensionTrait;
use AppBundle\Tests\Phpunit\Extension\RepositoryExtensionTrait;

class UserControllerTest extends DatabaseTestCase
{
    use AuthenticationExtensionTrait;
    use RepositoryExtensionTrait;

    public function testShouldLoadUserEditPage()
    {
        $this->authenticateUser(
            $this->getUserRepository()->findOneBy(['email' => 'learner@example.com']),
            ['ROLE_ADMIN']
        );

        $client = static::$client;
        $users = $this->getUserRepository()->findAll();
        $client->request('GET', '/admin/users/' . $users[0]->getId());

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShouldLoadIndexPage()
    {
        $this->authenticateUser(
            $this->getUserRepository()->findOneBy(['email' => 'learner@example.com']),
            ['ROLE_ADMIN']
        );

        $client = static::$client;
        $crawler = $client->request('GET', '/admin/users/');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
        $this->assertTrue($crawler->filter('html:contains("Epost")')->count() > 0);
    }

}
