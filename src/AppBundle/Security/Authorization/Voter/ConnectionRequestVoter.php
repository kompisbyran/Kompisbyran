<?php

namespace AppBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\ConnectionRequest;

/**
 * @Service("connection_request_voter", public=false)
 * @Tag("security.voter")
 */
class ConnectionRequestVoter extends AbstractVoter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    /**
     * @return array
     */
    protected function getSupportedAttributes()
    {
        return [
            self::VIEW,
            self::EDIT
        ];
    }

    /**
     * @return array
     */
    protected function getSupportedClasses()
    {
        return [
            ConnectionRequest::class
        ];
    }

    /**
     * @param string $attribute
     * @param object $connectionRequest
     * @param null $user
     * @return bool
     */
    protected function isGranted($attribute, $connectionRequest, $user = null)
    {
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch($attribute) {
            case self::VIEW:
                if ($user->hasAccessToCity($connectionRequest->getCity()) && !$connectionRequest->getPending()) {
                    return true;
                }
            break;
            case self::EDIT:
                if ($user->hasAccessToCity($connectionRequest->getCity())) {
                    return true;
                }
            break;
        }

        return false;
    }
}