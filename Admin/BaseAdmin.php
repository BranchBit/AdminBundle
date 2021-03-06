<?php

namespace BBIT\AdminBundle\Admin;

use BBIT\AdminBundle\Traits\RouterInjectionTrait;
use BBIT\AdminBundle\Traits\ServiceInfoTrait;
use BBIT\AdminBundle\Traits\TemplatingInjectionTrait;
use BBIT\DataGridBundle\Service\DataGridService;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

class BaseAdmin
{

    use ServiceInfoTrait;
    use TemplatingInjectionTrait;
    use RouterInjectionTrait;

    protected $entityClass;
    protected $name;

    protected $routePrefix;
    protected $authDisabled;


    protected $doctrine;
    protected $datagrid;

    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authChecker;

    /**
     * @var FormBuilder
     */
    protected $formfactory;

    public $context;

    protected $routeName = null;

    protected $routes = ['list', 'edit', 'add', 'delete'];

    /**
     * @return array
     */
    public function getRoutes()
    {
        return $this->routes;
    }

    /**
     * @param mixed $authDisabled
     */
    public function setAuthDisabled($authDisabled)
    {
        $this->authDisabled = $authDisabled;
    }


    /**
     * BaseAdmin constructor.
     * @param $entityClass
     */
    public function __construct($entityClass)
    {
        $this->entityClass = $entityClass;
    }

    /**
     * @param AuthorizationCheckerInterface $authChecker
     */
    public function setAuthChecker($authChecker)
    {
        $this->authChecker = $authChecker;
    }

    /**
     * @param mixed $routePrefix
     */
    public function setRoutePrefix($routePrefix)
    {
        $this->routePrefix = $routePrefix;
    }

    /**
     * @param mixed $formfactory
     */
    public function setFormfactory($formfactory)
    {
        $this->formfactory = $formfactory;
    }

    /**
     * @param mixed $datagrid
     */
    public function setDatagrid($datagrid)
    {
        $this->datagrid = $datagrid;
    }

    /**
     * @param mixed $doctrine
     */
    public function setDoctrine($doctrine)
    {
        $this->doctrine = $doctrine;
    }

    public function setupName()
    {
        if ($this->routeName !== null) {
            $this->name = $this->routeName;
        } else {
            $this->name = strtolower(substr($this->entityClass, strrpos($this->entityClass, '\\') + 1));
        }
    }

    public function isGranted($attributes, $object = null)
    {
        if ($this->authDisabled) {return true;}
        return $this->authChecker->isGranted($attributes, $object);
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    protected function mapListFields(DataGridService $grid) {}
    protected function mapFormFields(FormBuilder $formBuilder) {}

    protected function getRepo() {
        return $this->doctrine->getRepository($this->entityClass);
    }

    protected function createQueryBuilder() {
        return $this->getRepo()->createQueryBuilder('x');
    }

    protected function listQueryBuilder() {
        return $this->createQueryBuilder();
    }









    public function listAction()
    {
        if (!$this->isGranted('view', $this->entityClass)) {
            throw new AccessDeniedException();
        }

        $this->context = 'list';



        $qb = $this->listQueryBuilder();


        $grid = $this->datagrid;
        $grid->setExtradata(['admin' => $this]);
        $grid->setQb($qb);
        $grid->setItemsPerPage(10);

        $this->mapListFields($grid);

        //TODO: only if actions available
        $grid->addField('actions', 'custom_template', [
            'template' => 'BBITAdminBundle:Default:actions.html.twig'
        ]);



        //exit(var_dump($this->getName()));
        return new Response($this->templating->render('BBITAdminBundle:Default:list.html.twig', ['admin' => $this, 'grid' => $grid->render()]));
    }

    public function editAction(Request $request, $id)
    {
        if (!$this->isGranted('edit', $this->entityClass)) {
            throw new AccessDeniedException();
        }

        $this->context = 'edit';
        $item = $this->getRepo()->find($id);

        if (method_exists($this, 'preSaveAction')) {
            $item = $this->preSaveAction($item);
        }


        $formBuilder = $this->formfactory->createBuilder(FormType::class, $item);
        $this->mapFormFields($formBuilder);

        $form = $formBuilder
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($this, 'postSaveAction')) {
                $item = $this->postSaveAction($item);
            }

            $this->doctrine->getManager()->flush();
            //return new RedirectResponse($this->router->generate('bbit_admin_'.$this->getName().'_'.$this->context, ['id' => $id]));
        }


        return new Response($this->templating->render('BBITAdminBundle:Default:edit.html.twig', ['admin' => $this, 'item' => $item, 'form' => $form->createView()]));
    }

    public function addAction(Request $request)
    {
        if (!$this->isGranted('create', $this->entityClass)) {
            throw new AccessDeniedException();
        }

        $this->context = 'add';
        $item = new $this->entityClass;

        $formBuilder = $this->formfactory->createBuilder(FormType::class, $item);
        $this->mapFormFields($formBuilder);

        $form = $formBuilder
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if (method_exists($this, 'postSaveAction')) {
                $item = $this->postSaveAction($item);
            }

            $this->doctrine->getManager()->persist($item);
            $this->doctrine->getManager()->flush();
            return new RedirectResponse($this->router->generate('bbit_admin_'.$this->getName().'_list'));
        }


        return new Response($this->templating->render('BBITAdminBundle:Default:add.html.twig', ['admin' => $this, 'item' => $item, 'form' => $form->createView()]));
    }

    public function deleteAction($id)
    {
        if (!$this->isGranted('delete', $this->entityClass)) {
            throw new AccessDeniedException();
        }

        $this->context = 'delete';
        $item = $this->getRepo()->find($id);
        $this->doctrine->getManager()->remove($item);
        $this->doctrine->getManager()->flush();

        return new RedirectResponse($this->router->generate('bbit_admin_'.$this->getName().'_list'));
    }

}
