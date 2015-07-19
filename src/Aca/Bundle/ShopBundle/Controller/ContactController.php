<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ContactController extends Controller
{
    public function contactAction()
    {
        return $this->render('AcaShopBundle:Contact:contact.html.twig', array(
                // ...
            ));    }

}
