<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AliasesDescripcionType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ips', TextType::class,array(
                "label"=>"Ip or Port ",
                "required"=>"required",
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('descripciones',TextType::class,array(
                "label"=>"Description about ip or port ",
                "required"=>"required",
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\AliasesDescripcion'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_aliasesdescripcion';
    }


}
