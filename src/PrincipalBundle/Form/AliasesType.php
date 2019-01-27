<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AliasesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class,array(
                "label"=>"Name ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('descripcion', TextType::class,array(
                "label"=>"Description ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('tipo',ChoiceType::class, array(
                "label"=>"Type: ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control",
                    'onChange' => 'mostrar(this.value);'
                ),'choices' => array(
                    'Host' => 'host',
                    'Network(s)' => 'network',
                    'Port(s)' => 'port',
                    'URL (Ips)' => 'url',
                    'URL (Ports)' => 'url_ports',
                    'URL Table (Ips)' => 'urltable',
                    'URL Table (Ports)' => 'urltable_ports'
                )
            ))
            ->add('Save', SubmitType::class,[
                'attr' =>[
                    'class' => 'btn btn-primary btn-block btn-sm'
                ]
            ])
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\Aliases'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_aliases';
    }


}
