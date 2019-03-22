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

class NatOneToOneType extends AbstractType
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
            ->add('internalAdressMask',TextType::class,array(
                "label"=>"Address ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('maskinternal',ChoiceType::class, array(
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
            ->add('destination')
            ->add('destinationAdressMask',TextType::class,array(
                "label"=>"Address ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('maskDestination',ChoiceType::class, array(
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
