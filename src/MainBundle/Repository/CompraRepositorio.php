<?php

namespace MainBundle\Repository;

class CompraRepositorio {

    private $doctrine;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
    }

    public function listar() {
        $compras = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare("SELECT c.id, c.fecha, p.nombre, c.total FROM db_sucursal1.blcompras c INNER JOIN db_principal.blproveedores p ON p.id = c.blproveedores_id");
        $compras->execute();
        $resultado = $compras->fetchAll();
        return $resultado;
    }

//    public function listarMercaderia($fecha, $rubroId, $proveedorId) {
//        $query = "
//            SELECT cd.cantidad, CONCAT(m.codigo,' - ',m.descripcion,' - ',s.nombre) AS insumo, r.descripcion AS rubro, cd.importe/IFNULL(cd.cantidad,0) AS precio_unitario, cd.importe AS total
//            FROM db_principal.blmercaderia m
//                INNER JOIN db_principal.sis_unidad_medida s 
//                    ON m.sis_unidad_medida_id = s.id
//                INNER JOIN db_principal.blrubros r
//                    ON m.blrubros_id = r.id
//                LEFT JOIN blcompras_detalle cd
//                    ON m.id = cd.blmercaderia_id
//                LEFT JOIN blcompras c
//                    ON cd.blcompras_id = c.id
//                LEFT JOIN db_principal.blproveedores p
//                    ON c.blproveedores_id = p.id
//            WHERE IFNULL($rubroId , r.id) = r.id
//            AND IFNULL(c.fecha, str_to_date('$fecha','%d/%m/%y')) = str_to_date('$fecha','%d/%m/%y')
//            AND IFNULL(p.id, $proveedorId) = $proveedorId
//        ";
//        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
//        $consulta->execute();
//        $resultado = $consulta->fetchAll();
//        return $resultado;
//    }

    public function listarDetalle($compraId) {
        $query = "
            SELECT 
		ifnull(detalle.cantidad, '') AS `0`,
		CONCAT(m.codigo, ' - ', m.descripcion, ' - ', s.nombre) AS `1`, 
		r.descripcion AS `2`, 
		ifnull(ROUND(detalle.importe/IFNULL(detalle.cantidad,0),2), '') AS `3`, 
		ifnull(detalle.importe, '') AS `4`,
		m.id AS `5`
            FROM db_principal.blmercaderia  m
            INNER JOIN db_principal.sis_unidad_medida s 
                    ON m.sis_unidad_medida_id = s.id
            INNER JOIN db_principal.blrubros r
                    ON m.blrubros_id = r.id
            LEFT JOIN 
            (
                SELECT cd.blmercaderia_id blmercaderia_id, SUM(cantidad) cantidad, cd.importe importe
                FROM db_sucursal1.blcompras_detalle cd
                    LEFT JOIN db_sucursal1.blcompras c
                            ON cd.blcompras_id = c.id
                            where cd.blcompras_id = c.id and c.id = $compraId
                            GROUP BY blmercaderia_id
            ) AS detalle
            ON detalle.blmercaderia_id = m.id
        ";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        return $resultado;
    }

    public function listarDatos($id) {
        $query = "SELECT fecha,blproveedores_id AS proveedor FROM blcompras WHERE id = $id";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetch();
        return $resultado;
    }
    
    public function nuevo($data, $fecha, $proveedor, $idUsuario) {
        $conn = $this->doctrine->getEntityManager("dinamica")->getConnection();
        $conn->beginTransaction();
        $total = "5000";
        try {
            $query = "
                        INSERT INTO blcompras (fecha, total, blproveedores_id, users_id)
                            VALUES ('$fecha', $total, $proveedor, $idUsuario)
                    ";

            $consulta = $conn->prepare($query);
            $consulta->execute();
            $idCompra = $conn->lastInsertId();

//            $conn->commit();

            foreach ($data as $columna) {
                $cantidad = $columna[0];
                $mercaderia = $columna[1];
                $importeUnitario = $columna[2];

                $query2 = "
                        INSERT INTO blcompras_detalle (cantidad, importe, blcompras_id, blmercaderia_id)
                            VALUES ($cantidad, $importeUnitario, $idCompra, $mercaderia)
                    ";

                $consulta = $conn->prepare($query2);
                $consulta->execute();
            }
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

    public function editar($data, $fecha, $proveedor, $idCompra) {
        $conn = $this->doctrine->getEntityManager("dinamica")->getConnection();
        $conn->beginTransaction();
        try {
            $query = "
                DELETE FROM blcompras_detalle WHERE blcompras_id = $idCompra
                ";

            $consulta = $conn->prepare($query);
            $consulta->execute();

            $query2 = "
                UPDATE blcompras
                SET fecha = '$fecha' , blproveedores_id = $proveedor
                WHERE id = $idCompra
                ";

            $consulta = $conn->prepare($query2);
            $consulta->execute();

            foreach ($data as $columna) {
                $cantidad = $columna[0];
                $mercaderia = $columna[1];
                $importeUnitario = $columna[2];

                $query3 = "
                        INSERT INTO blcompras_detalle (cantidad, importe, blcompras_id, blmercaderia_id)
                            VALUES ($cantidad, $importeUnitario, $idCompra, $mercaderia)
                    ";

                $consulta = $conn->prepare($query3);
                $consulta->execute();
            }
            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

}
