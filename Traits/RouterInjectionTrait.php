<?php

namespace BBIT\AdminBundle\Traits;

trait RouterInjectionTrait {

    protected $router;
    /**
     * @param mixed $router
     */
    public function setRouter($router)
    {
        $this->router = $router;
    }

}