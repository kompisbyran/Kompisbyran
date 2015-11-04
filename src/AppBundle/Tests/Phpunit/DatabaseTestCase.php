<?php
namespace AppBundle\Tests\Phpunit;

use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpKernel\KernelInterface;

abstract class DatabaseTestCase extends WebTestCase
{
    /**
     * @var Client
     */
    protected static $client;

    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected static $container;

    /**
     * {@inheritDoc}
     */
    protected function setUp()
    {
        static::$client = static::createClient();
        static::$container = static::$kernel->getContainer();

        $this->startTransaction();
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        $this->rollbackTransaction();

        parent::tearDown();
    }

    protected function startTransaction()
    {
        /** @var $em \Doctrine\ORM\EntityManager */
        foreach (static::$container->get('doctrine')->getManagers() as $em) {
            $em->clear();
            $em->getConnection()->beginTransaction();
        }
    }

    protected function rollbackTransaction()
    {
        //the error can be thrown during setUp
        //It would be caught by phpunit and tearDown called.
        //In this case we could not rollback since container may not exist.
        if (false == static::$container) {
            return;
        }

        /** @var $em \Doctrine\ORM\EntityManager */
        foreach (static::$container->get('doctrine')->getManagers() as $em) {
            $connection = $em->getConnection();

            while ($connection->isTransactionActive()) {
                $connection->rollback();
            }
        }
    }

    /**
     * @return \Doctrine\ORM\EntityManager
     */
    protected function getEntityManager()
    {
        return static::$container->get('doctrine')->getManager();
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        return static::$client;
    }

    /**
     * @return KernelInterface
     */
    protected function getKernel()
    {
        return static::$kernel;
    }
}
