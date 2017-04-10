<?php
// src/AppBundle/Menu/Builder.php
namespace BBIT\AdminBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class LeftMenuBuilder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function menu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $admins = $this->container->get('admin_builder')->getAdmins();
        $controllers = $this->container->get('admin_builder')->getControllers();

        foreach ($admins as $admin) {
            $menu->addChild($admin->getServiceTags()['label'], array('route' => 'bbit_admin_'.$admin->getName().'_list'));
            if (array_key_exists('icon', $admin->getServiceTags())) {
                $menu[$admin->getServiceTags()['label']]->setAttribute('icon', $admin->getServiceTags()['icon']);
            }
        }

        foreach ($controllers as $controller) {
            $menu->addChild($controller->getServiceTags()['label'], array('test' => 'test', 'route' => 'bbit_admin_'.$controller->getServiceTags()['route_name']));
            if (array_key_exists('icon', $controller->getServiceTags())) {
                $menu[$controller->getServiceTags()['label']]->setAttribute('icon', $controller->getServiceTags()['icon']);
            }
        }



//        // access services from the container!
//        $em = $this->container->get('doctrine')->getManager();
//        // findMostRecent and Blog are just imaginary examples
//        $blog = $em->getRepository('AppBundle:Blog')->findMostRecent();
//
//        $menu->addChild('Latest Blog Post', array(
//            'route' => 'blog_show',
//            'routeParameters' => array('id' => $blog->getId())
//        ));
//
        // create another menu item
//        $menu->addChild('About Me', array('route' => 'bbit_admin_homepage'));
//        // you can also add sub level's to your menu's as follows
//        $menu['About Me']->addChild('Edit profile', array('route' => 'bbit_admin_homepage'));
//
//        // ... add more children

        return $menu;
    }
}