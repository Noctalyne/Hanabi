<?php

namespace App\Form;

use App\Entity\Adresses;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numAdrss')
            ->add('rueAdrss')
            ->add('complementAdrss')
            ->add('villeAdrss')
            ->add('codePostalAdrss')
            ->add('paysAdrss')
            // ->add('idClientAdresses')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adresses::class,
        ]);
    }
}
