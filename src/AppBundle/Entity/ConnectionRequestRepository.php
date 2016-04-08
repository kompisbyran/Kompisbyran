<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

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
            ->addOrderBy('cr.createdAt', 'ASC')
            ->getQuery()
            ->execute()
            ;
    }

    /**
     * @param User $user
     * @return null|object
     */
    public function findOneByUser(User $user)
    {
        return $this->findOneBy(array('user' => $user));
    }

    /**
     * @param User $user
     * @return bool
     */
    public function hasActiveRequest(User $user)
    {
        return $this->findOneByUser($user) instanceof ConnectionRequest? true: false;
    }
}
