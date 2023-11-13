<?php

namespace App\Form;

use App\Entity\Banniere;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BanniereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomBanniere')

            ->add('premiereImage',FileType::class,
                [
                    'required' => false, // on rend le champs non obligatoire
                    'mapped' => false, // on ignore l’input lors de la lecture/écriture de l’objet
                    'attr' => [
                        'class' => 'form-file',
                        'accept' => 'image/*', // on décide de n’accepter que les fichiers de type image
                        // 'max-width' =>"740px",
                        // 'max-height'=> "180px"
                    ],
                    'multiple' => false // on ne rend pas possible la multi-sélection
                ]
            )

            ->add('deuxiemeImage',FileType::class,
                [
                    'required' => false, // on rend le champs non obligatoire
                    'mapped' => false, // on ignore l’input lors de la lecture/écriture de l’objet
                    'attr' => [
                        'class' => 'form-file',
                        'accept' => 'image/*', // on décide de n’accepter que les fichiers de type image
                        // 'min-width' =>"740px",
                        // 'max-width' =>"740px",
                        // 'min-height'=> "180px",
                        // 'max-height'=> "180px"
                    ],
                    'multiple' => false // on ne rend pas possible la multi-sélection
                ]
            )

            ->add('troisiemeImage',FileType::class,
                [
                    'required' => false, // on rend le champs non obligatoire
                    'mapped' => false, // on ignore l’input lors de la lecture/écriture de l’objet
                    'attr' => [
                        'class' => 'form-file',
                        'accept' => 'image/*', // on décide de n’accepter que les fichiers de type image
                        // 'max-width' =>"740px",
                        // 'max-height'=> "180px"
                    ],
                    'multiple' => false // on ne rend pas possible la multi-sélection
                ]
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Banniere::class,
        ]);
    }
}
