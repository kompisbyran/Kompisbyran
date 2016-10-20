<?php

namespace AppBundle\Tests\Controller\Admin2;

use AppBundle\Tests\Phpunit\DatabaseTestCase;
use AppBundle\Tests\Phpunit\Extension\AuthenticationExtensionTrait;
use AppBundle\Tests\Phpunit\Extension\RepositoryExtensionTrait;

class UserControllerTest extends DatabaseTestCase
{
    use AuthenticationExtensionTrait;
    use RepositoryExtensionTrait;

    public function testShouldLoadPriviledgesPage()
    {
        $fluentSpeaker = $this->getUserRepository()->findOneBy(['email' => 'glenn@example.com']);
        $this->authenticateUser($fluentSpeaker, ['ROLE_SUPER_ADMIN']);

        $client = static::$client;
        $client->request('GET', '/admin2/users/priviledges');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
