<?php

namespace UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use UserBundle\Entity\User;
use UserBundle\Form\Type\UserType;

/**
 * User controller.
 *
 */
class UserController extends Controller
{

    /**
     * Lists all User entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('UserBundle:User')->findAll();

        return $this->render('UserBundle:User:list.html.twig', array(
            'entities' => $entities,
        ));
    }
    
    /**
     * Creates a new User entity.
     *
     */
    public function newAction(Request $request)
    {
        $userManager = $this->get('fos_user.user_manager');
        $entity = new User();
        $entity->setEnabled(true);
        
        $form = $this->createNewForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $userManager->updateUser($entity);
            
            $this->addFlash(
                'success',
                'El usuario se ha creado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('user_list'));
        }

        return $this->render('UserBundle:User:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a form to create a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createNewForm(User $entity)
    {
        $form = $this->createForm(new UserType(), $entity, array(
            'action' => $this->generateUrl('user_new'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Crear', 'attr' => array('class' => 'btn-primary')));

        return $form;
    }

    /**
     * Finds and displays a User entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('UserBundle:User:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Edits an existing User entity.
     *
     */
    public function editAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();
        $userManager = $this->get('fos_user.user_manager');

        $entity = $em->getRepository('UserBundle:User')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find User entity.');
        }
        
        $originalPassword = $entity->getPassword();
        
        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $newPassword = $editForm->get('plainPassword')->getData();
            if (empty($newPassword))  {  
                $entity->setPassword($originalPassword);
            }
            
            $userManager->updateUser($entity);
            
            $this->addFlash(
                'success',
                'El usuario se ha editado correctamente.'
            );
            
            return $this->redirect($this->generateUrl('user_list'));
        }

        return $this->render('UserBundle:User:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }
    
    /**
     * Creates a form to edit a User entity.
     *
     * @param User $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm(User $entity)
    {
        $form = $this->createFormBuilder($entity)
            ->setAction($this->generateUrl('user_edit', array('id' => $entity->getId())))
            ->setMethod('PUT')
            ->add('email', 'email', array('label' => 'Email:'))
            ->add('username', 'text', array('label' => 'Nombre de usuario:'))
            ->add('firstname', 'text', array('label' => 'Nombre:'))
            ->add('lastname', 'text', array('label' => 'Apellido:'))
            ->add('plainPassword', 'password', array(
                'label' => 'Contraseña:',
                'required' => false
            ))
            ->add('enabled')
            ->add('groups', null, array(
                'label' => 'Grupos:',
                'property' => 'name',
                'expanded' => true,
                'multiple' => true,)
            )
            ->add('submit', 'submit', array('label' => 'Grabar', 'attr' => array('class' => 'btn-primary')))
            ->getForm();

        return $form;
    }
    
    /**
     * Deletes a User entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('UserBundle:User')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find User entity.');
            }

            $em->remove($entity);
            $em->flush();
            
            $this->addFlash(
                'success',
                'El usuario se ha eliminado correctamente.'
            );
        }

        return $this->redirect($this->generateUrl('user_list'));
    }

    /**
     * Creates a form to delete a User entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $id)))
            ->setMethod('DELETE')
//            ->add('submit', 'submit', array('label' => 'Eliminar', 'attr' => array('class' => 'red')))
            ->getForm()
        ;
    }
    
}
