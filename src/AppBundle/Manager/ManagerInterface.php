<?php

namespace AppBundle\Manager;

/**
 * Interface ManagerInterface
 * @package AppBundle\Manager
 */
interface ManagerInterface
{
    /**
     * @return mixed
     */
    public function createNew();

    /**
     * @param $entity
     * @return mixed
     */
    public function save($entity);

    /**
     * @param $id
     * @return mixed
     */
    public function getFind($id);

    /**
     * @return mixed
     */
    public function getFindAll();

    /**
     * @param $entity
     * @return mixed
     */
    public function remove($entity);
}