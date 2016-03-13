<?php
namespace AppBundle\Tests\Phpunit\Extension;

trait RepositoryExtensionTrait
{
    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getUserRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle:User');
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getMunicipalityRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle:Municipality');
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    protected function getCityRepository()
    {
        return $this->getEntityManager()->getRepository('AppBundle:City');
    }
}
