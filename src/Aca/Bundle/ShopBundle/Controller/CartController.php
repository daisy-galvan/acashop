<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class CartController extends Controller
{
    /**
     * Add item to shopping cart
     * @return RedirectResponse
     */
    public function addAction()
    {
        /** @var  $session */
        $session = $this->get('session');

        $cart = $session->get('cart');

        $productID = $_POST['product_id'];
        $quantity = $_POST['quantity'];

        // First addition to the shopping cart
        if (empty($cart)) {
            $cart[] = array(
                'product_id' => $productID,
                'quantity' => $quantity
            );
        } else {
            $existingItem = false;

            foreach ($cart as &$cartItem) {
                // If product already exists in cart
                if ($cartItem['product_id'] == $productID) {
                    $existingItem = true;

                    // add to existing quantity
                    $cartItem['quantity'] += $quantity;
                }
            }

            // if brand new item
            if ($existingItem == false) {
                $cart[] = array(
                    'product_id' => $productID,
                    'quantity' => $quantity
                );
            }
        }

        $session->set('cart', $cart);


        return new RedirectResponse('/cart');
    }

    /**
     * Show user's shopping cart contents
     */
    public function  showAction()
    {

        /** @var DBCommon $db */
        $db = $this->get('aca.db');
        $session = $this->get('session');
        $cartItems = $session->get('cart');

//        $cartProductIDs= [];
//
//        foreach ($cartItems as $cartItem) {
//            $cartProductIDs =$cartItem['product_id'];
//        }
//
//        $query = 'select * from aca_product where product_id
//            in (' . implode(',', $cartProductIDs) . ')';
//        $db->setQuery($query);
//        $dbProducts= $db->loadObjectList();
//
//
//        $userSelectedProducts=[];
//        $grandTotal= 0.00;
//        foreach ($cartItems as $cartItem) {
//
//        }
//
//
//            /** @var array $userSelectedProducts Contains the merge of products/cart items */
//            $userSelectedProducts = [];
//            // Challenge: Is to merge the items in the cart, with products!
//            // Hint: two loops! loop through the DB products first, then cart items
//            $grandTotal = 0.00;
//            foreach ($cartItems as $cartItem) {
//                foreach ($dbProducts as $dbProduct) {
//                    // magic happens! the struggle is real...
//                    if ($dbProduct->product_id == $cartItem['product_id']) {
//                        $dbProduct->quantity = $cartItem['quantity'];
//                        $dbProduct->total_price = $dbProduct->price * $cartItem['quantity'];
//                        $grandTotal += $dbProduct->total_price;
//                        $userSelectedProducts[] = $dbProduct;
//                    }
//                }
//            }
//            return $this->render('AcaShopBundle:Cart:list.html.twig',
//                array(
//                    'products' => $userSelectedProducts,
//                    'grandTotal' => $grandTotal
//                )
//            );
//        }
    }

    /**
     * Delete an item from cart
     * @return RedirectResponse
     */
    public function deleteAction()
    {
        $productID = $_POST['product_id'];

        $session = $this->get('session');
        $cartItems = $session->get('cart');

        foreach ($cartItems as $index => $cartItem) {
            if ($cartItem['product_id'] == $productID) {
                unset($cartItems[$index]);
            }
        }

        $session->set('cart', $cartItems);

        return new RedirectResponse('/cart');
    }

    /**
     * Update quantity by individual product in cart
     * @return RedirectResponse
     */
    public function updateAction()
    {
        $productID = $_POST['product_id'];
        $updatedQuantity = $_POST['quantity'];

        $session = $this->get('session');
        $cartItems = $session->get('cart');

        foreach($cartItems as $index => $cartItem){
            if($cartItem['product_id'] == $productID) {
                $cartItems[$index]['quantity'] = $updatedQuantity;
            }
        }

        $session->set('cart', $cartItems);

        return new RedirectResponse('/cart');
    }
}
