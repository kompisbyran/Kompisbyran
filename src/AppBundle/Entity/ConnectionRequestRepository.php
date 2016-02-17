<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class ConnectionRequestRepository extends EntityRepository
{
    /**
     * @param City $city
     * @param bool $wantToLearn
     * @param bool $musicFriend
     * @return ConnectionRequest[]
     */
    public function findForCity(City $city, $wantToLearn, $musicFriend)
    {
        return $this
            ->createQueryBuilder('cr')
            ->where('cr.wantToLearn = :wantToLearn')
            ->andWhere('cr.city = :city')
            ->andWhere('cr.musicFriend = :musicFriend')
            ->setParameters([
                'wantToLearn' => $wantToLearn,
                'city' => $city,
                'musicFriend' => $musicFriend,
            ])
            ->orderBy('cr.sortOrder', 'DESC')
            ->orderBy('cr.createdAt', 'ASC')
            ->getQuery()
            ->execute()
            ;
    }
}
