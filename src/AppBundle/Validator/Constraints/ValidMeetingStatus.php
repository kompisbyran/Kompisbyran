<?php

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ValidMeetingStatus extends Constraint
{
    /**
     * @var string
     */
    public $message = 'Invalid meeting status';

    /**
     * @return string
     */
    public function validatedBy()
    {
        return 'valid_meeting_status';
    }
}
