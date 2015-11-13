<?php

namespace MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class MercaderiaController extends Controller
{
    
    public function listarAjaxAction() {
        $mercaderias = $this->get('main_mercaderia_repositorio')->listar();

        $output = array();
        foreach ($mercaderias as $aRow) {
            $fila = array();
            foreach ($aRow as $valor) {
                $fila[] = $valor;
            }
            $output[] = $fila;
        }
        unset($mercaderias);
        return new JsonResponse($output);
    }
}
