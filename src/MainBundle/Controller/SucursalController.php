<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Sucursal;

/**
 * Sucursal controller.
 *
 */
class SucursalController extends Controller
{
    public function seleccionarAction(Request $request)
    {
        $sucursalId = $request->request->get('sucursal');
        
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');
        
        $usuario = $this->getUser();
        $entity = $em->getRepository('UserBundle:User')->find($usuario ->getId());
        $sucursal = $em->getRepository('MainBundle:Sucursal')->find($sucursalId);
        $entity->setSucursal($sucursal);
        
        $userManager->updateUser($entity);
        
        return $this->redirect($this->generateUrl('main_home'));
    }
}