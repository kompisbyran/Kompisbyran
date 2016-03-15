<?php

namespace AppBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class BooleanModelTransformer
 * @package AppBundle\Form\DataTransformer
 */
class BooleanModelTransformer implements DataTransformerInterface
{
    /**
     * @param mixed $boolVal
     * @return string
     */
    public function transform($boolVal)
    {
        if (is_null($boolVal)) {
            return '';
        }

        return $boolVal === true ? '1' : '0';
    }

    /**
     * @param mixed $textVal
     * @return bool|null
     */
    public function reverseTransform($textVal)
    {
        if (is_null($textVal)) {

            return null;
        }

        return $textVal == '1' ? true : false;
    }
}