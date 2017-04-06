<?php
namespace BBIT\AdminBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

class AdminCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        // always first check if the primary service is defined
        if (!$container->has('admin_builder')) {
            return;
        }

        $definition = $container->findDefinition('admin_builder');

        $taggedServices = $container->findTaggedServiceIds('bbit.admin');

        foreach ($taggedServices as $id => $tags) {

            $adminDefinition = $container->findDefinition($id);
            $adminDefinition->addMethodCall('setTemplating', [new Reference('templating')]);
            $adminDefinition->addMethodCall('setDoctrine', [new Reference('doctrine')]);
            $adminDefinition->addMethodCall('setDatagrid', [new Reference('bbit_data_grid')]);
            $adminDefinition->addMethodCall('setFormfactory', [new Reference('form.factory')]);
            $adminDefinition->addMethodCall('setRoutePrefix', [$container->getParameter('bbit_admin.route_prefix')]);
            $adminDefinition->addMethodCall('setRouter', [new Reference('router')]);
            $adminDefinition->addMethodCall('setServiceName', [$id]);
            $adminDefinition->addMethodCall('setServiceTags', [$tags[0]]);

            $adminDefinition->addMethodCall('setupName', []);


            $definition->addMethodCall('addAdmin', array(new Reference($id)));

        }



    }
}