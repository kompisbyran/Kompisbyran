<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MunicipalityRepository extends EntityRepository
{
    /**
     * @return Municipality[]
     */
    public function findAllStartMunicipalities()
    {
        return $this->createQueryBuilder('m')
            ->where('m.startMunicipality = true')
            ->orderBy('m.name')
            ->getQuery()
            ->getResult()
            ;
    }

}
