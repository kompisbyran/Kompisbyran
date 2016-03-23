<?php

namespace AppBundle\Manager;

/**
 * Interface CategoryInterface
 * @package AppBundle\Manager
 */
interface CategoryInterface
{
    /**
     * @return mixed
     */
    public function getFindAllMusic();

    /**
     * @return mixed
     */
    public function getFindAllGeneral();

    /**
     * @return mixed
     */
    public function getFindAllQueryBuilder();
}