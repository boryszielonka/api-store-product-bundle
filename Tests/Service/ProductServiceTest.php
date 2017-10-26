<?php
namespace BorysZielonka\ApiStoreProductBundle\Tests\Service;

use BorysZielonka\ApiStoreProductBundle\Entity\Product;
use BorysZielonka\ApiStoreProductBundle\Repository\ProductRepository;
use BorysZielonka\ApiStoreProductBundle\Service\ProductService;
use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ProductServiceTest extends TestCase
{

    /**
     *
     * @var EntityManager | \Php
     */
    protected $entityManager;

    public function setUp()
    {
        $this->entityManager = $this->getMockBuilder('Doctrine\ORM\EntityManager')
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testProductList()
    {
        $productRepository = $this->getMockBuilder(ProductRepository::class)
            ->setMethods(array('findAll', 'getProductListByMoreThanAmount', 'getProductListByAvailability'))
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository->expects($this->once())
            ->method('getProductListByMoreThanAmount')
            ->willReturn([]);

        $productRepository->expects($this->once())
            ->method('getProductListByAvailability')
            ->willReturn([]);

        $productRepository->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($productRepository);

        $productService = new ProductService($this->entityManager);
        $productService->findProductList(0, 1);
    }

    public function testProductListInvalidParameters()
    {
        $this->setExpectedException(HttpException::class);
        $productService = new ProductService($this->entityManager);
        $productService->findProductList('0', '0');
    }

    public function testDeleteProduct()
    {
        $product = new Product();
        $product->setName('Name product');
        $product->setAmount(1);

        $productRepository = $this->getMockBuilder(ProductRepository::class)
            ->setMethods(array('find'))
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository->expects($this->once())
            ->method('find')
            ->willReturn($product);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($productRepository);

        $this->entityManager->expects($this->once())
            ->method('remove')
            ->with($this->equalTo($product));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $productService = new ProductService($this->entityManager);
        $this->assertTrue($productService->deleteProduct(1));
    }

    public function testUpdateProduct()
    {
        $product = new Product();
        $product->setName('Name product');
        $product->setAmount(1);

        $productRepository = $this->getMockBuilder(ProductRepository::class)
            ->setMethods(array('find'))
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository->expects($this->once())
            ->method('find')
            ->willReturn($product);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($productRepository);

        $this->entityManager->expects($this->once())
            ->method('flush');

        $productService = new ProductService($this->entityManager);
        $this->assertTrue($productService->updateProduct('12asda3', '23', 1));
    }

    public function testFindProductById()
    {
        $product = new Product();
        $product->setName('Name product');
        $product->setAmount(1);

        $productRepository = $this->getMockBuilder(ProductRepository::class)
            ->setMethods(array('find'))
            ->disableOriginalConstructor()
            ->getMock();

        $productRepository->expects($this->once())
            ->method('find')
            ->willReturn($product);

        $this->entityManager->expects($this->once())
            ->method('getRepository')
            ->willReturn($productRepository);

        $productService = new ProductService($this->entityManager);
        $result = $productService->findProductById(1);
        $this->assertEquals($product, $result);
    }

    public function testCreateProduct()
    {
        $product1 = new Product();
        $product1->setName('asdaasaas');
        $product1->setAmount(3);

        $product = $this->createMock(Product::class);

        $product->expects($this->any())
            ->method('setName');

        $product->expects($this->any())
            ->method('setAmount');

        $this->entityManager->expects($this->once())
            ->method('persist')
            ->with($this->equalTo($product1));

        $this->entityManager->expects($this->once())
            ->method('flush');

        $productService = new ProductService($this->entityManager);
        $this->assertTrue($productService->createProduct('asdaasaas', 3));
    }
}
