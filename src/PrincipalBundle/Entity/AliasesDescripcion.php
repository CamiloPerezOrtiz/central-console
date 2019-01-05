<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AliasesDescripcion
 *
 * @ORM\Table(name="aliases_descripcion")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\AliasesDescripcionRepository")
 */
class AliasesDescripcion
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
     * @ORM\Column(name="ips", type="text")
     */
    private $ips;

    /**
     * @var string
     *
     * @ORM\Column(name="descripciones", type="text")
     */
    private $descripciones;
    
    /**
     * @ORM\ManyToOne(targetEntity="Aliases", inversedBy="id_aliases_descripcion")
     * @ORM\JoinColumn(name="id_aliases_descripcion", referencedColumnName="id")
     */
    private $aliases_descripcion;


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
     * Set ips
     *
     * @param string $ips
     *
     * @return AliasesDescripcion
     */
    public function setIps($ips)
    {
        $this->ips = $ips;

        return $this;
    }

    /**
     * Get ips
     *
     * @return string
     */
    public function getIps()
    {
        return $this->ips;
    }

    /**
     * Set descripciones
     *
     * @param string $descripciones
     *
     * @return AliasesDescripcion
     */
    public function setDescripciones($descripciones)
    {
        $this->descripciones = $descripciones;

        return $this;
    }

    /**
     * Get descripciones
     *
     * @return string
     */
    public function getDescripciones()
    {
        return $this->descripciones;
    }

    /**
     * Set aliasesDescripcion
     *
     * @param \PrincipalBundle\Entity\Aliases $aliasesDescripcion
     *
     * @return AliasesDescripcion
     */
    public function setAliasesDescripcion(\PrincipalBundle\Entity\Aliases $aliasesDescripcion = null)
    {
        $this->aliases_descripcion = $aliasesDescripcion;

        return $this;
    }

    /**
     * Get aliasesDescripcion
     *
     * @return \PrincipalBundle\Entity\Aliases
     */
    public function getAliasesDescripcion()
    {
        return $this->aliases_descripcion;
    }
}
