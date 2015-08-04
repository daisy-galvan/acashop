<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class thankYouController extends Controller
{
    public function thankYouAction()
    {
        return $this->render('AcaShopBundle:thankYou:thankYou.html.twig', array(
            // ...
        ));    }

}