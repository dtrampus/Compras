<?php

/*
 * This file is part of the FOSUserBundle package.
 *
 * (c) FriendsOfSymfony <http://friendsofsymfony.github.com/>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Security\Core\SecurityContext;

class RegistrationFormType extends AbstractType
{
    private $securityContext;

    public function __construct(SecurityContext $securityContext)
    {
        $this->securityContext = $securityContext;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {      
        $builder
            ->add('firstname', 'text', array('label' => 'form.firstname', 'translation_domain' => 'FOSUserBundle'))
            ->add('lastname', 'text', array('label' => 'form.lastname', 'translation_domain' => 'FOSUserBundle'))
            ->add('hotel', 'text', array('label' => 'form.hotel', 'translation_domain' => 'FOSUserBundle'))
            ->add('room', 'text', array('label' => 'form.room', 'translation_domain' => 'FOSUserBundle'));
                
//        $isAdmin = false;
//        if($this->securityContext->getToken()->getUser() != "anon."){
//            $isAdmin = $this->securityContext->getToken()->getUser()->hasRole('ROLE_ADMIN');
//        }
//        
//        if($isAdmin){
//            $builder
//                ->add('groups', 'entity', array(
//                    'class' => 'TipGroupFosUserBundle:Group',
//                    'property' => 'name',
//                    'expanded' => true,
//                    'multiple' => true,
//                    'required' => true
//                ));
//        }
    }

    public function getParent()
    {
        return 'fos_user_registration';
    }

    public function getName()
    {
        return 'user_registration';
    }
}
