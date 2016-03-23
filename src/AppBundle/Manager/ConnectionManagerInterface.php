<?php

namespace AppBundle\Manager;

/**
 * Interface ConnectionManagerInterface
 * @package AppBundle\Manager
 */
interface ConnectionManagerInterface
{
    /**
     * @return mixed
     */
    public function createNew();
}