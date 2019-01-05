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

class AclGroupsType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('estatus')
            ->add('nombre',TextType::class,array(
                "label"=>"Name ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm",
                    'placeholder' => 'Write the name'
                )
            ))
            ->add('cliente',TextareaType::class,array(
                "label"=>"Client ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control",
                    'rows' => '2',
                    'placeholder' => 'Write the IP'
                )
            ))
            ->add('notAllowIp')
            ->add('redirect',TextareaType::class,array(
                "label"=>"Redirect ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control",
                    'rows' => '2',
                    'placeholder' => 'Write the redirect option'
                )
            ))
            ->add('redirectMode',ChoiceType::class, array(
                "label"=>"Redirect mode: ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                ),
                'choices' => array(
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
            ->add('descripcion',TextType::class,array(
                "label"=>"Description ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control form-control-sm",
                    'placeholder' => 'Write the description'
                )
            ))
            ->add('log')
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
            'data_class' => 'PrincipalBundle\Entity\AclGroups'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_aclgroups';
    }


}
