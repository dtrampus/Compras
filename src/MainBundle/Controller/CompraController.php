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

        return $this->render('MainBundle:Compra:editar.html.twig', array(
            "proveedores" => $proveedores,
            "rubros" => $rubros,
            "id" => $id
        ));
    }

    public function guardarAction(Request $request){
        
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
