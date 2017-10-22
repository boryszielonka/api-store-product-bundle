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

    const BUNDLE_CLASS_NAME = "BorysZielonkaApiStoreProductBundle:Product";

    /**
     *
     * @Rest\Get("api/product")
     * @return View | $products
     */
    public function listAction(Request $request)
    {
        $productRepo = $this->getDoctrine()->getRepository(self::BUNDLE_CLASS_NAME);
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
     * @param type $id
     * @return type
     * @throws type
     */
    public function getAction($id)
    {
        $product = $this->getDoctrine()
            ->getRepository(self::BUNDLE_CLASS_NAME)
            ->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        return $product;
    }

    /**
     * 
     * @Rest\Post("api/product/")
     * @param Request $request
     * @return View
     * @throws type
     */
    public function createAction(Request $request)
    {
        $name = $request->get('name');
        $amount = $request->get('amount');

        if (empty($name) || empty($amount)) {
            throw $this->createNotFoundException('At least one parameter is empty.');
        }

        if (!preg_match('/^\d+$/', $amount)) {
            throw $this->createNotFoundException('Invalid amount. Use digits only.');
        }

        $product = new Product();
        $product->setName($name);
        $product->setAmount($amount);

        $em = $this->getDoctrine()->getManager();
        $em->persist($product);
        $em->flush();

        return new View("Product created", Response::HTTP_OK);
    }

    /**
     * 
     * 
     * @Rest\Put("api/product/{id}")
     * @param Request $request
     * @param type $id
     * @return View
     * @throws type
     */
    public function updateAction(Request $request, $id)
    {
        $productName = $request->get('name');
        $productAmount = $request->get('amount');
        $em = $this->getDoctrine()->getManager();
        $product = $em->getRepository(self::BUNDLE_CLASS_NAME)->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found with id ' . $id);
        }
        if (empty($productName) && empty($productAmount)) {
            throw $this->createNotFoundException('Nothing to update for product ' . $id);
        }

        if (isset($productName)) {
            $product->setName($productName);
        }
        if (isset($productAmount)) {
            $product->setAmount($productAmount);
        }

        $em->flush();

        return new View("Product " . $id . " updated", Response::HTTP_OK);
    }

    /**
     * 
     * @Rest\Delete("api/product/{id}")
     * @param type $id
     * @return View
     * @throws type
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $productRepo = $em->getRepository(self::BUNDLE_CLASS_NAME);
        $product = $productRepo->find($id);

        if (!$product) {
            throw $this->createNotFoundException('No product found for id ' . $id);
        }

        $em->remove($product);
        $em->flush();

        return new View("Product " . $id . " deleted", Response::HTTP_OK);
    }
}
