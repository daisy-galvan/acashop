<?php

namespace Aca\Bundle\ShopBundle\Shop;

use Aca\Bundle\ShopBundle\Db\DBCommon;

/**
 * Class product represents product-related functionality
 * @package Aca\Bundle\ShopBundle\Shop
 */
class product {
    // add a constructor dependency
    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * get all product rows from db
     * @return \stdClass
     */
    public function getAllProducts()
    {
        $query = 'select * from aca_product';
        $this->db->setQuery($query);
        $products = $this->db->loadObjectList();

        return $products;
    }

    /**
     * Get a number of products from db from specified productIds
     * @param array $productIds
     * @return \stdClass[]
     */
    public function getProductsByProductIds($productIds){
        if(empty($productIds)){
            return null;
        }
        $query = 'select * from aca_product where product_id
                  in(' . implode(',', $productIds) . ')';


        $this->db->setQuery($query);
        $dbProducts = $this->db->loadObjectList();

        return $dbProducts;
    }
}