<?php
namespace MainBundle\Services;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of servicioBdDinamica
 *
 * @author usuario
 */
class servicioBdDinamica {
    //put your code here
    private $containerAware;
    public function __construct($container){
        $this->containerAware = $container;
    }
    
    public function cambiarBd(){
        $servicioSeguridad = $this->containerAware->get('security.context');
        $usuarioLogeado = $servicioSeguridad->getToken()->getUser();
        if($usuarioLogeado != "anon."){
            $connectionFactory = $this->containerAware->get('doctrine.dbal.connection_factory');
            $connection = $connectionFactory->createConnection(array(
                'driver' => 'pdo_mysql',
                'user' => $this->containerAware->getParameter('database_user'),
                'password' => $this->containerAware->getParameter('database_password'),
                'host' => $this->containerAware->getParameter('database_host'),
                'dbname' => $usuarioLogeado->getSucursal()->getNombreBd(),
            ));
            $this->containerAware->set('doctrine.dbal.dinamica_connection', $connection);
        }
    }
}
