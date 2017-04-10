<?php

namespace BBIT\AdminBundle\Service;

class AdminBuilder
{

    protected $admins = [];

    /**
     * @return array
     */
    public function getAdmins()
    {
        return $this->admins;
    }

    public function addAdmin($admin)
    {
        $this->admins[] = $admin;
        return $this;
    }

    protected $controllers = [];

    /**
     * @return array
     */
    public function getControllers()
    {
        return $this->controllers;
    }

    public function addController($controller)
    {
        $this->controllers[] = $controller;
        return $this;
    }

}
