<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Sucursal controller.
 *
 */
class CompraController extends Controller
{
    
    /**
     * Lists all Sucursal entities.
     *
     */
    public function indexAction()
    {
        $proveedores = $this->getDoctrine()->getManager("dinamica")->getConnection()->prepare("select id, nombre from db_principal.blproveedores p");
        $proveedores->execute();
        $filasProveedores = $proveedores->fetchAll();
        
        $rubros = $this->getDoctrine()->getManager("dinamica")->getConnection()->prepare("select id, descripcion from db_principal.blrubros r");
        $rubros->execute();
        $filasRubros = $rubros->fetchAll();
        
        return $this->render('MainBundle:Compra:index.html.twig',array(
            "proveedores" => $filasProveedores,
            "rubros" => $filasRubros
        ));
    }
    
    public function listarAction(Request $request){
        $fecha = $request->request->get("fecha");
        $proveedorId = ($request->request->get("proveedorId") == "" ? 0:$request->request->get("proveedorId"));
        $rubroId = ($request->request->get("rubroId") == "" ? "NULL":$request->request->get("rubroId"));
        
        $listaCompras = $this->get('main_compra_repositorio')->listarMercaderia($fecha,$rubroId,$proveedorId);
        
        $output = array(
            "data" => array()
        );
        foreach ($listaCompras as $aRow) {
            $output['data'][] = $aRow;
        }
        unset($listaCompras);
        return new JsonResponse($output);
    }
}
