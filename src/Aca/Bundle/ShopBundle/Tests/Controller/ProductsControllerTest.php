<?php

namespace Aca\Bundle\ShopBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsControllerTest extends WebTestCase
{
    public function testProducts()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/products');
    }

}
