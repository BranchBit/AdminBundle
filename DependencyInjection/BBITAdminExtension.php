<?php

namespace BBIT\AdminBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class BBITAdminExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter('bbit_admin.route_prefix', $config['route_prefix']);
        $container->setParameter('bbit_admin.disable_auth', $config['disable_auth']);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');
    }

    public function prepend(ContainerBuilder $container)
    {

        // get all bundles
        $bundles = $container->getParameter('kernel.bundles');

        if (isset($bundles['KnpMenuBundle']) && isset($bundles['AsseticBundle'])) {
            $config = array('twig' => ['template' => 'BBITAdminBundle:Menu:menu.html.twig']);
            foreach ($container->getExtensions() as $name => $extension) {
                switch ($name) {
                    case 'knp_menu':
                        $container->prependExtensionConfig($name, $config);
                        break;
                    case 'assetic':
                        $container->prependExtensionConfig($name, ['filters' => ['cssrewrite' => null]]);
                        break;
                }
            }
        }
    }

}
