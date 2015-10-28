<?php

namespace MainBundle\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class RequestListener implements EventSubscriberInterface {

    private $servicioCambioBD;
    public function __construct($servicioCambioBD){
        $this->servicioCambioBD = $servicioCambioBD;
    }

//    public function onKernelRequest(GetResponseEvent $event) {
//        $this->servicioCambioBD->cambiarBd();
//    }
    
    public function onKernelController(FilterControllerEvent $event)
    {
        $controller = $event->getController();
        // ...
        $this->servicioCambioBD->cambiarBd();
        // the controller can be changed to any PHP callable
        $event->setController($controller);
    }

    public static function getSubscribedEvents() {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::CONTROLLER => array(array('onKernelController', 17)),
            
        );
    }

}
