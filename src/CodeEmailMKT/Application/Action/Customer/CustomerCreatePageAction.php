<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 02/04/2017
 * Time: 11:00
 */

namespace CodeEmailMKT\Application\Action\Customer;


use CodeEmailMKT\Domain\Entity\Customer;
use CodeEmailMKT\Domain\Persistence\CustomerRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Diactoros\Response\RedirectResponse;
use Zend\Expressive\Router\RouterInterface;
use Zend\Expressive\Template\TemplateRendererInterface;
use Zend\Form\Form;
use Zend\View\HelperPluginManager;

class CustomerCreatePageAction
{

    /**
     * @var CustomerRepositoryInterface
     */
    private $repository;
    /**
     * @var TemplateRendererInterface
     */
    private $template;
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var HelperPluginManager
     */
    //private $helperManager;

    public function __construct(
        CustomerRepositoryInterface $repository,
        TemplateRendererInterface $template,
        RouterInterface $router
    )
    {
        $this->repository = $repository;
        $this->template = $template;
        $this->router = $router;
    }

    //teste com helper manager
//    public function __construct(
//        CustomerRepositoryInterface $repository,
//        TemplateRendererInterface $template,
//        RouterInterface $router,
//        HelperPluginManager $helperManager
//    )
//    {
//        $this->repository = $repository;
//        $this->template = $template;
//        $this->router = $router;
//        $this->helperManager = $helperManager;
//    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        //teste rederer form
        $myForm = new Form();
        $myForm->add([
            'name' => 'name',
            'type' => 'Text',
            'options' => [
                'label' => 'Name:'
            ]
        ]);
        $myForm->add([
            'name' => 'email',
            'type' => 'Text',
            'options' => [
                'label' => 'Email:'
            ]
        ]);
        $myForm->add([
            'name' => 'submit',
            'type' => 'Submit',
            'attributes' => [
                'value' => 'Enviar',
            ],
            'options' => [
                'label' => 'Submit'
            ]
        ]);
        //$formHelper = $this->helperManager->get('form');
        //FIM teste rederer form

        if($request->getMethod() == 'POST'){
            $flash = $request->getAttribute('flash');

            //cadastrar contato
            $data = $request->getParsedBody();
            $entity = new Customer();
            $entity->setName($data['name'])
                ->setEmail($data['email']);
            $this->repository->create($entity);

            $flash->setMessage('success', 'Contato cadastrado com sucesso');
            $uri = $this->router->generateUri('customer.list');
            return new RedirectResponse($uri);
        }
        return new HtmlResponse($this->template->render('app::customer/create', [
            'myForm' => $myForm,
        ]));
        //teste
//        return new HtmlResponse($this->template->render('app::customer/create', [
//            'myForm' => $myForm,
//            'formHelper' => $formHelper,
//        ]));
    }

}