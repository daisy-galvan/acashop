<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class addCartController extends Controller
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
                    $existingItem= true;

                    // add to existing quantity
                    $cartItem['quantity'] += $quantity;
                }
            }

            // if brand new item
            if ($existingItem == false) {
                $cart[]= array(
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
    public function  showAction(){

    }

    public function deleteAction(){

    }
}
