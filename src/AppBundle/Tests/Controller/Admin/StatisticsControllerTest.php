<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Tests\Phpunit\DatabaseTestCase;
use AppBundle\Tests\Phpunit\Extension\AuthenticationExtensionTrait;
use AppBundle\Tests\Phpunit\Extension\RepositoryExtensionTrait;

class StatisticsControllerTest extends DatabaseTestCase
{
    use AuthenticationExtensionTrait;
    use RepositoryExtensionTrait;

    public function testShouldLoadConfirmedMeetingsPage()
    {
        $this->authenticateUser(
            $this->getUserRepository()->findOneBy(['email' => 'learner@example.com']),
            ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']
        );

        $client = static::$client;
        $client->request('GET', '/admin/statistics/confirmed-meetings');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testShouldLoadConnectionRequestCountPage()
    {
        $this->authenticateUser(
            $this->getUserRepository()->findOneBy(['email' => 'learner@example.com']),
            ['ROLE_ADMIN', 'ROLE_SUPER_ADMIN']
        );

        $client = static::$client;
        $client->request('GET', '/admin/statistics/connection-request-count');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
