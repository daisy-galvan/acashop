<?php

namespace Aca\Bundle\ShopBundle\Controller;

use Aca\Bundle\ShopBundle\Db\DBCommon;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller
{
    public function indexAction()
    {
        $session = $this->get('session');

        $name = $session->get('name');
        $loggedIn = $session->get('logged_in');
        $errorMessage = $session->get('error_message');

//        $loggedIn = isset($_SESSION['logged_in']) ? $_SESSION['logged_in'] : 0;
//        $name = isset($_SESSION['name']) ? $_SESSION['name'] : null;

        return $this->render(
            'AcaShopBundle:Home:index.html.twig',
            array(
                'loggedIn' => $loggedIn,
                'name' => $name,
                'errorMessage' => $errorMessage
            )
        );
    }

    public function loginAction()
    {
        $session = $this->get('session');

        // acquire user input
        $username = $_POST['username'];
        $password = $_POST['password'];

        //check username and pw
        $query = 'select * from aca_user where username="' . $username . '" and password="' . $password . '"';

        //$db = new DBCommon();
        //replacing this with line 47, which uses the Service layer

        $db = $this->get('aca.db');
        $db->setQuery($query);
        $user = $db->loadObject();  //fetches one row from db

        if (empty($user)) {
            //if user is not good, set logged_in=0 in session
            //also set an error
            $session->set('logged_in', 0);
            $session->set('error message', 'Login failed!');

        } else {
            //if user is good, then set logged_in=1 in session
            $session->set('logged_in', 1);
            $session->set('name', $user->name);

        }
        return new RedirectResponse('/');
    }

    public function logoutAction()
    {
        $session = $this->get('session');
        $session->set('logged_in', 0); // $session->clear(); is another way to accomplish this

        return new RedirectResponse('/');
    }
}
