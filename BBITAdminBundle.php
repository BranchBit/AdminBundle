<?php

namespace BBIT\AdminBundle;

use BBIT\AdminBundle\DependencyInjection\Compiler\AdminCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class BBITAdminBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        $container->addCompilerPass(new AdminCompilerPass());
    }
}
