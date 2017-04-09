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

class CustomerUpdatePageAction
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

    public function __construct(
        CustomerRepositoryInterface $repository,
        TemplateRendererInterface $template = null,
        RouterInterface $router
    )
    {
        $this->repository = $repository;
        $this->template = $template;
        $this->router = $router;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        $id = $request->getAttribute('id');
        $entity = $this->repository->find($id);

        if($request->getMethod() == 'PUT'){
            $flash = $request->getAttribute('flash');
            //editar contato
            $data = $request->getParsedBody();

            $entity->setName($data['name'])
                ->setEmail($data['email']);
            $this->repository->update($entity);
            $flash->setMessage('success', 'Contato editado com sucesso');
            $uri = $this->router->generateUri('customer.list');
            return new RedirectResponse($uri);
        }
        return new HtmlResponse($this->template->render('app::customer/update',[
            'customer' => $entity,
            ]));
    }

}