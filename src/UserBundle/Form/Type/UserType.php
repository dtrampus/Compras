<?php

namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', 'text', array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', 
                array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch',
                )
            )
            ->add('firstname', 'text', array('label' => 'form.firstname', 'translation_domain' => 'FOSUserBundle'))
            ->add('lastname', 'text', array('label' => 'form.lastname', 'translation_domain' => 'FOSUserBundle'))
            ->add('groups', 'entity', array(
                    'class' => 'UserBundle:Group',
                    'property' => 'name',
                    'expanded' => true,
                    'multiple' => true,
                    'label' => 'form.groups', 
                    'translation_domain' => 'FOSUserBundle'
                )
            )
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'UserBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'user_user';
    }
}
