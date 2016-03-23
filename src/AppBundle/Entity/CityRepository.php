<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

/**
 * Class CityRepository
 * @package AppBundle\Entity
 */
class CityRepository extends EntityRepository
{
    /**
     * @return array
     */
    public function findAll()
    {
        $qb = $this->createQueryBuilder('c');
        $qb->orderBy('c.name', 'ASC');

        return $qb->getQuery()->getResult();
    }
}
