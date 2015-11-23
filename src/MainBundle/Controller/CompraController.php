<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
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
        $total = $request->request->get("total");
        $idCompra = $request->request->get("idCompra");

        $em = $this->getDoctrine()->getManager();
        $usuario = $this->getUser();
        $entity = $em->getRepository('UserBundle:User')->find($usuario);
        $idUsuario = $entity->getId();

        if ($tipo == "nueva") {
            $nuevo = $this->get('main_compra_repositorio')->nuevo($data, $fecha, $proveedor, $total, $idUsuario);
            if($nuevo == null){
                $this->get('session')->getFlashBag()->add('success', 'La compra se ha cargado correctamente!');
            }else{
                $error = "La compra no pudo darse de alta, ya que el turno no existe!";
                return new JsonResponse($error,500);
            }
        } else {
            $editar = $this->get('main_compra_repositorio')->editar($data, $fecha, $proveedor, $total, $idCompra);
            if($editar == null){
                $this->get('session')->getFlashBag()->add('success', 'La compra se ha modificado correctamente!');
            }else{
                $error = "La compra no pudo modificarse, ya que el turno no existe!";
                return new JsonResponse($error,500);
            }
        }
        $mensaje = "";
        return new JsonResponse($mensaje,200);
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
    
    public function eliminarAction($id) {
        
        $this->get('main_compra_repositorio')->eliminar($id);  
        $this->get('session')->getFlashBag()->add('success', 'La compra se ha eliminado correctamente!');
        
        return new JsonResponse("no_errors");
    }

}
