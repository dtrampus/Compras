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

class GroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', 'text', array('label' => 'form.group_name', 'translation_domain' => 'FOSUserBundle'))
                ->add('roles', 'permissionType', array(
                    'mapped' => true,
                    'expanded' => true,
                    'multiple' => true,
                ));
    }
    
    public function getParent()
    {
        return 'fos_user_group';
    }

    public function getName()
    {
        return 'user_group';
    }
}
