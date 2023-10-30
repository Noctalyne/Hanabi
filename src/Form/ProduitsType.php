<?php

namespace App\Form;

use App\Entity\Produits;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProduitsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nomProduit')
            ->add('descriptionProduit')
            //Ajouter une image
            ->add('imgProduit',FileType::class,[
                    'mapped' => false, // on ignore l’input lors de la lecture/écriture de l’objet
                    'attr' => [
                        'class' => 'form-file',
                        'accept' => 'image/*' // on décide de n’accepter que les fichiers de type image
                    ],
                    'multiple' => false // on ne rend pas possible la multi-sélection
                ]
            )
            ->add('prixProduit')
            ->add('quantStock');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Produits::class,
        ]);
    }
}
