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

class FirewallLanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('action',ChoiceType::class, array(
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Pass' => 'pass',
                    'Block' => 'block',
                    'Reject' => 'reject'
                ),
                'data' => 'pass'
            ))
            ->add('estatus')
            ->add('adressFamily',ChoiceType::class, array(
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'IPv4' => 'inet',
                    'IPv6' => 'inet6',
                    'IPv4+IPv6' => 'inet46'
                ),
                'data' => 'pass'
            ))
            ->add('protocolo',ChoiceType::class, array(
                "attr"=>array(
                    "class"=>"form-control input-sm",
                    'onChange' => 'protocolOnChange(this);'
                ),
                'choices' => array(
                    'Any' => 'any',
                    'TCP' => 'tcp',
                    'UDP' => 'udp',
                    'TCP/UDP' => 'tcp/udp',
                    'ICMP' => 'icmp'
                ),
                'data' => 'tcp'
            ))
            ->add('sourceInvertMatch')
            ->add('sourceAddresMask',TextType::class,array(
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
            ->add('sourcePortRangeFrom',ChoiceType::class, array(
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
            ->add('sourcePortRangeCustom',TextType::class,array(
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('sourcePortRangeTo',ChoiceType::class, array(
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
            ->add('sourcePortRangeCustomTo',TextType::class,array(
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('destinationInvertMatch')
            ->add('destinationAddresMask',TextType::class,array(
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
            ->add('destinationPortRangeFrom',ChoiceType::class, array(
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
            ->add('destinationPortRangeCustom',TextType::class,array(
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('destinationPortRangeTo',ChoiceType::class, array(
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
            ->add('destinationPortRangeCustomTo',TextType::class,array(
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('log')
            ->add('descripcion',TextType::class,array(
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
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
            'data_class' => 'PrincipalBundle\Entity\FirewallLan'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_firewalllan';
    }


}
