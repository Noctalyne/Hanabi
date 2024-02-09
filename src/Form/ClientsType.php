<?php

namespace App\Form;

// use App\Entity\Clients;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ClientsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
            'attr' => [
                'placeholder' => "Nouveau"
                ],
            ])

            ->add('prenom', TextType::class,[
                'attr' => [
                    'placeholder' => "Nouveau"
                    ],
            ])
            ->add('telephone', TextType::class,[
                'attr' => [
                    'placeholder' => "Nouveau"
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
