<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Sucursal controller.
 *
 */
class CompraController extends Controller {

    public function listarAction() {
        $compras = $this->get('main_compra_repositorio')->listar();

        return $this->render('MainBundle:Compra:listar.html.twig', array(
                    "compras" => $compras
        ));
    }

    public function nuevaAction() {
        $proveedores = $this->get('main_proveedor_repositorio')->listar();
        $rubros = $this->get('main_rubro_repositorio')->listar();

        return $this->render('MainBundle:Compra:nueva.html.twig', array(
                    "proveedores" => $proveedores,
                    "rubros" => $rubros
        ));
    }

    public function editarAction($id) {
        $proveedores = $this->get('main_proveedor_repositorio')->listar();
        $rubros = $this->get('main_rubro_repositorio')->listar();
        $datos = $this->get('main_compra_repositorio')->listarDatos($id);

        return $this->render('MainBundle:Compra:editar.html.twig', array(
                    "proveedores" => $proveedores,
                    "rubros" => $rubros,
                    "datos" => $datos,
                    "id" => $id
        ));
    }

    public function guardarAction(Request $request) {
        $tipo = $request->request->get("tipo");
        $data = $request->request->get("data");
        $fecha1 = date_create_from_format('d/m/Y', $request->request->get("fecha"));
        $fecha = date_format($fecha1,"Y-m-d H:i:s");
        $proveedor = $request->request->get("proveedor");
        $idCompra = $request->request->get("idCompra");

        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $entity = $em->getRepository('UserBundle:User')->find($usuario);
        $idUsuario = $entity->getId();

        if ($tipo == "nueva") {
            $this->get('main_compra_repositorio')->nuevo($data, $fecha, $proveedor, $idUsuario);
        } else {
            $this->get('main_compra_repositorio')->editar($data, $fecha, $proveedor, $idCompra);
        }
        

        return $this->redirect($this->generateUrl('compra_listar'));
    }

    public function listarDetalleAction(Request $request) {
        $compraId = intval($request->request->get("compraId"));

        $detalles = $this->get('main_compra_repositorio')->listarDetalle($compraId);

        $output = array();
        foreach ($detalles as $aRow) {
            $fila = array();
            foreach ($aRow as $valor) {
                $fila[] = $valor;
            }
            $output[] = $fila;
        }
        unset($detalles);
        return new JsonResponse($output);
    }

}
