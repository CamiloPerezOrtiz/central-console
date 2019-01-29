<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * FirewallLan
 *
 * @ORM\Table(name="firewall_lan")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\FirewallLanRepository")
 */
class FirewallLan
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
     * @ORM\Column(name="action", type="string", length=25)
     */
    private $action;

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
     * @ORM\Column(name="adress_family", type="string", length=25)
     */
    private $adressFamily;

    /**
     * @var string
     *
     * @ORM\Column(name="protocolo", type="string", length=25)
     */
    private $protocolo;

    /**
     * @var string
     *
     * @ORM\Column(name="icm_subtypes", type="text")
     */
    private $icmSubtypes;

    /**
     * @var bool
     *
     * @ORM\Column(name="source_invert_match", type="boolean")
     */
    private $sourceInvertMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="source_type", type="string", length=25)
     */
    private $sourceType;

    /**
     * @var string
     *
     * @ORM\Column(name="source_addres_mask", type="string", length=20)
     */
    private $sourceAddresMask;

    /**
     * @var string
     *
     * @ORM\Column(name="source_port_range_from", type="string", length=20)
     */
    private $sourcePortRangeFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="source_port_range_custom", type="string", length=25)
     */
    private $sourcePortRangeCustom;

    /**
     * @var string
     *
     * @ORM\Column(name="source_port_range_to", type="string", length=20)
     */
    private $sourcePortRangeTo;

    /**
     * @var string
     *
     * @ORM\Column(name="source_port_range_custom_to", type="string", length=20)
     */
    private $sourcePortRangeCustomTo;

    /**
     * @var bool
     *
     * @ORM\Column(name="destination_invert_match", type="boolean")
     */
    private $destinationInvertMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_type", type="string", length=25)
     */
    private $destinationType;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_addres_mask", type="string", length=20)
     */
    private $destinationAddresMask;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_port_range_from", type="string", length=20)
     */
    private $destinationPortRangeFrom;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_port_range_custom", type="string", length=25)
     */
    private $destinationPortRangeCustom;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_port_range_to", type="string", length=20)
     */
    private $destinationPortRangeTo;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_port_range_custom_to", type="string", length=20)
     */
    private $destinationPortRangeCustomTo;

    /**
     * @var bool
     *
     * @ORM\Column(name="log", type="boolean")
     */
    private $log;

    /**
     * @var string
     *
     * @ORM\Column(name="descripcion", type="string", length=40)
     */
    private $descripcion;

    /**
     * @var string
     *
     * @ORM\Column(name="grupo", type="string", length=50)
     */
    private $grupo;

    /**
     * @var int
     *
     * @ORM\Column(name="posicion", type="integer")
     */
    private $posicion;


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
     * Set action
     *
     * @param string $action
     *
     * @return FirewallLan
     */
    public function setAction($action)
    {
        $this->action = $action;

        return $this;
    }

    /**
     * Get action
     *
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * Set estatus
     *
     * @param boolean $estatus
     *
     * @return FirewallLan
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
     * @return FirewallLan
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
     * Set adressFamily
     *
     * @param string $adressFamily
     *
     * @return FirewallLan
     */
    public function setAdressFamily($adressFamily)
    {
        $this->adressFamily = $adressFamily;

        return $this;
    }

    /**
     * Get adressFamily
     *
     * @return string
     */
    public function getAdressFamily()
    {
        return $this->adressFamily;
    }

    /**
     * Set protocolo
     *
     * @param string $protocolo
     *
     * @return FirewallLan
     */
    public function setProtocolo($protocolo)
    {
        $this->protocolo = $protocolo;

        return $this;
    }

    /**
     * Get protocolo
     *
     * @return string
     */
    public function getProtocolo()
    {
        return $this->protocolo;
    }

    /**
     * Set icmSubtypes
     *
     * @param string $icmSubtypes
     *
     * @return FirewallLan
     */
    public function setIcmSubtypes($icmSubtypes)
    {
        $this->icmSubtypes = $icmSubtypes;

        return $this;
    }

    /**
     * Get icmSubtypes
     *
     * @return string
     */
    public function getIcmSubtypes()
    {
        return $this->icmSubtypes;
    }

    /**
     * Set sourceInvertMatch
     *
     * @param boolean $sourceInvertMatch
     *
     * @return FirewallLan
     */
    public function setSourceInvertMatch($sourceInvertMatch)
    {
        $this->sourceInvertMatch = $sourceInvertMatch;

        return $this;
    }

    /**
     * Get sourceInvertMatch
     *
     * @return bool
     */
    public function getSourceInvertMatch()
    {
        return $this->sourceInvertMatch;
    }

    /**
     * Set sourceType
     *
     * @param string $sourceType
     *
     * @return FirewallLan
     */
    public function setSourceType($sourceType)
    {
        $this->sourceType = $sourceType;

        return $this;
    }

    /**
     * Get sourceType
     *
     * @return string
     */
    public function getSourceType()
    {
        return $this->sourceType;
    }

    /**
     * Set sourceAddresMask
     *
     * @param string $sourceAddresMask
     *
     * @return FirewallLan
     */
    public function setSourceAddresMask($sourceAddresMask)
    {
        $this->sourceAddresMask = $sourceAddresMask;

        return $this;
    }

    /**
     * Get sourceAddresMask
     *
     * @return string
     */
    public function getSourceAddresMask()
    {
        return $this->sourceAddresMask;
    }

    /**
     * Set sourcePortRangeFrom
     *
     * @param string $sourcePortRangeFrom
     *
     * @return FirewallLan
     */
    public function setSourcePortRangeFrom($sourcePortRangeFrom)
    {
        $this->sourcePortRangeFrom = $sourcePortRangeFrom;

        return $this;
    }

    /**
     * Get sourcePortRangeFrom
     *
     * @return string
     */
    public function getSourcePortRangeFrom()
    {
        return $this->sourcePortRangeFrom;
    }

    /**
     * Set sourcePortRangeCustom
     *
     * @param string $sourcePortRangeCustom
     *
     * @return FirewallLan
     */
    public function setSourcePortRangeCustom($sourcePortRangeCustom)
    {
        $this->sourcePortRangeCustom = $sourcePortRangeCustom;

        return $this;
    }

    /**
     * Get sourcePortRangeCustom
     *
     * @return string
     */
    public function getSourcePortRangeCustom()
    {
        return $this->sourcePortRangeCustom;
    }

    /**
     * Set sourcePortRangeTo
     *
     * @param string $sourcePortRangeTo
     *
     * @return FirewallLan
     */
    public function setSourcePortRangeTo($sourcePortRangeTo)
    {
        $this->sourcePortRangeTo = $sourcePortRangeTo;

        return $this;
    }

    /**
     * Get sourcePortRangeTo
     *
     * @return string
     */
    public function getSourcePortRangeTo()
    {
        return $this->sourcePortRangeTo;
    }

    /**
     * Set sourcePortRangeCustomTo
     *
     * @param string $sourcePortRangeCustomTo
     *
     * @return FirewallLan
     */
    public function setSourcePortRangeCustomTo($sourcePortRangeCustomTo)
    {
        $this->sourcePortRangeCustomTo = $sourcePortRangeCustomTo;

        return $this;
    }

    /**
     * Get sourcePortRangeCustomTo
     *
     * @return string
     */
    public function getSourcePortRangeCustomTo()
    {
        return $this->sourcePortRangeCustomTo;
    }

    /**
     * Set destinationInvertMatch
     *
     * @param boolean $destinationInvertMatch
     *
     * @return FirewallLan
     */
    public function setDestinationInvertMatch($destinationInvertMatch)
    {
        $this->destinationInvertMatch = $destinationInvertMatch;

        return $this;
    }

    /**
     * Get destinationInvertMatch
     *
     * @return bool
     */
    public function getDestinationInvertMatch()
    {
        return $this->destinationInvertMatch;
    }

    /**
     * Set destinationType
     *
     * @param string $destinationType
     *
     * @return FirewallLan
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
     * Set destinationAddresMask
     *
     * @param string $destinationAddresMask
     *
     * @return FirewallLan
     */
    public function setDestinationAddresMask($destinationAddresMask)
    {
        $this->destinationAddresMask = $destinationAddresMask;

        return $this;
    }

    /**
     * Get destinationAddresMask
     *
     * @return string
     */
    public function getDestinationAddresMask()
    {
        return $this->destinationAddresMask;
    }

    /**
     * Set destinationPortRangeFrom
     *
     * @param string $destinationPortRangeFrom
     *
     * @return FirewallLan
     */
    public function setDestinationPortRangeFrom($destinationPortRangeFrom)
    {
        $this->destinationPortRangeFrom = $destinationPortRangeFrom;

        return $this;
    }

    /**
     * Get destinationPortRangeFrom
     *
     * @return string
     */
    public function getDestinationPortRangeFrom()
    {
        return $this->destinationPortRangeFrom;
    }

    /**
     * Set destinationPortRangeCustom
     *
     * @param string $destinationPortRangeCustom
     *
     * @return FirewallLan
     */
    public function setDestinationPortRangeCustom($destinationPortRangeCustom)
    {
        $this->destinationPortRangeCustom = $destinationPortRangeCustom;

        return $this;
    }

    /**
     * Get destinationPortRangeCustom
     *
     * @return string
     */
    public function getDestinationPortRangeCustom()
    {
        return $this->destinationPortRangeCustom;
    }

    /**
     * Set destinationPortRangeTo
     *
     * @param string $destinationPortRangeTo
     *
     * @return FirewallLan
     */
    public function setDestinationPortRangeTo($destinationPortRangeTo)
    {
        $this->destinationPortRangeTo = $destinationPortRangeTo;

        return $this;
    }

    /**
     * Get destinationPortRangeTo
     *
     * @return string
     */
    public function getDestinationPortRangeTo()
    {
        return $this->destinationPortRangeTo;
    }

    /**
     * Set destinationPortRangeCustomTo
     *
     * @param string $destinationPortRangeCustomTo
     *
     * @return FirewallLan
     */
    public function setDestinationPortRangeCustomTo($destinationPortRangeCustomTo)
    {
        $this->destinationPortRangeCustomTo = $destinationPortRangeCustomTo;

        return $this;
    }

    /**
     * Get destinationPortRangeCustomTo
     *
     * @return string
     */
    public function getDestinationPortRangeCustomTo()
    {
        return $this->destinationPortRangeCustomTo;
    }

    /**
     * Set log
     *
     * @param boolean $log
     *
     * @return FirewallLan
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
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return FirewallLan
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
     * Set grupo
     *
     * @param string $grupo
     *
     * @return FirewallLan
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
     * Set posicion
     *
     * @param integer $posicion
     *
     * @return FirewallLan
     */
    public function setPosicion($posicion)
    {
        $this->posicion = $posicion;

        return $this;
    }

    /**
     * Get posicion
     *
     * @return int
     */
    public function getPosicion()
    {
        return $this->posicion;
    }
}

