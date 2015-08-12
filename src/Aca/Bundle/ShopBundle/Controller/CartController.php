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

        // get the cart from session, might be empty first time around
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
                    'quantity' => $quantity);
            }
        }

        $session->set('cart', $cart);


        return new RedirectResponse('/cart');
    }

    /**
     * Show the contents of the user's shopping cart
     */
    public function showAction()
    {
        try {
            $cart = $this->get('aca.cart');
            $grandTotal = $cart->getGrandTotal();
            $userSelectedProducts = $cart->getProducts();

            return $this->render('AcaShopBundle:Cart:list.html.twig',
                array(
                    'products' => $userSelectedProducts,
                    'grandTotal' => $grandTotal)
            );
        } catch (\Exception $exception) {
            $errorMessage = $exception->getMessage();
            return $this->render('AcaShopBundle:Cart:list.html.twig',
                array('errorMessage' => $errorMessage));
        }
    }

    /**
     * Delete one item from your shopping cart
     * @throws \Exception
     * @return RedirectResponse
     */
    public function deleteAction()
    {
        $productId = $_POST['product_id'];
        $cart = $this->get('aca.cart');
        $cart->delete($productId);
        return new RedirectResponse('/cart');
    }

    /**
     * Update the quantity for one particular product in the cart
     * @return RedirectResponse
     */
    public function updateAction()
    {
        $productId = $_POST['product_id'];
        $updatedQuantity = $_POST['quantity'];

        $cart = $this->get('aca.cart');
        $cart->updateQuantity($productId, $updatedQuantity);

        return new RedirectResponse('/cart');
    }

    /**
     * Show shipping address form
     */
    public function shippingAddressAction()
    {
        /** @var Session $session */
        $session = $this->get('session');

        /** @var DBCommon $db */
        $db = $this->get('aca.db');

        /** @var int $userId Logged in user identifier */
        $userId = $session->get('user_id');

        if (empty($userId)) {

            // messaging telling them: why i am i here and what do i do
            $session->get('error_message', 'Why am I here and what am I doing?');

            return new RedirectResponse('/');
        }

        // Get the shipping_address_id and billing_address_id from the user table
        $query = '
        select
            shipping_address_id,
            billing_address_id
        from
            aca_user
        where
            user_id = ' . $userId;

        $db->setQuery($query);
        $shippingIds = $db->loadObject();

        $shippingAddressId = $shippingIds->shipping_address_id;
        $billingAddressId = $shippingIds->billing_address_id;

        // Get shipping address
        $shippingQuery = '
        select
            *
        from
            aca_address
        where
            address_id =' . $shippingAddressId;
        $db->setQuery($shippingQuery);
        $shippingAddress = $db->loadObject();

        //get billing address
        $billingQuery = '
        select
            *
        from
            aca_address
        where
            address_id =' . $billingAddressId;
        $db->setQuery($billingQuery);
        $billingAddress = $db->loadObject();

        return $this->render('AcaShopBundle:Shipping:address.html.twig',
            array(
                'shipping' => $shippingAddress,
                'billing' => $billingAddress
            )
        );
    }
}
