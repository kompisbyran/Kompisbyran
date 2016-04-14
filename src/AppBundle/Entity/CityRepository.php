<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\City;

/**
 * Class CityRepository
 * @package AppBundle\Entity
 */
class CityRepository extends EntityRepository
{
    /**
     * @param City $city
     * @return City
     */
    public function save(City $city)
    {
        $this->getEntityManager()->persist($city);
        $this->getEntityManager()->flush();

        return $city;
    }

    /**
     * @param City $city
     */
    public function remove(City $city)
    {
        $this->getEntityManager()->remove($city);
        $this->getEntityManager()->flush();
    }

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
