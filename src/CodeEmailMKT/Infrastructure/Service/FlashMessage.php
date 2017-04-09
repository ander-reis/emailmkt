<?php
/**
 * Created by PhpStorm.
 * User: ander
 * Date: 02/04/2017
 * Time: 15:30
 */

namespace CodeEmailMKT\Infrastructure\Service;


use Aura\Session\Segment;
use Aura\Session\Session;
use CodeEmailMKT\Domain\Service\FlashMessageInterface;

class FlashMessage implements FlashMessageInterface
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @var Segment
     */
    private $segment;


    /**
     * FlashMessage constructor.
     */
    public function __construct(Session $session)
    {
        $this->session = $session;

        if(!$this->session->isStarted()){
            $this->session->start();
        }
    }

    public function setNamespace($name = __NAMESPACE__)
    {
        $this->segment = $this->session->getSegment($name);
        return $this;
    }

    public function setMessage($key, $value)
    {
        if(!$this->segment){
            $this->setNamespace();
        }
        $this->segment->setFlash($key, $value);
        return $this;
    }

    public function getMessage($key)
    {
        if(!$this->segment){
            $this->setNamespace();
        }
        return $this->segment->getFlash($key);
    }
}