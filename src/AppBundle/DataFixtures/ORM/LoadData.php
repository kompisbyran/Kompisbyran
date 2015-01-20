<?php

namespace AppBundle\DataFixtures\ORM;

use AppBundle\Entity\Category;
use AppBundle\Entity\City;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $categories = [
            'Fika',
            'Bada',
            'Symfony',
        ];
        foreach ($categories as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);
            $manager->persist($category);
        }

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

        $manager->flush();
    }
}
