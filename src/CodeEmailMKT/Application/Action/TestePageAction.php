<?php

namespace CodeEmailMKT\Application\Action;

use CodeEmailMKT\Domain\Entity\Customer;
use CodeEmailMKT\Domain\Persistence\CustomerRepositoryInterface;
use Doctrine\ORM\EntityManager;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Zend\Diactoros\Response\HtmlResponse;
use Zend\Expressive\Template;
use Zend\Expressive\Plates\PlatesRenderer;
use Zend\Expressive\ZendView\ZendViewRenderer;

class TestePageAction
{
    private $template;
    /**
     * @var EntityManager
     */
    private $manager;
    /**
     * @var CustomerRepositoryInterface
     */
    private $repository;

    public function __construct(CustomerRepositoryInterface $repository, Template\TemplateRendererInterface $template = null)
    {
        $this->template = $template;
        $this->repository = $repository;
    }

    public function __invoke(ServerRequestInterface $request, ResponseInterface $response, callable $next = null)
    {
        //instancia customer
        $customer = new Customer();
        //configura entidade
        $customer->setName('Anderson')
            ->setEmail('ander-reis@hotmail.com');
        //cria o customer
        $this->repository->create($customer);
        //listar todos
        $customers = $this->repository->findAll();
        //retorna para template
        return new HtmlResponse($this->template->render('app::teste', [
            'data' => 'Minha primeira aplicação',
            'customers' => $customers,
        ]));
    }
}
