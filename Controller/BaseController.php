<?php

namespace BBIT\AdminBundle\Controller;


use BBIT\AdminBundle\Traits\ServiceInfoTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BaseController extends Controller
{
    use ServiceInfoTrait;
}
