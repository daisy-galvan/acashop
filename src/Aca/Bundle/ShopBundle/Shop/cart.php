<?php

namespace Aca\Bundle\ShopBundle\Shop;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Aca\Bundle\ShopBundle\Shop\product;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class cart
 * @package Aca\Bundle\ShopBundle\Shop
 */
class cart extends abstractOrder
{
    /**
     * product class
     * @var product
     */
    protected $product;

    /**
     * Grand total for everything in cart
     * @var float
     */
    protected $grandTotal;

    /**
     * Products user added to their shopping cart, with details
     * @var array
     */
    protected $userSelectedProducts;

    /**
     * @param $db
     * @param $session
     * @param $product
     */
    public function __construct($db, $session, $product)
    {
        parent::__construct($db, $session);

        $this->product = $product;
    }

    /**
     * Delete a product from shopping cart
     * @param int $productID primary key from product table
     * @return bool
     * @throws \Exception
     */
    public function delete($productID)
    {
        $cartItems = $this->session->get('cart');

        foreach ($cartItems as $index => $cartItem) {
            if ($cartItem['product_id'] == $productID) {
                unset($cartItems[$index]);
            }
        }

        $this->session->set('cart', $cartItems);

        $didRemove = true;

        foreach ($cartItems as $index => $cartItem) {
            if ($cartItem['product_id'] == $productID) {
                $didRemove = false;
            }
        }

        if (!$didRemove) {
            throw new \Exception('Cannot delete item from cart!');
        }

        return $didRemove;
    }


    /**
     * Update quantity of item in cart
     * @param int $productID PK from product
     * @param int $updatedQuantity
     * @return void
     */
    public function updateQuantity($productID, $updatedQuantity)
    {
        $cartItems = $this->session->get('cart');

        foreach ($cartItems as $index => $cartItem) {
            if ($cartItem['product_id'] == $productID) {
                $cartItems[$index]['quantity'] = $updatedQuantity;
            }
        }

        $this->session->set('cart', $cartItems);
    }

    /**
     * Get an array of productIds from the shopping cart
     * @throws \Exception
     * @return array
     */
    public function getProductIds()
    {
        $cartItems = $this->session->get('cart');

        if (empty($cartItems)) {
            throw new \Exception('The cart is empty!');
        }

        $cartProductIds = [];
        foreach ($cartItems as $cartItem) {
            $cartProductIds[] = $cartItem['product_id'];
        }

        return $cartProductIds;
    }

    public function getProducts()
    {
        if (isset($this->userSelectedProducts)) {
            return $this->userSelectedProducts;
        }

        $cartItems = $this->session->get('cart');
        $cartProductIds = $this->getProductIds();
        $dbProducts = $this->product->getProductsByProductIds($cartProductIds);

        /** @var $userSelectedProducts Contains the merge of products/cart items */
        $userSelectedProducts = [];

        $grandTotal = 0.00;

        foreach ($cartItems as $cartItem) {
            foreach ($dbProducts as $dbProduct) {
                if ($dbProduct->product_id == $cartItem['product_id']) {
                    $dbProduct->quantity = $cartItem['quantity'];

                    $dbProduct->total_price = $dbProduct->price * $cartItem['quantity'];
                    $grandTotal += $dbProduct->total_price;

                    $userSelectedProducts[] = $dbProduct;
                }
            }
        }

        $this->grandTotal = $grandTotal;
        $this->userSelectedProducts = $userSelectedProducts;

        if (empty($userSelectedProducts)) {
            throw new \Exception("Please add something to your cart.");
        }

        return $userSelectedProducts;
    }

    public function getGrandTotal()
    {
        if (!isset($this->grandTotal)) {
            $this->getProducts();
        }
        return $this->grandTotal;
    }
}
