<?php

namespace Aca\Bundle\ShopBundle\Shop;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\Session\Session;

/**
 * Class abstractOrder
 * @package Aca\Bundle\ShopBundle\Shop
 */
abstract class abstractOrder
{

    protected $db;
    protected $session;

    public function __construct($db, $session)
    {
        $this->db = $db;
        $this->session = $session;
    }
}