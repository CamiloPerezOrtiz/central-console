<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class NatPortForwardType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estatus',CheckboxType::class,array(
                "label"=>"Disabled ",
                "required"=>false
            ))
            ->add('protocolo',ChoiceType::class, array(
                "label"=>"Protocol ",
                "attr"=>array(
                    "class"=>"form-control input-sm",
                    'onChange' => 'protocolOnChange(this);'
                ),
                'choices' => array(
                    'TCP' => 'tcp',
                    'UDP' => 'udp',
                    'TCP/UDP' => 'tcp/udp',
                    'ICMP' => 'icmp'
                )
            ))
            ->add('sourceAdvancedInvertMatch',CheckboxType::class,array(
                "label"=>"Source ",
                "required"=>false
            ))
            ->add('sourceAdvancedType',ChoiceType::class, array(
                "label"=>"Type ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Any' => 'any',
                    'Single host or alias' => 'single',
                    'Network' => 'network',
                    'PPPoE clients' => 'pppoe',
                    'L2TP clients' => 'l2tp',
                    'WAN net' => 'wan',
                    'WAN address' => 'wanip',
                    'LAN net' => 'lan',
                    'LAN address' => 'lanip'
                ),
                'data' => 'any'
            ))
            ->add('sourceAdvancedAdressMask',TextType::class,array(
                "label"=>"Address ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('sourceAdvancedAdressMask1',ChoiceType::class, array(
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    '32' => '32',
                    '31' => '31',
                    '30' => '30',
                    '29' => '29',
                    '28' => '28',
                    '27' => '27',
                    '26' => '26',
                    '25' => '25',
                    '24' => '24',
                    '23' => '23',
                    '22' => '22',
                    '21' => '21',
                    '20' => '20',
                    '19' => '19',
                    '18' => '18',
                    '17' => '17',
                    '16' => '16',
                    '15' => '15',
                    '14' => '14',
                    '13' => '13',
                    '12' => '12',
                    '11' => '11',
                    '10' => '10',
                    '9' => '9',
                    '8' => '8',
                    '7' => '7',
                    '6' => '6',
                    '5' => '5',
                    '4' => '4',
                    '3' => '3',
                    '2' => '2',
                    '1' => '1'
                ),
                'data' => '32'
            ))
            ->add('sourceAdvancedFromPort',ChoiceType::class, array(
                "label"=>"From port",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Other' => '',
                    'Any' => 'any',
                    'CVSup' => '5999',
                    'DNS' => '53',
                    'FTP' => '21',
                    'HBCI' => '3000',
                    'HTTP' => '80',
                    'HTTPS' => '443',
                    'ICQ' => '5190',
                    'IDENT/AUTH' => '113',
                    'IMAP' => '143',
                    'IMAP/S' => '993',
                    'IPsec NAT-T' => '4500',
                    'ISAKMP' => '500',
                    'L2TP' => '1701',
                    'LDAP' => '389',
                    'MMS/TCP' => '1755',
                    'MMS/UDP' => '7000',
                    'MS DS' => '445',
                    'MS RDP' => '3389',
                    'MS WIN' => '1512',
                    'MSN' => '1863',
                    'NNTP' => '119',
                    'NTP' => '123',
                    'NetBIOS-DGM' => '138',
                    'NetBIOS-NS' => '137',
                    'NetBIOS-SSN' => '139',
                    'OpenVPN' => '1194',
                    'POP3' => '110',
                    'POP3/S' => '995',
                    'PPTP' => '1723',
                    'RADIUS' => '1812',
                    'RADIUS accounting' => '1813',
                    'RTP' => '5004',
                    'SIP' => '5060',
                    'SMTP' => '25',
                    'SMTP/S' => '465',
                    'SNMP' => '161',
                    'SNMP-Trap' => '162',
                    'SSH' => '22',
                    'STUN' => '3478',
                    'SUBMISSION' => '587',
                    'Teredo' => '3544',
                    'Telnet' => '23',
                    'TFTP' => '69',
                    'VNC' => '5900'
                ),
                'data' => 'any'
            ))
            ->add('sourceAdvancedCustom',TextType::class,array(
                "label"=>"Custom ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('sourceAdvancedToPort',ChoiceType::class, array(
                "label"=>"To port",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Other' => '',
                    'Any' => 'any',
                    'CVSup' => '5999',
                    'DNS' => '53',
                    'FTP' => '21',
                    'HBCI' => '3000',
                    'HTTP' => '80',
                    'HTTPS' => '443',
                    'ICQ' => '5190',
                    'IDENT/AUTH' => '113',
                    'IMAP' => '143',
                    'IMAP/S' => '993',
                    'IPsec NAT-T' => '4500',
                    'ISAKMP' => '500',
                    'L2TP' => '1701',
                    'LDAP' => '389',
                    'MMS/TCP' => '1755',
                    'MMS/UDP' => '7000',
                    'MS DS' => '445',
                    'MS RDP' => '3389',
                    'MS WIN' => '1512',
                    'MSN' => '1863',
                    'NNTP' => '119',
                    'NTP' => '123',
                    'NetBIOS-DGM' => '138',
                    'NetBIOS-NS' => '137',
                    'NetBIOS-SSN' => '139',
                    'OpenVPN' => '1194',
                    'POP3' => '110',
                    'POP3/S' => '995',
                    'PPTP' => '1723',
                    'RADIUS' => '1812',
                    'RADIUS accounting' => '1813',
                    'RTP' => '5004',
                    'SIP' => '5060',
                    'SMTP' => '25',
                    'SMTP/S' => '465',
                    'SNMP' => '161',
                    'SNMP-Trap' => '162',
                    'SSH' => '22',
                    'STUN' => '3478',
                    'SUBMISSION' => '587',
                    'Teredo' => '3544',
                    'Telnet' => '23',
                    'TFTP' => '69',
                    'VNC' => '5900'
                ),
                'data' => 'any'
            ))
            ->add('sourceAdvancedCustomToPort',TextType::class,array(
                "label"=>"Custom ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('destinationInvertMatch',CheckboxType::class,array(
                "label"=>"Destination ",
                "required"=>false
            ))
            ->add('destinationType',ChoiceType::class, array(
                "label"=>"Type: ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Any' => 'any',
                    'Single host or alias' => 'single',
                    'Network' => 'network',
                    'This firewall (self)' => '(self)',
                    'PPPoE clients' => 'pppoe',
                    'L2TP' => 'l2tp',
                    'WAN net' => 'wan',
                    'WAN address' => 'wanip',
                    'LAN net' => 'lan',
                    'LAN address' => 'lanip'
                ),
                'data' => 'wanip'
            ))
            ->add('destinationAdressMask',TextType::class,array(
                "label"=>"Address ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('destinationAdressMask2',ChoiceType::class, array(
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    '32' => '32',
                    '31' => '31',
                    '30' => '30',
                    '29' => '29',
                    '28' => '28',
                    '27' => '27',
                    '26' => '26',
                    '25' => '25',
                    '24' => '24',
                    '23' => '23',
                    '22' => '22',
                    '21' => '21',
                    '20' => '20',
                    '19' => '19',
                    '18' => '18',
                    '17' => '17',
                    '16' => '16',
                    '15' => '15',
                    '14' => '14',
                    '13' => '13',
                    '12' => '12',
                    '11' => '11',
                    '10' => '10',
                    '9' => '9',
                    '8' => '8',
                    '7' => '7',
                    '6' => '6',
                    '5' => '5',
                    '4' => '4',
                    '3' => '3',
                    '2' => '2',
                    '1' => '1'
                ),
                'data' => '32'
            ))
            ->add('destinationRangeFromPort',ChoiceType::class, array(
                "label"=>"From port",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Other' => '',
                    'Any' => 'any',
                    'CVSup' => '5999',
                    'DNS' => '53',
                    'FTP' => '21',
                    'HBCI' => '3000',
                    'HTTP' => '80',
                    'HTTPS' => '443',
                    'ICQ' => '5190',
                    'IDENT/AUTH' => '113',
                    'IMAP' => '143',
                    'IMAP/S' => '993',
                    'IPsec NAT-T' => '4500',
                    'ISAKMP' => '500',
                    'L2TP' => '1701',
                    'LDAP' => '389',
                    'MMS/TCP' => '1755',
                    'MMS/UDP' => '7000',
                    'MS DS' => '445',
                    'MS RDP' => '3389',
                    'MS WIN' => '1512',
                    'MSN' => '1863',
                    'NNTP' => '119',
                    'NTP' => '123',
                    'NetBIOS-DGM' => '138',
                    'NetBIOS-NS' => '137',
                    'NetBIOS-SSN' => '139',
                    'OpenVPN' => '1194',
                    'POP3' => '110',
                    'POP3/S' => '995',
                    'PPTP' => '1723',
                    'RADIUS' => '1812',
                    'RADIUS accounting' => '1813',
                    'RTP' => '5004',
                    'SIP' => '5060',
                    'SMTP' => '25',
                    'SMTP/S' => '465',
                    'SNMP' => '161',
                    'SNMP-Trap' => '162',
                    'SSH' => '22',
                    'STUN' => '3478',
                    'SUBMISSION' => '587',
                    'Teredo' => '3544',
                    'Telnet' => '23',
                    'TFTP' => '69',
                    'VNC' => '5900'
                )
            ))
            ->add('destinationRangeCustom',TextType::class,array(
                "label"=>"Custom ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('destinationRangeToPort',ChoiceType::class, array(
                "label"=>"To port",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Other' => '',
                    'Any' => 'any',
                    'CVSup' => '5999',
                    'DNS' => '53',
                    'FTP' => '21',
                    'HBCI' => '3000',
                    'HTTP' => '80',
                    'HTTPS' => '443',
                    'ICQ' => '5190',
                    'IDENT/AUTH' => '113',
                    'IMAP' => '143',
                    'IMAP/S' => '993',
                    'IPsec NAT-T' => '4500',
                    'ISAKMP' => '500',
                    'L2TP' => '1701',
                    'LDAP' => '389',
                    'MMS/TCP' => '1755',
                    'MMS/UDP' => '7000',
                    'MS DS' => '445',
                    'MS RDP' => '3389',
                    'MS WIN' => '1512',
                    'MSN' => '1863',
                    'NNTP' => '119',
                    'NTP' => '123',
                    'NetBIOS-DGM' => '138',
                    'NetBIOS-NS' => '137',
                    'NetBIOS-SSN' => '139',
                    'OpenVPN' => '1194',
                    'POP3' => '110',
                    'POP3/S' => '995',
                    'PPTP' => '1723',
                    'RADIUS' => '1812',
                    'RADIUS accounting' => '1813',
                    'RTP' => '5004',
                    'SIP' => '5060',
                    'SMTP' => '25',
                    'SMTP/S' => '465',
                    'SNMP' => '161',
                    'SNMP-Trap' => '162',
                    'SSH' => '22',
                    'STUN' => '3478',
                    'SUBMISSION' => '587',
                    'Teredo' => '3544',
                    'Telnet' => '23',
                    'TFTP' => '69',
                    'VNC' => '5900'
                )
            ))
            ->add('destinationRangeCustomToPort',TextType::class,array(
                "label"=>"Custom ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('redirectTargetIp',TextType::class,array(
                "label"=>"Redirect target IP ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('redirectTargetPort',ChoiceType::class, array(
                "label"=>"Port",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Other' => '',
                    'CVSup' => '5999',
                    'DNS' => '53',
                    'FTP' => '21',
                    'HBCI' => '3000',
                    'HTTP' => '80',
                    'HTTPS' => '443',
                    'ICQ' => '5190',
                    'IDENT/AUTH' => '113',
                    'IMAP' => '143',
                    'IMAP/S' => '993',
                    'IPsec NAT-T' => '4500',
                    'ISAKMP' => '500',
                    'L2TP' => '1701',
                    'LDAP' => '389',
                    'MMS/TCP' => '1755',
                    'MMS/UDP' => '7000',
                    'MS DS' => '445',
                    'MS RDP' => '3389',
                    'MS WIN' => '1512',
                    'MSN' => '1863',
                    'NNTP' => '119',
                    'NTP' => '123',
                    'NetBIOS-DGM' => '138',
                    'NetBIOS-NS' => '137',
                    'NetBIOS-SSN' => '139',
                    'OpenVPN' => '1194',
                    'POP3' => '110',
                    'POP3/S' => '995',
                    'PPTP' => '1723',
                    'RADIUS' => '1812',
                    'RADIUS accounting' => '1813',
                    'RTP' => '5004',
                    'SIP' => '5060',
                    'SMTP' => '25',
                    'SMTP/S' => '465',
                    'SNMP' => '161',
                    'SNMP-Trap' => '162',
                    'SSH' => '22',
                    'STUN' => '3478',
                    'SUBMISSION' => '587',
                    'Teredo' => '3544',
                    'Telnet' => '23',
                    'TFTP' => '69',
                    'VNC' => '5900'
                ),
                'data' => ''
            ))
            ->add('redirectTargetPortCustom',TextType::class,array(
                "label"=>"Custom",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('descripcion',TextType::class,array(
                "label"=>"Description",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('natReflection',ChoiceType::class, array(
                "label"=>"NAT reflection ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Use system default' => 'default',
                    'Enable (NAT + Proxy)' => 'enable',
                    'Enable (Pure NAT)' => 'purenat',
                    'Disable' => 'disable'
                )
            ))
            ->add('filterRuleAssociation',ChoiceType::class, array(
                "label"=>"Filter rule association ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'None' => '',
                    'Pass' => 'pass'
                )
            ))
            ->add('Save',SubmitType::class,array(
                "attr"=>array("
                    class"=>"btn btn-success btn-sm btn-block"
                )
            ))
            ->add('Reset', ResetType::class, array(
                'attr' => array(
                    'class' => 'btn btn-danger btn-sm btn-block'
                )
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\NatPortForward'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_natportforward';
    }


}
