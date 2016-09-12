<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Entity\ConnectionRequest;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UserHasMusicCategoriesValidator extends ConstraintValidator
{
    /**
     * @param ConnectionRequest $value
     * @param Constraint|UserHasMusicCategories $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value->isMusicFriend()) {
            if (count($value->getUser()->getMusicCategories()) == 0) {
                $this->context->addViolation($constraint->message);
            }
        }
    }
}
