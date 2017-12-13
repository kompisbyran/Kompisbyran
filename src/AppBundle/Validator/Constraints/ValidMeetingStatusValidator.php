<?php

namespace AppBundle\Validator\Constraints;

use AppBundle\Enum\MeetingTypes;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ValidMeetingStatusValidator extends ConstraintValidator
{
    /**
     * @param string $value
     * @param Constraint|ValidMeetingStatus $constraint
     */
    public function validate($value, Constraint $constraint)
    {
        if (!array_key_exists($value, MeetingTypes::listTypesWithTranslationKeys())) {
            $this->context->addViolation($constraint->message);
        }
    }
}
