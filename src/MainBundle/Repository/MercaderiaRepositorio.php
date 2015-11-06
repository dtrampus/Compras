<?php
namespace MainBundle\Repository;

class MercaderiaRepositorio{
    
    private $doctrine;
    public function __construct($doctrine){
        $this->doctrine = $doctrine;
    }
    
    public function listarMercaderia($rubroId){
        $query = "
            SELECT '' ,CONCAT(m.codigo,' - ',m.descripcion,' - ',s.nombre) AS insumo, r.descripcion AS rubro, '', ''
            FROM db_principal.blmercaderia m
                INNER JOIN db_principal.sis_unidad_medida s 
                    ON m.sis_unidad_medida_id = s.id
                INNER JOIN db_principal.blrubros r
                    ON m.blrubros_id = r.id
            WHERE IFNULL($rubroId , r.id) = r.id
        ";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        return $resultado;
    }
      
}