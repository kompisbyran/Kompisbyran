<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class InstagramImageRepository extends EntityRepository
{
    /**
     * @param string $instagramImageId
     *
     * @return InstagramImage|null
     */
    public function findOneByInstagramImageId($instagramImageId)
    {
        return $this->createQueryBuilder('ii')
            ->where('ii.instagramImageId = :instagramImageId')
            ->setParameter('instagramImageId', $instagramImageId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
