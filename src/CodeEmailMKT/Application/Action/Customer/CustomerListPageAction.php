<?php

namespace CodeEmailMKT\Application\Action\Customer;

use CodeEmailMKT\Domain\Persistence\CustomerRepositoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;
//use Zend\Expressive\Plates\PlatesRenderer;
//use Zend\Expressive\ZendView\ZendViewRenderer;

class CustomerListPageAction
{
    private $template;

    /**
     * @var CustomerRepositoryInterface
     */
    private $repository;

    public function __construct(CustomerRepositoryInterface $repository, Template\TemplateRendererInterface $template)
    {
        $this->template = $template;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        //listar todos
        $customers = $this->repository->findAll();
        $flash = $request->getAttribute('flash');

        //retorna para template
        return new HtmlResponse($this->template->render('app::customer/list', [
            'customers' => $customers,
            'message' => $flash->getMessage('success'),
        ]));
    }
}
