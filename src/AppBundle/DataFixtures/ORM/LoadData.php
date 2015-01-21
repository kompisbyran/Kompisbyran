<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\City;
use AppBundle\Entity\User;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadData extends AbstractFixture implements ContainerAwareInterface
{
    /**
     * @var \Symfony\Component\DependencyInjection\ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $this->loadCategories($manager);
        $this->loadCities($manager);
        $this->loadUsers($manager);

        $manager->flush();
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadCategories(ObjectManager $manager)
    {
        $categories = [
            'Fika',
            'Bada',
            'Symfony',
        ];
        foreach ($categories as $i => $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
            $this->addReference(sprintf('category-%s', $i), $category);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadCities(ObjectManager $manager)
    {
        $cities = [
            'Örebro',
            'Stockholm',
            'Göteborg',
        ];
        foreach ($cities as $cityName) {
            $city = new City();
            $city->setName($cityName);
            $manager->persist($city);
        }
    }

    /**
     * @param ObjectManager $manager
     */
    protected function loadUsers(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('learner');
        $user->setEmail('learner@example.com');
        $user->setName('Learner');
        $user->setEnabled(true);
        $user->addRole('ROLE_COMPLETE_USER');
        $user->setWantToLearn(true);
        $user->setAge(35);
        $user->setAbout('Sportintresserad man med 3 barn');
        $user->setCategories([$this->getReference('category-1'), $this->getReference('category-2')]);
        $user->setFrom('Kurdistan');
        $user->setGender('M');
        $user->setLanguages('Svenska, Kurdiska och lite engelska');
        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));
        $manager->persist($user);

        $user = new User();
        $user->setUsername('fluentspeaker');
        $user->setEmail('fluentspeaker@example.com');
        $user->setName('Fluent speaker');
        $user->setEnabled(true);
        $user->setRoles(['ROLE_COMPLETE_USER', 'ROLE_ADMIN']);
        $user->setWantToLearn(false);
        $user->setAge(40);
        $user->setAbout('Sportintresserad lärare');
        $user->setCategories([$this->getReference('category-0'), $this->getReference('category-1')]);
        $user->setFrom('Umeå');
        $user->setGender('M');
        $user->setLanguages('Svenska, engelska och franska');

        $encoder = $this->container->get('security.encoder_factory')->getEncoder($user);
        $user->setPassword($encoder->encodePassword('asdf123', $user->getSalt()));

        $manager->persist($user);
    }
}
