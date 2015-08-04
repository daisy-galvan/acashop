<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductsController extends Controller
{
    public function productsAction()
    {
        $query = 'select * from aca_product';
        $db = $this->get('aca.db');
        $db->setQuery($query);
        $products = $db->loadObjectList();

        return $this->render('AcaShopBundle:Products:products.html.twig', array(
            'products' => $products));
    }


}
