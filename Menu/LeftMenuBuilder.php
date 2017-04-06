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

        foreach ($admins as $admin) {
            $menu->addChild($admin['admin']->getServiceTags()['label'], array('route' => 'bbit_admin_'.$admin['admin']->getName().'_list'));
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