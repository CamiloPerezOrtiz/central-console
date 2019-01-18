<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Aliases
 *
 * @ORM\Table(name="aliases")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\AliasesRepository")
 */
class Aliases
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
     * @ORM\Column(name="nombre", type="string", length=50)
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 20,
     *      minMessage = "The name must be at least {{ limit }} characters long",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=true)
     * @Assert\Length(
     *      min = 3,
     *      max = 50,
     *      minMessage = "The name must be at least {{ limit }} characters long",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=50)
     */
    private $tipo;

    /**
     * @var string
     *
     * @ORM\Column(name="ip_port", type="text")
     */
    private $ip_port;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion_ip_port", type="text")
     */
    private $descripcion_ip_port;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=50)
     */
    private $grupo;

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
     * @return Aliases
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Aliases
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set ip_port
     *
     * @param string $ip_port
     *
     * @return Aliases
     */
    public function setIp_port($ip_port)
    {
        $this->ip_port = $ip_port;

        return $this;
    }

    /**
     * Get ip_port
     *
     * @return string
     */
    public function getIp_port()
    {
        return $this->ip_port;
    }

    /**
     * Set descripcion_ip_port
     *
     * @param string $descripcion_ip_port
     *
     * @return Aliases
     */
    public function setDescripcion_ip_port($descripcion_ip_port)
    {
        $this->descripcion_ip_port = $descripcion_ip_port;

        return $this;
    }

    /**
     * Get descripcion_ip_port
     *
     * @return string
     */
    public function getDescripcion_ip_port()
    {
        return $this->descripcion_ip_port;
    }

    /**
     * Set grupo
     *
     * @param string $grupo
     *
     * @return Usuarios
     */
    public function setGrupo($grupo)
    {
        $this->grupo = $grupo;

        return $this;
    }

    /**
     * Get grupo
     *
     * @return string
     */
    public function getGrupo()
    {
        return $this->grupo;
    }

    /**
     * Set tipo
     *
     * @param string $tipo
     *
     * @return Aliases
     */
    public function setTipo($tipo)
    {
        $this->tipo = $tipo;

        return $this;
    }

    /**
     * Get tipo
     *
     * @return string
     */
    public function getTipo()
    {
        return $this->tipo;
    }

}
