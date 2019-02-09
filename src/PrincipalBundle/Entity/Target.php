<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Target
 *
 * @ORM\Table(name="target")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\TargetRepository")
 */
class Target
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
     * @ORM\Column(name="nombre", type="string", length=20)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="domain_list", type="text")
     */
    private $domainList;

    /**
     * @var string
     *
     * @ORM\Column(name="url_list", type="text")
     */
    private $urlList;

    /**
     * @var string
     *
     * @ORM\Column(name="regular_expression", type="text")
     */
    private $regularExpression;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_mode", type="string", length=50)
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
     * @var string
     *
     * @ORM\Column(name="ubicacion", type="string", length=50)
     */
    private $ubicacion;


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
     * @return Target
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
     * Set domainList
     *
     * @param string $domainList
     *
     * @return Target
     */
    public function setDomainList($domainList)
    {
        $this->domainList = $domainList;

        return $this;
    }

    /**
     * Get domainList
     *
     * @return string
     */
    public function getDomainList()
    {
        return $this->domainList;
    }

    /**
     * Set urlList
     *
     * @param string $urlList
     *
     * @return Target
     */
    public function setUrlList($urlList)
    {
        $this->urlList = $urlList;

        return $this;
    }

    /**
     * Get urlList
     *
     * @return string
     */
    public function getUrlList()
    {
        return $this->urlList;
    }

    /**
     * Set regularExpression
     *
     * @param string $regularExpression
     *
     * @return Target
     */
    public function setRegularExpression($regularExpression)
    {
        $this->regularExpression = $regularExpression;

        return $this;
    }

    /**
     * Get regularExpression
     *
     * @return string
     */
    public function getRegularExpression()
    {
        return $this->regularExpression;
    }

    /**
     * Set redirectMode
     *
     * @param string $redirectMode
     *
     * @return Target
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
     * Set redirect
     *
     * @param string $redirect
     *
     * @return Target
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return Target
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
     * @return Target
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
     * @return Target
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
     * Set ubicacion
     *
     * @param string $ubicacion
     *
     * @return Target
     */
    public function setUbicacion($ubicacion)
    {
        $this->ubicacion = $ubicacion;

        return $this;
    }

    /**
     * Get ubicacion
     *
     * @return string
     */
    public function getUbicacion()
    {
        return $this->ubicacion;
    }
}

