<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Inventario controller.
 *
 */
class InventarioController extends Controller {

    //Carga de pantalla para nuevo inventario
    public function nuevoAction() {
        $rubros = $this->get('main_rubro_repositorio')->listar();

        return $this->render('MainBundle:Inventario:nuevo.html.twig', array(
                    "rubros" => $rubros
        ));
    }

    //Permite guardar un inventario sea nuevo o uno editado
    public function guardarAction(Request $request) {
        $tipo = $request->request->get("tipo");
        $data = $request->request->get("data");
        $fecha1 = date_create_from_format('d/m/Y', $request->request->get("fecha"));
        $fecha = date_format($fecha1, "Y-m-d H:i:s");
        $idInventario = $request->request->get("inventarioId");

        if ($tipo == "nueva") {
            
           $nuevo = $this->get('main_inventario_repositorio')->nuevo($data, $fecha);
           if($nuevo == null){
               $this->get('session')->getFlashBag()->add('success', 'El inventario se ha creado correctamente!');
           }else{
               $this->get('session')->getFlashBag()->add('error', 'El inventario no pudo darse de alta, ya que el turno no existe!');
           }
        
           return new JsonResponse("no_errors");
           
        } else {
            
            $editar = $this->get('main_inventario_repositorio')->editar($data, $fecha, $idInventario);
            if ($editar == null){
                $this->get('session')->getFlashBag()->add('success', 'El inventario se ha editado correctamente!');
            }else{
                $this->get('session')->getFlashBag()->add('error', 'El inventario no pudo editarse correctamente, ya que el turno no existe!');
            }
            
            return new JsonResponse("no_errors");
        }
    }

    //Lista los inventarios cargados
    public function listarAction() {
        $inventarios = $this->get('main_inventario_repositorio')->listar();

        return $this->render('MainBundle:Inventario:listar.html.twig', array(
                    "inventarios" => $inventarios
        ));
    }

    public function editarAction($id) {
        $rubros = $this->get('main_rubro_repositorio')->listar();
        $fecha = $this->get('main_inventario_repositorio')->getFecha($id);

        return $this->render('MainBundle:Inventario:editar.html.twig', array(
                    "fecha" => $fecha,
                    "rubros" => $rubros,
                    "id" => $id
        ));
    }

    public function listarDetalleAction(Request $request) {
        $inventarioId = intval($request->request->get("inventarioId"));

        $detalles = $this->get('main_inventario_repositorio')->listarDetalle($inventarioId);

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
        $this->get('main_inventario_repositorio')->eliminar($id);

        $this->get('session')->getFlashBag()->add('success', 'El inventario se ha eliminado correctamente!');

        return new JsonResponse("no_errors");
    }

}
