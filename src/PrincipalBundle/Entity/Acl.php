<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Acl
 *
 * @ORM\Table(name="acl")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\AclRepository")
 */
class Acl
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
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 2,
     *      max = 15,
     *      minMessage = "The name must be at least {{ limit }} characters long",
     *      maxMessage = "The name cannot be longer than {{ limit }} characters"
     * )
     * @Assert\Regex("/^\w+/")
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="cliente", type="text")
     */
    private $cliente;

    /**
     * @var string
     *
     * @ORM\Column(name="target_rule", type="text")
     */
    private $targetRule;

    /**
     * @var string
     *
     * @ORM\Column(name="target_rules_list", type="text")
     */
    private $targetRulesList;

    /**
     * @var bool
     *
     * @ORM\Column(name="not_allow_ip", type="boolean")
     */
    private $notAllowIp;

    /**
     * @var string
     *
     * @ORM\Column(name="redirectMode", type="string", length=50)
     */
    private $redirectMode;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect", type="string", length=50)
     */
    private $redirect;

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
     * Set estatus
     *
     * @param boolean $estatus
     *
     * @return Acl
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
     * @return Acl
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
     * @return Acl
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
     * Set targetRulesList
     *
     * @param string $targetRulesList
     *
     * @return Acl
     */
    public function setTargetRulesList($targetRulesList)
    {
        $this->targetRulesList = $targetRulesList;

        return $this;
    }

    /**
     * Get targetRulesList
     *
     * @return string
     */
    public function getTargetRulesList()
    {
        return $this->targetRulesList;
    }

    /**
     * Set targetRule
     *
     * @param string $targetRule
     *
     * @return Acl
     */
    public function setTargetRule($targetRule)
    {
        $this->targetRule = $targetRule;

        return $this;
    }

    /**
     * Get targetRule
     *
     * @return string
     */
    public function getTargetRule()
    {
        return $this->targetRule;
    }

    /**
     * Set notAllowIp
     *
     * @param boolean $notAllowIp
     *
     * @return Acl
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
     * @return Acl
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
     * @return Acl
     */
    public function setRedirectMode($redirectMode)
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
     * @return Acl
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
     * @return Acl
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

    /**
     * Set grupo
     *
     * @param string $grupo
     *
     * @return Acl
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

