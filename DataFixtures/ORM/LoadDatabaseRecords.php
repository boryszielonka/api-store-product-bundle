<?php

namespace BorysZielonka\DataFixtures\ORM;

use BorysZielonka\ApiStoreProductBundle\Entity\Product;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadDatabaseRecords implements FixtureInterface
{
    
    /**
     * 
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $product1 = new Product();
        $product1->setName('Produkt 1');
        $product1->setAmount(4);
        $manager->persist($product1);
        
        $product2 = new Product();
        $product2->setName('Produkt 2');
        $product2->setAmount(12);
        $manager->persist($product2);
        
        $product5 = new Product();
        $product5->setName('Produkt 5');
        $product5->setAmount(0);
        $manager->persist($product5);
        
        $product7 = new Product();
        $product7->setName('Produkt 7');
        $product7->setAmount(6);
        $manager->persist($product7);
        
        $product8 = new Product();
        $product8->setName('Produkt 8');
        $product8->setAmount(2);
        $manager->persist($product8);

        $manager->flush();
    }
}

