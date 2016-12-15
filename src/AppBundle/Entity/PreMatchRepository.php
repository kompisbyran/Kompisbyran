<?php

namespace AppBundle\Entity;

use Doctrine\ORM\EntityRepository;

class PreMatchRepository extends EntityRepository
{
    /**
     * @param $municipalityId
     * @param $preMatchId
     *
     * @return PreMatch
     *
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findByMunicipalityIdAndPreMatchId($municipalityId, $preMatchId)
    {
        return $this->createQueryBuilder('p')
            ->innerJoin('p.municipality', 'm')
            ->where('m.id = :municipalityId')
            ->andWhere('p.id = :preMatchId')
            ->setParameters([
                'municipalityId' => $municipalityId,
                'preMatchId' => $preMatchId,
            ])
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }

    /**
     * @param Municipality $municipality
     *
     * @return PreMatch[]
     */
    public function findVerifiedByMunicipality(Municipality $municipality)
    {
        return $this->createQueryBuilder('p')
            ->where('p.verified = true')
            ->andWhere('p.municipality = :municipality')
            ->setParameter('municipality', $municipality)
            ->orderBy('p.id')
            ->getQuery()
            ->execute()
            ;
    }
}
