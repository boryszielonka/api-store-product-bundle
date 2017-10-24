<?php
namespace BorysZielonka\ApiStoreProductBundle\Controller;

use BorysZielonka\ApiStoreProductBundle\Service\ProductService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends FOSRestController
{

    /**
     *
     * @Rest\Get("api/product/")
     * @return View | $products
     */
    public function listAction(Request $request)
    {
        $moreThanAmount = $request->get('moreThanAmount');
        $inStock = $request->get('inStock');

        $productService = $this->get(ProductService::class);
        $products = $productService->findProductList($moreThanAmount, $inStock);
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
        $productService = $this->get(ProductService::class);
        $product = $productService->findProductById($id);

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

        $productService = $this->get(ProductService::class);
        $productService->createProduct($name, $amount);

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

        $productService = $this->get(ProductService::class);
        $productService->updateProduct($productName, $productAmount, $id);

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
        $productService = $this->get(ProductService::class);
        $productService->deleteProduct($id);

        return new View("Product " . $id . " deleted", Response::HTTP_OK);
    }
}
