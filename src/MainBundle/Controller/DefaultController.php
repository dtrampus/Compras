<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('MainBundle:Default:index.html.twig');
    }
    
    public function sucursalesAction(){
        $em = $this->getDoctrine()->getManager();
        $sucursales = $em->getRepository('MainBundle:Sucursal')->findAll();
       
        return $this->render('MainBundle:Default:sucursales.html.twig', array(
                    'sucursales' => $sucursales
        ));
    }
}
