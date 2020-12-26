<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Todo
 *
 * @ORM\Table(name="todo")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TodoRepository")
 */
class Todo
{

    const STATUS_INICIADA   = 'INICIADA';
    const STATUS_NOINICIADA = 'SIN EMPEZAR';
    const STATUS_FINALIZADA = 'FINALIZADA';

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
     * @ORM\Column(name="nombre", type="string", length=256, unique=true)
     */
    private $nombre;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_creacion", type="datetime")
     */
    private $fechaCreacion;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="fecha_tope", type="datetime", nullable=true)
     */
    private $fechaTope;

    /**
     * @var string
     *
     * @ORM\Column(name="estado", type="string", length=50, columnDefinition="ENUM('INICIADA', 'FINALIZADA', SIN EMPEZAR)")
     */
    private $estado;

    public function __construct()
    {
        $this->fechaCreacion = new \DateTime();
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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Todo
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
     * Set fechaCreacion
     *
     * @param \DateTime $fechaCreacion
     *
     * @return Todo
     */
    public function setFechaCreacion($fechaCreacion)
    {
        $this->fechaCreacion = $fechaCreacion;

        return $this;
    }

    /**
     * Get fechaCreacion
     *
     * @return \DateTime
     */
    public function getFechaCreacion()
    {
        return $this->fechaCreacion;
    }

    /**
     * Set fechaTope
     *
     * @param \DateTime $fechaTope
     *
     * @return Todo
     */
    public function setFechaTope($fechaTope)
    {
        $this->fechaTope = $fechaTope;

        return $this;
    }

    /**
     * Get fechaTope
     *
     * @return \DateTime
     */
    public function getFechaTope()
    {
        return $this->fechaTope;
    }

    /**
     * Set estado
     *
     * @param string $estado
     *
     * @return Todo
     */
    public function setEstado($estado)
    {

        if (!in_array($estado, array(self::STATUS_INICIADA, self::STATUS_NOINICIADA, self::STATUS_FINALIZADA))) {
            throw new \InvalidArgumentException("Invalid status");
        }
        $this->estado = $estado;        
        // $this->estado = $estado;

        return $this;
    }

    /**
     * Get estado
     *
     * @return string
     */
    public function getEstado()
    {
        return $this->estado;
    }
}

