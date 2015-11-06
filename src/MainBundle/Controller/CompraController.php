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

    public function listarComprasAction()
    {
        $compras = $this->getDoctrine()->getManager("dinamica")->getConnection()->prepare("SELECT c.id, c.fecha, p.nombre, c.total FROM db_sucursal1.blcompras c INNER JOIN db_principal.blproveedores p ON p.id = c.blproveedores_id");
        $compras->execute();
        $filasCompras = $compras->fetchAll();
        
        return $this->render('MainBundle:Compra:listar.html.twig',array(
            "compras" => $filasCompras
        ));
    }
    
        public function editarAction($id)
    {
        $proveedores = $this->getDoctrine()->getManager("dinamica")->getConnection()->prepare("select id, nombre from db_principal.blproveedores p");
        $proveedores->execute();
        $filasProveedores = $proveedores->fetchAll();
        
        $rubros = $this->getDoctrine()->getManager("dinamica")->getConnection()->prepare("select id, descripcion from db_principal.blrubros r");
        $rubros->execute();
        $filasRubros = $rubros->fetchAll();
        
//        $compra = $this->getDoctrine()->getManager("dinamica")->getConnection()->prepare("SELECT id FROM db_sucursal1.blcompras c WHERE id = $id");
//        $compra->execute();
//        $filasCompra = $compra->fetch();
        
        return $this->render('MainBundle:Compra:editar.html.twig',array(
            "proveedores" => $filasProveedores,
            "rubros" => $filasRubros,
            "id" => $id
        ));
    }
    
        public function listarEditarAction(Request $request)
    {
        $compraId = intval($request->request->get("compraId")); 
            
        $listarEditarCompras = $this->get('main_compra_repositorio')->listarEditarMercaderia($compraId);
        
        $output = array();
        foreach ($listarEditarCompras as $aRow) {
            $fila = array();
            foreach ($aRow as $valor) {
                $fila[] = $valor;
            }
            $output[] = $fila;
        }
        unset($listarEditarCompras);
        return new JsonResponse($output);
    }
}
