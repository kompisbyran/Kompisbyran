<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\User;
use AppBundle\Enum\RoleTypes;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

class UserVoter extends AbstractVoter
{
    const VIEW = 'user.view';
    const CHANGE_ROLES = 'user.change_roles';

    /**
     * @return array
     */
    protected function getSupportedAttributes()
    {
        return [
            self::VIEW,
            self::CHANGE_ROLES,
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
                    $connectionRequest = $user->getOpenConnectionRequest();
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

            case self::CHANGE_ROLES:
                if ($loggedInUser == $user) {
                    return false;
                }
                return $loggedInUser->hasRole(RoleTypes::SUPER_ADMIN);
        }

        return false;
    }
}
