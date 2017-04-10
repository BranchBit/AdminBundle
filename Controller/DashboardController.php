<?php
namespace BBIT\AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DashboardController extends Controller {

    public function indexAction()
    {

        return $this->render('@BBITAdmin/Default/dashboard.html.twig');
    }

}
