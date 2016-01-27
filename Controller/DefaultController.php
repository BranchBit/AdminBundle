<?php

namespace BBIT\AdminBundle\Controller;

use AppBundle\Entity\TestPage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;


/**
 * BBITAdminController
 */
class DefaultController extends Controller
{
    public function indexAction()
    {





        return $this->render('BBITAdminBundle:admin:dashboard.html.twig');
    }
}
