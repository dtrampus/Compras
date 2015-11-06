<?php

namespace MainBundle\Entity;

class CompraRepositorio {

    private $doctrine;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
    }

    public function listarMercaderia($fecha, $rubroId, $proveedorId) {
        $query = "
            SELECT cd.cantidad, CONCAT(m.codigo,' - ',m.descripcion,' - ',s.nombre) AS insumo, r.descripcion AS rubro, cd.importe/IFNULL(cd.cantidad,0) AS precio_unitario, cd.importe AS total
            FROM db_principal.blmercaderia m
                INNER JOIN db_principal.sis_unidad_medida s 
                    ON m.sis_unidad_medida_id = s.id
                INNER JOIN db_principal.blrubros r
                    ON m.blrubros_id = r.id
                LEFT JOIN blcompras_detalle cd
                    ON m.id = cd.blmercaderia_id
                LEFT JOIN blcompras c
                    ON cd.blcompras_id = c.id
                LEFT JOIN db_principal.blproveedores p
                    ON c.blproveedores_id = p.id
            WHERE IFNULL($rubroId , r.id) = r.id
            AND IFNULL(c.fecha, str_to_date('$fecha','%d/%m/%y')) = str_to_date('$fecha','%d/%m/%y')
            AND IFNULL(p.id, $proveedorId) = $proveedorId
        ";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        return $resultado;
    }

    public function listarEditarMercaderia($compraId) {
        $query = "
            SELECT ROUND(cd.cantidad,0) AS cantidad, CONCAT(m.codigo,' - ',m.descripcion,' - ',s.nombre) AS insumo, r.descripcion AS rubro, ROUND(cd.importe/IFNULL(cd.cantidad,0),2) AS precio_unitario, cd.importe AS total
            FROM db_principal.blmercaderia m
                INNER JOIN db_principal.sis_unidad_medida s 
                    ON m.sis_unidad_medida_id = s.id
                INNER JOIN db_principal.blrubros r
                    ON m.blrubros_id = r.id
                LEFT JOIN blcompras_detalle cd
                    ON m.id = cd.blmercaderia_id
                LEFT JOIN blcompras c
                    ON cd.blcompras_id = c.id
                LEFT JOIN db_principal.blproveedores p
                    ON c.blproveedores_id = p.id
            WHERE IFNULL(c.id, $compraId) = $compraId
        ";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        return $resultado;
    }

}
