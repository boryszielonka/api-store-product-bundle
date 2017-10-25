<?php
namespace BorysZielonka\ApiStoreProductBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpKernel\Exception\HttpException;
use BorysZielonka\ApiStoreProductBundle\Entity\Product;

/**
 * 
 */
class ProductService
{

    const BUNDLE_CLASS_NAME = "BorysZielonkaApiStoreProductBundle:Product";

    /**
     *
     * @var Doctrine\ORM\EntityManager 
     */
    protected $entityManager;

    /**
     * 
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * 
     * @param type $moreThanAmount
     * @param type $inStock
     * @return type
     * @throws HttpException
     * @return array
     */
    public function findProductList($moreThanAmount, $inStock)
    {
        $productRepo = $this->entityManager->getRepository(self::BUNDLE_CLASS_NAME);
        $products = [];
        if (isset($moreThanAmount)) {
            if ($inStock === '0') {
                throw new HttpException(400, "Invalid parameters");
            }
            $products = array_merge($products, $productRepo->getProductListByMoreThanAmount($moreThanAmount));
        }

        if (isset($inStock)) {
            $products = array_merge($products, $productRepo->getProductListByAvailability($inStock));
        }

        if (empty($products)) {
            $products = $productRepo->findAll();
        }

        return $products;
    }

    /**
     * 
     * @param type $id
     * @return type
     * @throws HttpException
     * @return array
     */
    public function findProductById($id)
    {
        $product = $this->entityManager
            ->getRepository(self::BUNDLE_CLASS_NAME)
            ->find($id);

        if (!$product) {
            throw new HttpException(404, 'No product found for id ' . $id);
        }

        return $product;
    }

    /**
     * 
     * @param type $name
     * @param type $amount
     * @throws HttpException
     * @return void
     */
    public function createProduct($name, $amount)
    {
        if (empty($name) || $amount == NULL) {
            throw new HttpException(400, 'At least one parameter is empty.');
        }

        if (!preg_match('/^\d+$/', $amount)) {
            throw new HttpException(400, 'Invalid amount. Use digits only.');
        }

        $product = new Product();
        $product->setName($name);
        $product->setAmount($amount);

        $this->entityManager->persist($product);
        $this->entityManager->flush();
    }

    /**
     * 
     * @param type $productName
     * @param type $productAmount
     * @param type $id
     * @throws HttpException
     * @return void
     */
    public function updateProduct($productName, $productAmount, $id)
    {
        $em = $this->entityManager;
        $product = $em->getRepository(self::BUNDLE_CLASS_NAME)->find($id);

        if (!$product) {
            throw new HttpException(404, 'No product found for id ' . $id);
        }
        if (empty($productName) && empty($productAmount)) {
            throw new HttpException(400, 'Nothing to update for product ' . $id);
        }

        if (isset($productName)) {
            $product->setName($productName);
        }
        if (isset($productAmount)) {
            $product->setAmount($productAmount);
        }

        $em->flush();
    }

    /**
     * 
     * @param type $id
     * @throws HttpException
     * @return void
     */
    public function deleteProduct($id)
    {
        $em = $this->entityManager;
        $productRepo = $em->getRepository(self::BUNDLE_CLASS_NAME);
        $product = $productRepo->find($id);

        if (!$product) {
            throw new HttpException(404, 'No product found for id ' . $id);
        }

        $em->remove($product);
        $em->flush();
    }
}
