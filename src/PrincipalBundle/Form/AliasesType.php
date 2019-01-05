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
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('tipo', TextType::class,array(
                "label"=>"Type ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('id_aliases_descripcion', CollectionType::class,[
                "label"=>false,
                'entry_type' => AliasesDescripcionType::class,
                'entry_options' => [
                    'label' => false
                ],
            'by_reference' => false,
            'allow_add' => true,
            'allow_delete' => true
            ])
            ->add('Save', SubmitType::class,[
                'attr' =>[
                    'class' => 'btn btn-success btn-sm'
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
