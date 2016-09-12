<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UserHasMusicCategories extends Constraint
{
    /**
     * @var string
     */
    public $message = 'För att vara musikompis måste du ha anget några musikkategorier på din profil.';

    /**
     * @return string
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'user_has_music_categories';
    }
}
