<?php

namespace AppBundle\Security\Authorization\Voter;

use AppBundle\Entity\Municipality;
use AppBundle\Entity\User;
use Symfony\Component\Security\Core\Authorization\Voter\AbstractVoter;
use Symfony\Component\Security\Core\User\UserInterface;

class MunicipalityVoter extends AbstractVoter
{
    const ADMIN_VIEW = 'municipality.admin_view';

    /**
     * @return array
     */
    protected function getSupportedAttributes()
    {
        return [
            self::ADMIN_VIEW,
        ];
    }

    /**
     * @return array
     */
    protected function getSupportedClasses()
    {
        return [
            Municipality::class
        ];
    }

    /**
     * @param string $attribute
     * @param object $municipality
     * @param User $user
     * @return bool
     */
    protected function isGranted($attribute, $municipality, $user = null)
    {
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
