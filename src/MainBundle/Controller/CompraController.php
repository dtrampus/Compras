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

    /**
     * Lists all entities.
     *
     */
    public function nuevaCompraAction() {
        $proveedores = $this->get('main_proveedor_repositorio')->listar();

        $rubros = $this->get('main_rubro_repositorio')->listar();

        return $this->render('MainBundle:Compra:nueva.html.twig', array(
                    "proveedores" => $filasProveedores,
                    "rubros" => $filasRubros
        ));
    }
    
    public function listarAction(Request $request) {
        $rubroId = ($request->request->get("rubroId") == "" ? "NULL" : $request->request->get("rubroId"));

        $listaCompras = $this->get('main_mercaderia_repositorio')->listarMercaderia($rubroId);

        $output = array();
        foreach ($listaCompras as $aRow) {
            $fila = array();
            foreach ($aRow as $valor) {
                $fila[] = $valor;
            }
            $output[] = $fila;
        }
        unset($listaCompras);
        return new JsonResponse($output);
    }
    
    public function guardarNuevaCompraAction(){
        
    }
    
    public function listarComprasAction()
   {
       $compras = $this->getDoctrine()->getManager("dinamica")->getConnection()->prepare("SELECT c.id, c.fecha, p.nombre, c.total FROM db_sucursal1.blcompras c INNER JOIN db_principal.blproveedores p ON p.id = c.blproveedores_id");
       $compras->execute();
       $filasCompras = $compras->fetchAll();
       
       return $this->render('MainBundle:Compra:listar.html.twig',array(
           "compras" => $filasCompras
       ));
   }

}
