<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class UsuariosType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre', TextType::class,array(
                "label"=>"Name: ",
                "required"=>"required",
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('apellidos', TextType::class,array(
                "label"=>"Last name: ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('email', EmailType::class,array(
                "label"=>"Email: ",
                "required"=>"required",
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('role',ChoiceType::class, array(
                "label"=>"Role: ",
                "required"=>"required",
                "attr"=>array(
                    "class"=>"form-control"
                ),'choices' => array(
                    'Super user' => 'ROLE_SUPERUSER',
                    'Administrator' => 'ROLE_ADMINISTRADOR',
                    'User' => 'ROLE_USER',
                )
            ))
            ->add('password', PasswordType::class,array(
                "label"=>"Password: ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control form-control-sm"
                )
            ))
            ->add('Save', SubmitType::class,array(
                "attr"=>array(
                    "class"=>"btn btn-primary btn-block"
                )
            ))
        ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\Usuarios'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_usuarios';
    }


}
