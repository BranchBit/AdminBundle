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
        $this->admins[] = [
            'admin' => $admin,
        ];
        return $this;
    }

}
