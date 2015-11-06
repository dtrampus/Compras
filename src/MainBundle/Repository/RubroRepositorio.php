<?php
namespace MainBundle\Repository;

class RubroRepositorio{
    
    private $doctrine;
    public function __construct($doctrine){
        $this->doctrine = $doctrine;
    }
    
    public function listar(){
        $query = "select id, descripcion from db_principal.blrubros r";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        return $resultado;
    }
      
}