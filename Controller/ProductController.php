<?php
namespace BorysZielonka\ApiStoreProductBundle\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use BorysZielonka\ApiStoreProductBundle\Entity\Product;
use Doctrine\ORM\EntityManager;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductController extends FOSRestController
{

    /**
     *
     * @Rest\Get("api/product")
     * @return View | $products
     */
    public function listAction(Request $request)
    {
        $productRepo = $this->getDoctrine()->getRepository('BorysZielonkaApiStoreProductBundle:Product');
        $moreThanAmount = $request->get('moreThanAmount');
        $inStock = $request->get('inStock');

        if ($moreThanAmount >= 0 &&
            $moreThanAmount !== NULL &&
            $inStock == 0 &&
            $inStock !== NULL) {
            throw new HttpException(400, "Invalid parameters");
        }

        if ($moreThanAmount >= 0) {
            $products = $productRepo->getProductListByMoreThanAmount($moreThanAmount);
        }
        if ($inStock) {
            $products = $productRepo->getProductListByAvailability($inStock);
        }

        return $products;
    }

    /**
     *
     * @Rest\Get("api/product/{id}")
     * @return $products
     */
    public function getAction(Request $request)
    {
        
    }

    public function createAction()
    {
        
    }

    /**
     *
     * @Rest\Put("api/product/{id}")
     * @return $products
     */
    public function updateAction(Request $request)
    {
        
    }

    /**
     *
     * @Rest\Delete("api/product/{id}")
     * @return $products
     */
    public function deleteAction(Request $request)
    {
        
    }
}
