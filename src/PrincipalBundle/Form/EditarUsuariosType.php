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

class EditarUsuariosType extends AbstractType
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
                "required"=>true,
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
                    'Administrator' => 'ROLE_ADMINISTRATOR',
                    'User' => 'ROLE_USER',
                )
            ))
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => array('attr' => array(
                    'class' => 'password-field',
                    "class"=>"form-control form-control-sm"
                )),
                'required'=>false,
                'first_options'  => array(
                    'label' => 'Password'
                ),
                'second_options' => array(
                    'label' => 'Repeat Password'
                ),
            ))
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
    }
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\EditarUsuarios'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_editarusuarios';
    }


}
