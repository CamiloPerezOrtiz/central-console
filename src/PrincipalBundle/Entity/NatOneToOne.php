<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NatOneToOne
 *
 * @ORM\Table(name="nat_one_to_one")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\NatOneToOneRepository")
 */
class NatOneToOne
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
     * @ORM\Column(name="interface", type="string", length=25)
     */
    private $interface;

    /**
     * @var string
     *
     * @ORM\Column(name="external_subnet_ip", type="string", length=25)
     */
    private $externalSubnetIp;

    /**
     * @var bool
     *
     * @ORM\Column(name="internal_ip", type="boolean")
     */
    private $internalIp;

    /**
     * @var string
     *
     * @ORM\Column(name="internal_ip_type", type="string", length=25)
     */
    private $internalIpType;

    /**
     * @var string
     *
     * @ORM\Column(name="internal_adress_mask", type="string", length=25, nullable=true)
     */
    private $internalAdressMask;

    /**
     * @var int
     *
     * @ORM\Column(name="maskinternal", type="integer", nullable=true)
     */
    private $maskinternal;

    /**
     * @var bool
     *
     * @ORM\Column(name="destination", type="boolean")
     */
    private $destination;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_type", type="string", length=25)
     */
    private $destinationType;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_adress_mask", type="string", length=25, nullable=true)
     */
    private $destinationAdressMask;

    /**
     * @var int
     *
     * @ORM\Column(name="maskDestination", type="integer", nullable=true)
     */
    private $maskDestination;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=25)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="nat_reflection", type="string", length=25)
     */
    private $natReflection;

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
     * Set estatus
     *
     * @param boolean $estatus
     *
     * @return NatOneToOne
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
     * Set interface
     *
     * @param string $interface
     *
     * @return NatOneToOne
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;

        return $this;
    }

    /**
     * Get interface
     *
     * @return string
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * Set externalSubnetIp
     *
     * @param string $externalSubnetIp
     *
     * @return NatOneToOne
     */
    public function setExternalSubnetIp($externalSubnetIp)
    {
        $this->externalSubnetIp = $externalSubnetIp;

        return $this;
    }

    /**
     * Get externalSubnetIp
     *
     * @return string
     */
    public function getExternalSubnetIp()
    {
        return $this->externalSubnetIp;
    }

    /**
     * Set internalIp
     *
     * @param boolean $internalIp
     *
     * @return NatOneToOne
     */
    public function setInternalIp($internalIp)
    {
        $this->internalIp = $internalIp;

        return $this;
    }

    /**
     * Get internalIp
     *
     * @return bool
     */
    public function getInternalIp()
    {
        return $this->internalIp;
    }

    /**
     * Set internalIpType
     *
     * @param string $internalIpType
     *
     * @return NatOneToOne
     */
    public function setInternalIpType($internalIpType)
    {
        $this->internalIpType = $internalIpType;

        return $this;
    }

    /**
     * Get internalIpType
     *
     * @return string
     */
    public function getInternalIpType()
    {
        return $this->internalIpType;
    }

    /**
     * Set internalAdressMask
     *
     * @param string $internalAdressMask
     *
     * @return NatOneToOne
     */
    public function setInternalAdressMask($internalAdressMask)
    {
        $this->internalAdressMask = $internalAdressMask;

        return $this;
    }

    /**
     * Get internalAdressMask
     *
     * @return string
     */
    public function getInternalAdressMask()
    {
        return $this->internalAdressMask;
    }

    /**
     * Set maskinternal
     *
     * @param int $maskinternal
     *
     * @return NatOneToOne
     */
    public function setMaskinternal($maskinternal)
    {
        $this->maskinternal = $maskinternal;

        return $this;
    }

    /**
     * Get maskinternal
     *
     * @return int
     */
    public function getMaskinternal()
    {
        return $this->maskinternal;
    }

    /**
     * Set destination
     *
     * @param boolean $destination
     *
     * @return NatOneToOne
     */
    public function setDestination($destination)
    {
        $this->destination = $destination;

        return $this;
    }

    /**
     * Get destination
     *
     * @return bool
     */
    public function getDestination()
    {
        return $this->destination;
    }

    /**
     * Set destinationType
     *
     * @param string $destinationType
     *
     * @return NatOneToOne
     */
    public function setDestinationType($destinationType)
    {
        $this->destinationType = $destinationType;

        return $this;
    }

    /**
     * Get destinationType
     *
     * @return string
     */
    public function getDestinationType()
    {
        return $this->destinationType;
    }

    /**
     * Set destinationAdressMask
     *
     * @param string $destinationAdressMask
     *
     * @return NatOneToOne
     */
    public function setDestinationAdressMask($destinationAdressMask)
    {
        $this->destinationAdressMask = $destinationAdressMask;

        return $this;
    }

    /**
     * Get destinationAdressMask
     *
     * @return string
     */
    public function getDestinationAdressMask()
    {
        return $this->destinationAdressMask;
    }

    /**
     * Set maskDestination
     *
     * @param int $maskDestination
     *
     * @return NatOneToOne
     */
    public function setMaskDestination($maskDestination)
    {
        $this->maskDestination = $maskDestination;

        return $this;
    }

    /**
     * Get maskDestination
     *
     * @return int
     */
    public function getMaskDestination()
    {
        return $this->maskDestination;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return NatOneToOne
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
     * Set natReflection
     *
     * @param string $natReflection
     *
     * @return NatOneToOne
     */
    public function setNatReflection($natReflection)
    {
        $this->natReflection = $natReflection;

        return $this;
    }

    /**
     * Get natReflection
     *
     * @return string
     */
    public function getNatReflection()
    {
        return $this->natReflection;
    }

    /**
     * Set grupo
     *
     * @param string $grupo
     *
     * @return NatOneToOne
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
     * @return NatOneToOne
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

