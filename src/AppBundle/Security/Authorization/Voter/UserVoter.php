<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends AbstractVoter
{
    const VIEW = 'user.view';

    /**
     * @return array
     */
    protected function getSupportedAttributes()
    {
        return [
            self::VIEW,
        ];
    }

    /**
     * @return array
     */
    protected function getSupportedClasses()
    {
        return [
            User::class
        ];
    }

    /**
     * @param string $attribute
     * @param User $user
     * @param User $loggedInUser
     * @return bool
     */
    protected function isGranted($attribute, $user, $loggedInUser = null)
    {
        if (!$loggedInUser instanceof UserInterface) {
            return false;
        }

        switch($attribute) {
            case self::VIEW:
                if ($loggedInUser->hasRole('ROLE_ADMIN')) {
                    return true;
                }
                foreach ($loggedInUser->getAdminMunicipalities() as $adminMunicipality) {
                    /** @var ConnectionRequest $connectionRequest */
                    $connectionRequest = $user->getConnectionRequests()->first();
                    if ($connectionRequest) {
                        if ($adminMunicipality == $connectionRequest->getMunicipality()) {
                            return true;
                        }
                    }
                    foreach ($user->getLearnerConnections() as $connection) {
                        if ($adminMunicipality == $connection->getMunicipality()) {
                            return true;
                        }
                    }
                    foreach ($user->getFluentSpeakerConnections() as $connection) {
                        if ($adminMunicipality == $connection->getMunicipality()) {
                            return true;
                        }
                    }
                }
                break;
        }

        return false;
    }
}
