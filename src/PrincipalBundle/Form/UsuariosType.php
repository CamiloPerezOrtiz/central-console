<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;

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
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('apellidos', TextType::class,array(
                "label"=>"Last name: ",
                "required"=>false,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('email', EmailType::class,array(
                "label"=>"Email: ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                )
            ))
            ->add('role',ChoiceType::class, array(
                "label"=>"Role: ",
                "required"=>true,
                "attr"=>array(
                    "class"=>"form-control input-sm"
                ),'choices' => array(
                    'Administrator' => 'ROLE_ADMINISTRATOR',
                    'User' => 'ROLE_USER',
                )
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array(
                    'class' => 'password-field',
                    "class"=>"form-control input-sm"
                )),
                'required'=>true,
                'first_options'  => array(
                    'label' => 'Password'
                ),
                'second_options' => array(
                    'label' => 'Repeat Password'
                ),
            ))
            ->add('Save', SubmitType::class,array(
                "attr"=>array(
                    "class"=>"btn btn-success btn-block btn-sm"
                )
            ))
            ->add('Clear', ResetType::class, array(
                'attr' => array(
                    'class' => 'btn btn-danger btn-block btn-sm'),
                )
            )
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
