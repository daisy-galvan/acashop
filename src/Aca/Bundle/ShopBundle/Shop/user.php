<?php

namespace Aca\Bundle\ShopBundle\Shop;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\Session\Session;

class user extends abstractOrder
{

    public function user()
    {
        $session = $this->get('session');

        $name = $session->get('name');
        $loggedIn = $session->get('logged_in');
        $errorMessage = $session->get('error_message');


//        $query = '
//          select
//              name
//          from
//              aca_user
//          WHERE
//              name = "' . $name . '"';
//
//        $this->db->setQuery($query);
//        $user_name = $this->db->loadObjectList();
    }
}