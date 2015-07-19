<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AboutController extends Controller
{
    public function aboutAction()
    {
        return $this->render('AcaShopBundle:About:about.html.twig', array(
                // ...
            ));


    }

}
