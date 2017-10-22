<?php
namespace BorysZielonka\ApiStoreProductBundle\Repository;

/**
 * ProductRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ProductRepository extends \Doctrine\ORM\EntityRepository
{

    public function getProductListByMoreThanAmount($MoreThanAmount)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select('p, p.name, p.amount');
        $queryBuilder->where('p.amount > :amount');
        $queryBuilder->setParameter('amount', $MoreThanAmount);

        return $queryBuilder->getQuery()->getResult();
    }

    public function getProductListByAvailability($inStock)
    {
        $queryBuilder = $this->createQueryBuilder('p');
        $queryBuilder->select('p, p.name, p.amount');
        if ($inStock) {
            $queryBuilder->where('p.amount >= 1');
        } else {
            $queryBuilder->where('p.amount < 1');
        }
        
        return $queryBuilder->getQuery()->getResult();
    }
}
