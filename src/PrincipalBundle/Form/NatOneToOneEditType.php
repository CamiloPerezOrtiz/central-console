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

class NatOneToOneEditType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estatus')
            ->add('externalSubnetIp',TextType::class,array(
                "label"=>"External subnet IP ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('internalIp')
            ->add('internalIpType',ChoiceType::class, array(
                "label"=>"Type ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
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
                ),
            ))
            ->add('internalAdressMask',TextType::class,array(
                "label"=>"Address ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('destination')
            ->add('destinationType',ChoiceType::class, array(
                "label"=>"Type ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),
                'choices' => array(
                    'Any' => 'any',
                    'Single host or alias' => 'single',
                    'Network' => 'network',
                    'L2TP' => 'l2tp',
                    'PPPoE clients' => 'pppoe',
                    'WAN' => 'wan',
                    'WAN address' => 'wanip',
                    'LAN' => 'lan',
                    'LAN address' => 'lanip'
                ),
            ))
            ->add('destinationAdressMask',TextType::class,array(
                "label"=>"Address ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('descripcion',TextType::class,array(
                "label"=>"Description    ",
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
                    'Enable' => 'enable',
                    'Disable' => 'disable'
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
            'data_class' => 'PrincipalBundle\Entity\NatOneToOne'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_natonetoone';
    }


}
