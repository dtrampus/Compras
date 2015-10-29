<?php
namespace UserBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;

class PermissionType extends AbstractType 
{
    private $roles;

    public function __construct(Container $container)
    {
        $roles = $container->getParameter('security.role_hierarchy.roles');
        $this->roles = $this->flatArray($roles);
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'choices' => $this->roles
        ));
    }

    public function getParent()
    {
        return 'choice';
    }

    public function getName()
    {
        return 'permissionType';
    }

    private function flatArray(array $data)
    {
        $result = array();
        foreach ($data as $key => $value) {
            if (substr($key, 0, 4) === 'ROLE') {
                $result[$key] = $key;
            }
            if (is_array($value)) {
                $tmpresult = $this->flatArray($value);
                if (count($tmpresult) > 0) {
                    $result = array_merge($result, $tmpresult);
                }
            } else {
                $result[$value] = $value;
            }
        }
        return array_unique($result);
    }
}