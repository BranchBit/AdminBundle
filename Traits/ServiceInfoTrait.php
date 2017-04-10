<?php

namespace BBIT\AdminBundle\Traits;

trait ServiceInfoTrait {

    protected $serviceName;
    protected $serviceTags;

    /**
     * @return mixed
     */
    public function getServiceTags()
    {
        return $this->serviceTags;
    }

    /**
     * @param mixed $serviceTags
     */
    public function setServiceTags($serviceTags)
    {
        $this->serviceTags = $serviceTags;
    }

    /**
     * @param mixed $serviceName
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return mixed
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

}