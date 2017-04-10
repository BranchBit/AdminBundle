<?php
namespace BBIT\AdminBundle\Routing;

use BBIT\AdminBundle\Service\AdminBuilder;
use Symfony\Component\Config\Loader\Loader;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

class AdminRouter extends Loader
{
    private $loaded = false;
    /**
     * @var AdminBuilder
     */
    private $adminBuilder;

    /**
     * AdminRouter constructor.
     */
    public function __construct(AdminBuilder $adminBuilder)
    {
        $this->adminBuilder = $adminBuilder;
    }


    public function load($resource, $type = null)
    {
        if (true === $this->loaded) {
            throw new \RuntimeException('Do not add the "extra" loader twice');
        }

        $routes = new RouteCollection();


        foreach ($this->adminBuilder->getAdmins() as $admin) {

            $path = '';
            $defaults = array(
                '_controller' => 'BBITAdminBundle:Dashboard:index',
            );
            $route = new Route($path, $defaults, []);
            $routeName = 'bbit_admin_dashboard';
            $routes->add($routeName, $route);


            foreach ($admin['admin']->getRoutes() as $routeName) {
                $requirements = [];
                $path = '/'.$admin['admin']->getName().'/'.$routeName;
                if (in_array($routeName, ['edit', 'delete'])) {
                    $path .= '/{id}';
                    $requirements = array(
                        'parameter' => '\d+',
                    );
                }
                $defaults = array(
                    '_controller' => $admin['admin']->getServiceName().':'.$routeName.'Action',
                );

                $route = new Route($path, $defaults, $requirements);

                $routeName = 'bbit_admin_'.$admin['admin']->getName().'_'.$routeName;
                $routes->add($routeName, $route);
            }
        }


        $this->loaded = true;

        return $routes;
    }

    public function supports($resource, $type = null)
    {
        return 'admin' === $type;
    }
}