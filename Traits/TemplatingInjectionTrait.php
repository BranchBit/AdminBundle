<?php

namespace BBIT\AdminBundle\Traits;

trait TemplatingInjectionTrait {

    public $templating;

    /**
     * @param mixed $templating
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
    }

}