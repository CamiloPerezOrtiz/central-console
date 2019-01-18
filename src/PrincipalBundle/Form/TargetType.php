<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

class TargetType extends AbstractType
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
                    "class"=>"form-control form-control-sm",
                    'placeholder' => 'Write the list'
                )
            ))
            ->add('domainList',TextareaType::class,array(
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm",
                    'rows' => '4',
                    'placeholder' => 'Write the list'
                )
            ))
            ->add('urlList',TextareaType::class,array(
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm",
                    'rows' => '4',
                    'placeholder' => 'Write the list'
                )
            ))
            ->add('regularExpression',TextareaType::class,array(
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm",
                    'rows' => '4',
                    'placeholder' => 'Write the IP'
                )
            ))
            ->add('redirectMode',ChoiceType::class, array(
                "label"=>"Redirect mode ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control",
                    'onChange' => 'mostrar(this.value);'
                ),'choices' => array(
                    'none' => 'rmod_none',
                    'int error page (enter error message)' => 'rmod_int',
                    'int blank page ' => 'rmod_int_bpg',
                    'int blank image' => 'rmod_int_bim',
                    'ext url err page (enter URL)' => 'rmod_ext_err',
                    'ext url redirect (enter URL)' => 'rmod_ext_rdr',
                    'ext url move  (enter URL)' => 'rmod_ext_mov',
                    'ext url found (enter URL)' => 'rmod_ext_fnd',
                )
            ))
            ->add('redirect',TextareaType::class,array(
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm",
                    'rows' => '2',
                    'placeholder' => 'Write the error message'
                )
            ))
            ->add('descripcion', TextType::class,array(
                "label"=>"Description ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm",
                    'placeholder' => 'Write the description'
                )
            ))
            ->add('log')
            ->add('Save', SubmitType::class,array(
                "attr"=>array(
                    "class"=>"btn btn-primary btn-block btn-sm"
                )
            ))
            ->add('Clear', ResetType::class, array(
                'attr' => array(
                    'class' => 'btn btn-danger btn-block btn-sm'),
                )
            )
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\Target'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_target';
    }


}
