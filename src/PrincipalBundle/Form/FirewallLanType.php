<?php

namespace PrincipalBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FirewallLanType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('action')
            ->add('estatus')
            ->add('interface')
            ->add('adressFamily')
            ->add('protocolo')
            ->add('icmSubtypes')
            ->add('sourceInvertMatch')
            ->add('sourceType')
            ->add('sourceAddresMask')
            ->add('sourcePortRangeFrom')
            ->add('sourcePortRangeCustom')
            ->add('sourcePortRangeTo')
            ->add('sourcePortRangeCustomTo')
            ->add('destinationInvertMatch')
            ->add('destinationType')
            ->add('destinationAddresMask')
            ->add('destinationPortRangeFrom')
            ->add('destinationPortRangeCustom')
            ->add('destinationPortRangeTo')
            ->add('destinationPortRangeCustomTo')
            ->add('log')
            ->add('descripcion')
            ->add('grupo')
            ->add('posicion')
        ;
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'PrincipalBundle\Entity\FirewallLan'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'principalbundle_firewalllan';
    }


}
