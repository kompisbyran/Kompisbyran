<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ConnectionRepository extends EntityRepository
{
    /**
     * @return \Doctrine\ORM\Query
     */
    public function getFindAllQuery()
    {
        return $this
            ->createQueryBuilder('c')
            ->orderBy('c.id', 'desc')
            ->getQuery()
        ;
    }
}
