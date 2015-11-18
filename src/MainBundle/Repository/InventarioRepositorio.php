<?php

namespace MainBundle\Repository;

class InventarioRepositorio {

    private $doctrine;

    public function __construct($doctrine) {
        $this->doctrine = $doctrine;
    }

    public function nuevo($data, $fecha) {
        $conn = $this->doctrine->getEntityManager("dinamica")->getConnection();
        $conn->beginTransaction();
        try {

            $query = "SELECT id FROM blturnos WHERE date(fecha_apertura)=STR_TO_DATE('$fecha','%Y-%m-%d')";

            $consulta = $conn->prepare($query);
            $consulta->execute();
            $resultado = $consulta->fetch();
            $idTurno = $resultado['id'];

            if ($idTurno == null) {
                $mensaje = "0";
                return $mensaje;
            } else {
                $query = "
                        INSERT INTO blinventario (fecha_apertura, blturnos_id)
                            VALUES ('$fecha','$idTurno')
                    ";

                $consulta = $conn->prepare($query);
                $consulta->execute();
                $idInventario = $conn->lastInsertId();

                foreach ($data as $columna) {
                    $conteo = $columna[0];
                    $mercaderia = $columna[1];

                    $query2 = "
                        INSERT INTO blinventario_detalle (blinventario_id, blmercaderia_id, conteo)
                            VALUES ($idInventario, $mercaderia, $conteo)
                    ";

                    $consulta = $conn->prepare($query2);
                    $consulta->execute();
                }
                $conn->commit();
            }
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

    public function listar() {
        $inventarios = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare("SELECT i.id, i.fecha_apertura FROM blinventario i");
        $inventarios->execute();
        $resultado = $inventarios->fetchAll();
        return $resultado;
    }

    public function getFecha($id) {
        $query = "SELECT i.fecha_apertura as fecha from blinventario i where id= $id";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetch();
        return $resultado;
    }

    public function listarDetalle($inventarioId) {
        $query = "
            SELECT 
		ifnull(detalle.conteo, '') AS `0`,
		CONCAT(m.codigo, ' - ', m.descripcion, ' - ', s.nombre) AS `1`, 
		r.descripcion AS `2`,
		m.id AS `3`
            FROM db_principal.blmercaderia  m
            INNER JOIN db_principal.sis_unidad_medida s 
                    ON m.sis_unidad_medida_id = s.id
            INNER JOIN db_principal.blrubros r
                    ON m.blrubros_id = r.id
            LEFT JOIN 
            (
                SELECT invd.blmercaderia_id blmercaderia_id, SUM(conteo) conteo
                FROM blinventario_detalle invd
                    LEFT JOIN blinventario i
                            ON invd.blinventario_id = i.id
                            where invd.blinventario_id = i.id and i.id = $inventarioId
                            GROUP BY blmercaderia_id
            ) AS detalle
            ON detalle.blmercaderia_id = m.id
        ";
        $consulta = $this->doctrine->getEntityManager("dinamica")->getConnection()->prepare($query);
        $consulta->execute();
        $resultado = $consulta->fetchAll();
        return $resultado;
    }

    public function editar($data, $fecha, $idInventario) {
        $conn = $this->doctrine->getEntityManager("dinamica")->getConnection();
        $conn->beginTransaction();
        try {
            $query = "SELECT id FROM blturnos WHERE date(fecha_apertura)=STR_TO_DATE('$fecha','%Y-%m-%d')";

            $consulta = $conn->prepare($query);
            $consulta->execute();
            $resultado = $consulta->fetch();
            $idTurno = $resultado['id'];

            if ($idTurno == null) {
                $mensaje = "0";
                return $mensaje;
            } else {
                $query = "
               DELETE FROM blinventario_detalle WHERE blinventario_id = $idInventario
               ";

                $consulta = $conn->prepare($query);
                $consulta->execute();

                $query2 = "
               UPDATE blinventario
               SET fecha_apertura = '$fecha'
               WHERE id = $idInventario
               ";

                $consulta = $conn->prepare($query2);
                $consulta->execute();

                foreach ($data as $columna) {
                    $conteo = $columna[0];
                    $mercaderia = $columna[1];

                    $query3 = "
                       INSERT INTO blinventario_detalle (blinventario_id, blmercaderia_id, conteo)
                            VALUES ($idInventario, $mercaderia, $conteo)
                   ";

                    $consulta = $conn->prepare($query3);
                    $consulta->execute();
                }
                $conn->commit();
            }
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

    public function eliminar($id) {
        $conn = $this->doctrine->getEntityManager("dinamica")->getConnection();
        $conn->beginTransaction();
        try {
            $query = "
                DELETE i,invd FROM blinventario i
                JOIN blinventario_detalle invd
                WHERE i.id = $id
                AND invd.blinventario_id = $id
                ";

            $consulta = $conn->prepare($query);
            $consulta->execute();

            $conn->commit();
        } catch (Exception $e) {
            $conn->rollback();
            throw $e;
        }
    }

}
