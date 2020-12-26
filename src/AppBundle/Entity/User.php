<?php

namespace AppBundle\Entity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table(name="User")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="username", type="string", length=255, unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "8",
     *      max = "15",
     * )
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="apellidos", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "100",
     * )
     */
    private $apellidos;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=30)
     * @Assert\NotBlank()
     * @Assert\Length(
     *      min = "2",
     *      max = "50",
     * )
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=80)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fechaAlta", type="datetime")
     * @Assert\DateTime
     */
    private $fechaAlta;

    /**
     * @var string
     *
     * @ORM\Column(name="roles", type="string", columnDefinition="enum('ROLE_USER', 'ROLE_ADMIN')" , length=15, nullable=false)
     */
    private $roles;


    private $tasks;

    public function __construct() {

        $this->tasks = new \Doctrine\Common\Collections\ArrayCollection();

    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return Usuario
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set apellidos
     *
     * @param string $apellidos
     *
     * @return Usuario
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Usuario
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
     * Set password
     *
     * @param string $password
     *
     * @return Usuario
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set fechaAlta
     *
     * @param \DateTime $fechaAlta
     *
     * @return Usuario
     */
    public function setFechaAlta($fechaAlta)
    {
        $this->fechaAlta = $fechaAlta;

        return $this;
    }

    /**
     * Get fechaAlta
     *
     * @return \DateTime
     */
    public function getFechaAlta()
    {
        return $this->fechaAlta;
    }

    /**
     * Set roles
     *
     * @param string $roles
     *
     * @return Usuario
     */
    public function setRoles($roles)
    {
    if (!in_array($roles, array('ROLE_USER', 'ROLE_ADMIN'))) {
        throw new \InvalidArgumentException("Rol no valido");
    }
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get roles
     *
     * @return string
     */

    public function getRoles()
    {
        return [$this->roles];
    }




    public function getSalt()
    {
        return null;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /** @see \Serializable::serialize() */
    public function serialize()
    {
        return serialize(array(
            $this->idUsuario,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt,
        ));
    }

    /** @see \Serializable::unserialize() */
    public function unserialize($serialized)
    {
        list (
            $this->idUsuario,
            $this->username,
            $this->password,
            // see section on salt below
            // $this->salt
        ) = unserialize($serialized);
    }


    /**
     * Add task
     *
     * @param \AppBundle\Entity\Todo $task
     *
     * @return Usuario
     */
    public function addTask(\AppBundle\Entity\Todo $task)
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks[] = $task;
        }
        return $this;
    }

    /**
     * Remove task
     *
     * @param \AppBundle\Entity\Todo $task
     */
    public function removeTask(\AppBundle\Entity\Todo $task)
    {
        $this->tasks->removeElement($task);
    }

    /**
     * Get Tasks
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getTasks()
    {
        return $this->tasks;
    }







}
