<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class MunicipalityRepository extends EntityRepository
{
    /**
     * @return Municipality[]
     */
    public function findAllActiveStartMunicipalities()
    {
        return $this->createQueryBuilder('m')
            ->where('m.activeStartMunicipality = true')
            ->orderBy('m.name')
            ->getQuery()
            ->getResult()
            ;
    }

}
