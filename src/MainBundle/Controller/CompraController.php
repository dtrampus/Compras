<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Sucursal controller.
 *
 */
class CompraController extends Controller
{
    
    /**
     * Lists all Sucursal entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Sucursal')->findAll();

        return $this->render('MainBundle:Compra:index.html.twig', array(
            'entities' => $entities,
        ));
    }
}
