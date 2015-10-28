<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
//        $connectionFactory = $this->container->get('doctrine.dbal.connection_factory');
//        $connection = $connectionFactory->createConnection(array(
//            'driver' => 'pdo_mysql',
//            'user' => 'root',
//            'password' => '123456',
//            'host' => 'localhost',
//            'dbname' => 'db_sucursal1',
//        ));
//       
//       
//       $stmt = $connection->prepare("select * from db_principal.blmercaderia, blcompras;");
//       $stmt->execute();
//       $rResultFilteres = $stmt->fetch();
       
        $stmt = $this->getDoctrine()->getManager("dinamica")->getConnection()->prepare("select * from db_principal.blmercaderia, blcompras;");
        $stmt->execute();
        $rResultFilteres = $stmt->fetch();
        return $this->render('MainBundle:Default:index.html.twig',array(
            "resultado" => $rResultFilteres
        ));
    }
    
    public function sucursalesAction(){
        $em = $this->getDoctrine()->getManager();
        $sucursales = $em->getRepository('MainBundle:Sucursal')->findAll();
       
        return $this->render('MainBundle:Default:sucursales.html.twig', array(
                    'sucursales' => $sucursales
        ));
    }
}
