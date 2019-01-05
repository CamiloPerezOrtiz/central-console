<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AclGroups
 *
 * @ORM\Table(name="acl_groups")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\AclGroupsRepository")
 */
class AclGroups
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
     * @var bool
     *
     * @ORM\Column(name="estatus", type="boolean")
     */
    private $estatus;

    /**
     * @var string
     *
     * @ORM\Column(name="nombre", type="string", length=20)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="cliente", type="text")
     */
    private $cliente;

    /**
     * @var bool
     *
     * @ORM\Column(name="not_allow_ip", type="boolean")
     */
    private $notAllowIp;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect", type="string", length=50)
     */
    private $redirect;

    /**
     * @var string
     *
     * @ORM\Column(name="redirectMode", type="string", length=50)
     */
    private $redirectMode;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50)
     */
    private $descripcion;

    /**
     * @var bool
     *
     * @ORM\Column(name="log", type="boolean")
     */
    private $log;


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
     * Set estatus
     *
     * @param boolean $estatus
     *
     * @return AclGroups
     */
    public function setEstatus($estatus)
    {
        $this->estatus = $estatus;

        return $this;
    }

    /**
     * Get estatus
     *
     * @return bool
     */
    public function getEstatus()
    {
        return $this->estatus;
    }

    /**
     * Set nombre
     *
     * @param string $nombre
     *
     * @return AclGroups
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
     * Set cliente
     *
     * @param string $cliente
     *
     * @return AclGroups
     */
    public function setCliente($cliente)
    {
        $this->cliente = $cliente;

        return $this;
    }

    /**
     * Get cliente
     *
     * @return string
     */
    public function getCliente()
    {
        return $this->cliente;
    }

    /**
     * Set notAllowIp
     *
     * @param boolean $notAllowIp
     *
     * @return AclGroups
     */
    public function setNotAllowIp($notAllowIp)
    {
        $this->notAllowIp = $notAllowIp;

        return $this;
    }

    /**
     * Get notAllowIp
     *
     * @return bool
     */
    public function getNotAllowIp()
    {
        return $this->notAllowIp;
    }

    /**
     * Set redirect
     *
     * @param string $redirect
     *
     * @return AclGroups
     */
    public function setRedirect($redirect)
    {
        $this->redirect = $redirect;

        return $this;
    }

    /**
     * Get redirect
     *
     * @return string
     */
    public function getRedirect()
    {
        return $this->redirect;
    }

    /**
     * Set redirectMode
     *
     * @param string $redirectMode
     *
     * @return AclGroups
     */
    public function setRedirectMode($redirect)
    {
        $this->redirectMode = $redirectMode;

        return $this;
    }

    /**
     * Get redirectMode
     *
     * @return string
     */
    public function getRedirectMode()
    {
        return $this->redirectMode;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return AclGroups
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
     * Set log
     *
     * @param boolean $log
     *
     * @return AclGroups
     */
    public function setLog($log)
    {
        $this->log = $log;

        return $this;
    }

    /**
     * Get log
     *
     * @return bool
     */
    public function getLog()
    {
        return $this->log;
    }
}

