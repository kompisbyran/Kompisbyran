<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\ConnectionRequest;
use AppBundle\Entity\Municipality;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class MunicipalityVoter extends Voter
{
    const ADMIN_VIEW = 'municipality.admin_view';

    protected function supports($attribute, $subject)
    {
        return $subject instanceof Municipality && in_array($attribute, [
                self::ADMIN_VIEW,
            ]);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();
        $municipality = $subject;

        if (!$user instanceof UserInterface) {
            return false;
        }

        switch($attribute) {
            case self::ADMIN_VIEW:
                foreach ($user->getAdminMunicipalities() as $adminMunicipality) {
                    if ($adminMunicipality == $municipality) {
                        return true;
                    }
                }
                break;
        }

        return false;
    }
}
