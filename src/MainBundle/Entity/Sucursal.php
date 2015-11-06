<?php

namespace MainBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sucursal
 *
 * @ORM\Table(name="sucursales")
 * @ORM\Entity(repositoryClass="MainBundle\Repository\SucursalRepository")
 */
class Sucursal
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre_bd", type="string", length=255)
     */
    private $nombreBd;
    
//    /**
//     * @ORM\OneToMany(targetEntity="UserBundle\Entity\User", mappedBy="sucursal")
//     */
//    protected $users;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     * @return Sucursal
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set nombreBd
     *
     * @param string $nombreBd
     * @return Sucursal
     */
    public function setNombreBd($nombreBd)
    {
        $this->nombreBd = $nombreBd;

        return $this;
    }

    /**
     * Get nombreBd
     *
     * @return string 
     */
    public function getNombreBd()
    {
        return $this->nombreBd;
    }
    
//    /**
//     * Add user
//     *
//     * @param \UserBundle\Entity\User $user
//     * @return Sucursal
//     */
//    public function addUser(\UserBundle\Entity\User $user)
//    {
//        $this->user[] = $user;
//
//        return $this;
//    }
//
//    /**
//     * Remove user
//     *
//     * @param \UserBundle\Entity\User $user
//     */
//    public function removeUser(\UserBundle\Entity\User $user)
//    {
//        $this->user->removeElement($user);
//    }
//
//    /**
//     * Get user
//     *
//     * @return \Doctrine\Common\Collections\Collection 
//     */
//    public function getUser()
//    {
//        return $this->user;
//    }
}
