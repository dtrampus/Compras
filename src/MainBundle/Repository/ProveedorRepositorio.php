<?php
namespace MainBundle\Repository;

class ProveedorRepositorio{
    
    private $doctrine;
    public function __construct($doctrine){
        $this->doctrine = $doctrine;
    }
    
    public function listar(){
        $query = "select id, nombre from db_principal.blproveedores p";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        return $resultado;
    }
      
}