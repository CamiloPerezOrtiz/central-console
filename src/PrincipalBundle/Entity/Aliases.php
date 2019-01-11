<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

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
     * @ORM\Column(name="nombre", type="string", length=20, nullable=true)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50, nullable=true)
     */
    private $descripcion;

    /**
     * @var int
     *
     * @ORM\Column(name="id_aliases_tipo", type="integer")
     */
    private $id_aliases_tipo;

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
     * Set id_aliases_tipo
     *
     * @param int $id_aliases_tipo
     *
     * @return Aliases
     */
    public function setId_aliases_tipo($id_aliases_tipo)
    {
        $this->id_aliases_tipo = $id_aliases_tipo;

        return $this;
    }

    /**
     * Get id_aliases_tipo
     *
     * @return int
     */
    public function getId_aliases_tipo()
    {
        return $this->id_aliases_tipo;
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

}
