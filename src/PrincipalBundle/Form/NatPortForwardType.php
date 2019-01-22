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
            ))
            ->add('sourceAdvancedInvertMatch',CheckboxType::class,array(
                "label"=>"Source ",
            ))
            ->add('sourceAdvancedType',ChoiceType::class, array(
                "label"=>"Type: ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                ),
                'choices' => array(
                    'Any' => 'any',
                    'Single host or alias' => 'single',
                    'Network' => 'network',
                    'L2TP' => 'l2tp',
                    'PPPoE clients' => 'pppoe',
                    'WAN net' => 'wan',
                    'WAN address' => 'wanip',
                    'LAN net' => 'lan',
                    'LAN address' => 'lanip'
                )
            ))
            ->add('sourceAdvancedAdressMask',TextType::class,array(
                "label"=>"Address ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('sourceAdvancedFromPort',ChoiceType::class, array(
                "label"=>"From port",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                ),
                'choices' => array(
                    'Any' => 'any',
                    'Single host or alias' => 'single',
                    'Network' => 'network',
                    'L2TP' => 'l2tp',
                    'PPPoE clients' => 'pppoe',
                    'WAN net' => 'wan',
                    'WAN address' => 'wanip',
                    'LAN net' => 'lan',
                    'LAN address' => 'lanip'
                )
            ))
            ->add('sourceAdvancedCustom',TextType::class,array(
                "label"=>"Custom ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('sourceAdvancedToPort',ChoiceType::class, array(
                "label"=>"To port",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                ),
                'choices' => array(
                    'Any' => 'any',
                    'Single host or alias' => 'single',
                    'Network' => 'network',
                    'L2TP' => 'l2tp',
                    'PPPoE clients' => 'pppoe',
                    'WAN net' => 'wan',
                    'WAN address' => 'wanip',
                    'LAN net' => 'lan',
                    'LAN address' => 'lanip'
                )
            ))
            ->add('sourceAdvancedCustomToPort',TextType::class,array(
                "label"=>"Custom ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('destinationInvertMatch')
            ->add('destinationType',ChoiceType::class, array(
                "label"=>"Type: ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                ),
                'choices' => array(
                    'Any' => 'any',
                    'Single host or alias' => 'single',
                    'Network' => 'network',
                    'This firewall (self)' => '(self)',
                    'PPPoE clients' => 'pppoe',
                    'WAN net' => 'wan',
                    'WAN address' => 'wanip',
                    'LAN net' => 'lan',
                    'LAN address' => 'lanip'
                )
            ))
            ->add('destinationAdressMask')
            ->add('destinationRangeFromPort')
            ->add('destinationRangeCustom')
            ->add('destinationRangeToPort')
            ->add('destinationRangeCustomToPort')
            ->add('redirectTargetIp')
            ->add('redirectTargetPort')
            ->add('redirectTargetPortCustom')
            ->add('descripcion')
            ->add('natReflection')
            ->add('filterRuleAssociation')
            ->add('grupo')
            ->add('Save',SubmitType::class,array(
                "attr"=>array("
                    class"=>"btn btn-primary btn-sm btn-block"
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
