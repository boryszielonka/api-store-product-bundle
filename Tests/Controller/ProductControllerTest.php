<?php
namespace BorysZielonka\ApiStoreProductBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductControllerTest extends WebTestCase
{

    public function testListAction()
    {
        $request = $this->getMockBuilder('Symfony\Component\HttpFoundation\Request')
            ->setMethods(array('get'))
            ->disableOriginalConstructor()
            ->getMock();

        $client = static::createClient();
        $client->request('GET', '/api/product/');
        $this->assertEquals(
            200, $client->getResponse()->getStatusCode()
        );

        $client->request('GET', '/api/product/?moreThanAmount=1&inStock=0');
        $this->assertEquals(
            400, $client->getResponse()->getStatusCode()
        );

        $client->request('POST', '/api/product/');
        $this->assertEquals(
            400, $client->getResponse()->getStatusCode()
        );
    }

    public function testGetAction()
    {
        $client = static::createClient();
        $client->request('GET', '/api/product/', [7]);
        $this->assertEquals(
            200, $client->getResponse()->getStatusCode()
        );
    }

    public function testCreateAction()
    {
        $product = [
            'name' => 'asdasd',
            'amount' => 3
        ];

        $client = static::createClient();
        $client->request('POST', '/api/product/', $product);
        $this->assertEquals(
            200, $client->getResponse()->getStatusCode()
        );

        $product = [
            'name' => 'asdasd',
            'amount' => null
        ];

        $client = static::createClient();
        $client->request('POST', '/api/product/', $product);
        $this->assertEquals(
            400, $client->getResponse()->getStatusCode()
        );
    }

    public function testUpdateAction()
    {
        $product = [
            'name' => 'test',
            'amount' => 2
        ];

        $client = static::createClient();
        $client->request('PUT', '/api/product/2323423ddddddd4234', $product);
        $this->assertEquals(
            404, $client->getResponse()->getStatusCode()
        );
    }

    public function testDeleteAction()
    {
        $client = static::createClient();
        $client->request('DELETE', '/api/product/');
        $this->assertEquals(
            405, $client->getResponse()->getStatusCode()
        );
    }
}
