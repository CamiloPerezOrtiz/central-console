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
     * @ORM\Column(name="nombre", type="string", length=20)
     */
    private $nombre;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=50)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="tipo", type="string", length=20)
     */
    private $tipo;

    /**
     * @ORM\OneToMany(targetEntity="AliasesDescripcion", mappedBy="aliases_descripcion", cascade={"persist", "remove"})
     */
    private $id_aliases_descripcion;


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

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->id_aliases_descripcion = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add idAliasesDescripcion
     *
     * @param \PrincipalBundle\Entity\AliasesDescripcion $idAliasesDescripcion
     *
     * @return Aliases
     */
    public function addIdAliasesDescripcion(\PrincipalBundle\Entity\AliasesDescripcion $idAliasesDescripcion)
    {
        $this->id_aliases_descripcion[] = $idAliasesDescripcion;
        $idAliasesDescripcion->setAliasesDescripcion($this);
        return $this;
    }

    /**
     * Remove idAliasesDescripcion
     *
     * @param \PrincipalBundle\Entity\AliasesDescripcion $idAliasesDescripcion
     */
    public function removeIdAliasesDescripcion(\PrincipalBundle\Entity\AliasesDescripcion $idAliasesDescripcion)
    {
        $this->id_aliases_descripcion->removeElement($idAliasesDescripcion);
    }

    /**
     * Get idAliasesDescripcion
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdAliasesDescripcion()
    {
        return $this->id_aliases_descripcion;
    }
}