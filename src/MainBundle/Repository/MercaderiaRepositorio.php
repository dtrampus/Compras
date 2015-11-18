<?php
namespace MainBundle\Repository;

class MercaderiaRepositorio{
    
    private $doctrine;
    public function __construct($doctrine){
        $this->doctrine = $doctrine;
    }
    
    public function listar(){
        $query = "
            SELECT '' AS `0`,CONCAT(m.codigo,' - ',m.descripcion,' - ',s.nombre) AS `1`, r.descripcion AS `2`, '' AS `3`, '' AS `4`, m.id AS `5`
            FROM db_principal.blmercaderia m
                INNER JOIN db_principal.sis_unidad_medida s 
                    ON m.sis_unidad_medida_id = s.id
                INNER JOIN db_principal.blrubros r
                    ON m.blrubros_id = r.id
        ";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        return $resultado;
    }
      
}