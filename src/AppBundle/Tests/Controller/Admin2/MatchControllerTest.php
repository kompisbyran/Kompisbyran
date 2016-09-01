<?php

namespace AppBundle\Tests\Controller\Admin;

use AppBundle\Tests\Phpunit\DatabaseTestCase;
use AppBundle\Tests\Phpunit\Extension\AuthenticationExtensionTrait;
use AppBundle\Tests\Phpunit\Extension\RepositoryExtensionTrait;

class MatchControllerTest extends DatabaseTestCase
{
    use AuthenticationExtensionTrait;
    use RepositoryExtensionTrait;

    public function testShouldMatchUsers()
    {
        $learner = $this->getUserRepository()->findOneBy(['email' => 'learner@example.com']);
        $fluentSpeaker = $this->getUserRepository()->findOneBy(['email' => 'glenn@example.com']);

        $this->authenticateUser($learner, ['ROLE_ADMIN']);

        $client = static::$client;

        $client->request('POST',
            '/admin2/matches/approve',
            ['match' => [
                'user_id' => $learner->getId(),
                'match_user_id' => $fluentSpeaker,
                'email_to_user' => '',
                'email_to_match_user' => '',
            ]]
        );

        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }
}
