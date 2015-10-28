<?php

namespace MainBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MainBundle\Entity\Sucursal;
use MainBundle\Form\SucursalType;

/**
 * Sucursal controller.
 *
 */
class SucursalController extends Controller
{

    /**
     * Lists all Sucursal entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('MainBundle:Sucursal')->findAll();

        return $this->render('MainBundle:Sucursal:index.html.twig', array(
            'entities' => $entities,
        ));
    }
    /**
     * Creates a new Sucursal entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity = new Sucursal();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('sucursal_show', array('id' => $entity->getId())));
        }

        return $this->render('MainBundle:Sucursal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a Sucursal entity.
     *
     * @param Sucursal $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Sucursal $entity)
    {
        $form = $this->createForm(new SucursalType(), $entity, array(
            'action' => $this->generateUrl('sucursal_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new Sucursal entity.
     *
     */
    public function newAction()
    {
        $entity = new Sucursal();
        $form   = $this->createCreateForm($entity);

        return $this->render('MainBundle:Sucursal:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Sucursal entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Sucursal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sucursal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Sucursal:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Sucursal entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Sucursal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sucursal entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('MainBundle:Sucursal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
    * Creates a form to edit a Sucursal entity.
    *
    * @param Sucursal $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(Sucursal $entity)
    {
        $form = $this->createForm(new SucursalType(), $entity, array(
            'action' => $this->generateUrl('sucursal_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing Sucursal entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('MainBundle:Sucursal')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Sucursal entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('sucursal_edit', array('id' => $id)));
        }

        return $this->render('MainBundle:Sucursal:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    /**
     * Deletes a Sucursal entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('MainBundle:Sucursal')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Sucursal entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('sucursal'));
    }

    /**
     * Creates a form to delete a Sucursal entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('sucursal_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
    
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