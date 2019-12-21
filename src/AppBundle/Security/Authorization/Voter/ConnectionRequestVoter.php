<?php

namespace AppBundle\Security\Authorization\Voter;

use JMS\DiExtraBundle\Annotation\Service;
use JMS\DiExtraBundle\Annotation\Tag;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use AppBundle\Entity\ConnectionRequest;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

/**
 * @Service("connection_request_voter", public=false)
 * @Tag("security.voter")
 */
class ConnectionRequestVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof ConnectionRequest && in_array($attribute, [
                self::VIEW,
                self::EDIT
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        $connectionRequest = $subject;
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch($attribute) {
            case self::VIEW:
                if (!$connectionRequest->getCity()) {
                    return false;
                }
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
