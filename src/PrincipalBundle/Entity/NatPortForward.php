<?php

namespace PrincipalBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * NatPortForward
 *
 * @ORM\Table(name="nat_port_forward")
 * @ORM\Entity(repositoryClass="PrincipalBundle\Repository\NatPortForwardRepository")
 */
class NatPortForward
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
     * @ORM\Column(name="protocolo", type="string", length=25)
     */
    private $protocolo;

    /**
     * @var bool
     *
     * @ORM\Column(name="source_advanced_invert_match", type="boolean")
     */
    private $sourceAdvancedInvertMatch;

    /**
     * @var string
     *
     * @ORM\Column(name="source_advanced_type", type="string", length=25)
     */
    private $sourceAdvancedType;

    /**
     * @var string
     *
     * @ORM\Column(name="source_advanced_adress_mask", type="string", length=25, nullable=true)
     */
    private $sourceAdvancedAdressMask;

    /**
     * @var string
     *
     * @ORM\Column(name="source_advanced_from_port", type="string", length=20)
     */
    private $sourceAdvancedFromPort;

    /**
     * @var string
     *
     * @ORM\Column(name="source_advanced_custom", type="string", length=25)
     */
    private $sourceAdvancedCustom;

    /**
     * @var string
     *
     * @ORM\Column(name="source_advanced_to_port", type="string", length=20)
     */
    private $sourceAdvancedToPort;

    /**
     * @var string
     *
     * @ORM\Column(name="source_advanced_custom_to_port", type="string", length=25)
     */
    private $sourceAdvancedCustomToPort;

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
     * @ORM\Column(name="destination_adress_mask", type="string", length=25)
     */
    private $destinationAdressMask;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_range_from_port", type="string", length=20)
     */
    private $destinationRangeFromPort;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_range_custom", type="string", length=25)
     */
    private $destinationRangeCustom;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_range_to_port", type="string", length=20)
     */
    private $destinationRangeToPort;

    /**
     * @var string
     *
     * @ORM\Column(name="destination_range_custom_to_port", type="string", length=25)
     */
    private $destinationRangeCustomToPort;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_target_ip", type="string", length=15)
     */
    private $redirectTargetIp;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_target_port", type="string", length=25)
     */
    private $redirectTargetPort;

    /**
     * @var string
     *
     * @ORM\Column(name="redirect_target_port_custom", type="string", length=25)
     */
    private $redirectTargetPortCustom;

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
     * @ORM\Column(name="filter_rule_association", type="string", length=25)
     */
    private $filterRuleAssociation;

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
     * @return NatPortForward
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
     * @param int $interface
     *
     * @return NatPortForward
     */
    public function setInterface($interface)
    {
        $this->interface = $interface;

        return $this;
    }

    /**
     * Get interface
     *
     * @return int
     */
    public function getInterface()
    {
        return $this->interface;
    }

    /**
     * Set protocolo
     *
     * @param string $protocolo
     *
     * @return NatPortForward
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
     * Set sourceAdvancedInvertMatch
     *
     * @param boolean $sourceAdvancedInvertMatch
     *
     * @return NatPortForward
     */
    public function setSourceAdvancedInvertMatch($sourceAdvancedInvertMatch)
    {
        $this->sourceAdvancedInvertMatch = $sourceAdvancedInvertMatch;

        return $this;
    }

    /**
     * Get sourceAdvancedInvertMatch
     *
     * @return bool
     */
    public function getSourceAdvancedInvertMatch()
    {
        return $this->sourceAdvancedInvertMatch;
    }

    /**
     * Set sourceAdvancedType
     *
     * @param string $sourceAdvancedType
     *
     * @return NatPortForward
     */
    public function setSourceAdvancedType($sourceAdvancedType)
    {
        $this->sourceAdvancedType = $sourceAdvancedType;

        return $this;
    }

    /**
     * Get sourceAdvancedType
     *
     * @return string
     */
    public function getSourceAdvancedType()
    {
        return $this->sourceAdvancedType;
    }

    /**
     * Set sourceAdvancedAdressMask
     *
     * @param string $sourceAdvancedAdressMask
     *
     * @return NatPortForward
     */
    public function setSourceAdvancedAdressMask($sourceAdvancedAdressMask)
    {
        $this->sourceAdvancedAdressMask = $sourceAdvancedAdressMask;

        return $this;
    }

    /**
     * Get sourceAdvancedAdressMask
     *
     * @return string
     */
    public function getSourceAdvancedAdressMask()
    {
        return $this->sourceAdvancedAdressMask;
    }

    /**
     * Set sourceAdvancedFromPort
     *
     * @param string $sourceAdvancedFromPort
     *
     * @return NatPortForward
     */
    public function setSourceAdvancedFromPort($sourceAdvancedFromPort)
    {
        $this->sourceAdvancedFromPort = $sourceAdvancedFromPort;

        return $this;
    }

    /**
     * Get sourceAdvancedFromPort
     *
     * @return string
     */
    public function getSourceAdvancedFromPort()
    {
        return $this->sourceAdvancedFromPort;
    }

    /**
     * Set sourceAdvancedCustom
     *
     * @param string $sourceAdvancedCustom
     *
     * @return NatPortForward
     */
    public function setSourceAdvancedCustom($sourceAdvancedCustom)
    {
        $this->sourceAdvancedCustom = $sourceAdvancedCustom;

        return $this;
    }

    /**
     * Get sourceAdvancedCustom
     *
     * @return string
     */
    public function getSourceAdvancedCustom()
    {
        return $this->sourceAdvancedCustom;
    }

    /**
     * Set sourceAdvancedToPort
     *
     * @param string $sourceAdvancedToPort
     *
     * @return NatPortForward
     */
    public function setSourceAdvancedToPort($sourceAdvancedToPort)
    {
        $this->sourceAdvancedToPort = $sourceAdvancedToPort;

        return $this;
    }

    /**
     * Get sourceAdvancedToPort
     *
     * @return string
     */
    public function getSourceAdvancedToPort()
    {
        return $this->sourceAdvancedToPort;
    }

    /**
     * Set sourceAdvancedCustomToPort
     *
     * @param string $sourceAdvancedCustomToPort
     *
     * @return NatPortForward
     */
    public function setSourceAdvancedCustomToPort($sourceAdvancedCustomToPort)
    {
        $this->sourceAdvancedCustomToPort = $sourceAdvancedCustomToPort;

        return $this;
    }

    /**
     * Get sourceAdvancedCustomToPort
     *
     * @return string
     */
    public function getSourceAdvancedCustomToPort()
    {
        return $this->sourceAdvancedCustomToPort;
    }

    /**
     * Set destinationInvertMatch
     *
     * @param boolean $destinationInvertMatch
     *
     * @return NatPortForward
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
     * @return NatPortForward
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
     * @return NatPortForward
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
     * Set destinationRangeFromPort
     *
     * @param string $destinationRangeFromPort
     *
     * @return NatPortForward
     */
    public function setDestinationRangeFromPort($destinationRangeFromPort)
    {
        $this->destinationRangeFromPort = $destinationRangeFromPort;

        return $this;
    }

    /**
     * Get destinationRangeFromPort
     *
     * @return string
     */
    public function getDestinationRangeFromPort()
    {
        return $this->destinationRangeFromPort;
    }

    /**
     * Set destinationRangeCustom
     *
     * @param string $destinationRangeCustom
     *
     * @return NatPortForward
     */
    public function setDestinationRangeCustom($destinationRangeCustom)
    {
        $this->destinationRangeCustom = $destinationRangeCustom;

        return $this;
    }

    /**
     * Get destinationRangeCustom
     *
     * @return string
     */
    public function getDestinationRangeCustom()
    {
        return $this->destinationRangeCustom;
    }

    /**
     * Set destinationRangeToPort
     *
     * @param string $destinationRangeToPort
     *
     * @return NatPortForward
     */
    public function setDestinationRangeToPort($destinationRangeToPort)
    {
        $this->destinationRangeToPort = $destinationRangeToPort;

        return $this;
    }

    /**
     * Get destinationRangeToPort
     *
     * @return string
     */
    public function getDestinationRangeToPort()
    {
        return $this->destinationRangeToPort;
    }

    /**
     * Set destinationRangeCustomToPort
     *
     * @param string $destinationRangeCustomToPort
     *
     * @return NatPortForward
     */
    public function setDestinationRangeCustomToPort($destinationRangeCustomToPort)
    {
        $this->destinationRangeCustomToPort = $destinationRangeCustomToPort;

        return $this;
    }

    /**
     * Get destinationRangeCustomToPort
     *
     * @return string
     */
    public function getDestinationRangeCustomToPort()
    {
        return $this->destinationRangeCustomToPort;
    }

    /**
     * Set redirectTargetIp
     *
     * @param string $redirectTargetIp
     *
     * @return NatPortForward
     */
    public function setRedirectTargetIp($redirectTargetIp)
    {
        $this->redirectTargetIp = $redirectTargetIp;

        return $this;
    }

    /**
     * Get redirectTargetIp
     *
     * @return string
     */
    public function getRedirectTargetIp()
    {
        return $this->redirectTargetIp;
    }

    /**
     * Set redirectTargetPort
     *
     * @param string $redirectTargetPort
     *
     * @return NatPortForward
     */
    public function setRedirectTargetPort($redirectTargetPort)
    {
        $this->redirectTargetPort = $redirectTargetPort;

        return $this;
    }

    /**
     * Get redirectTargetPort
     *
     * @return string
     */
    public function getRedirectTargetPort()
    {
        return $this->redirectTargetPort;
    }

    /**
     * Set redirectTargetPortCustom
     *
     * @param string $redirectTargetPortCustom
     *
     * @return NatPortForward
     */
    public function setRedirectTargetPortCustom($redirectTargetPortCustom)
    {
        $this->redirectTargetPortCustom = $redirectTargetPortCustom;

        return $this;
    }

    /**
     * Get redirectTargetPortCustom
     *
     * @return string
     */
    public function getRedirectTargetPortCustom()
    {
        return $this->redirectTargetPortCustom;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     *
     * @return NatPortForward
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
     * @return NatPortForward
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
     * Set filterRuleAssociation
     *
     * @param string $filterRuleAssociation
     *
     * @return NatPortForward
     */
    public function setFilterRuleAssociation($filterRuleAssociation)
    {
        $this->filterRuleAssociation = $filterRuleAssociation;

        return $this;
    }

    /**
     * Get filterRuleAssociation
     *
     * @return string
     */
    public function getFilterRuleAssociation()
    {
        return $this->filterRuleAssociation;
    }

    /**
     * Set grupo
     *
     * @param string $grupo
     *
     * @return NatPortForward
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

