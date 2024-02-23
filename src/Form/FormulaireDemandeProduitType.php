<?php

namespace App\Form;

use App\Entity\FormulaireDemandeProduit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class FormulaireDemandeProduitType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('type_produit', ChoiceType::class, [
                'choices' => [
                    'Manga' => 'Manga',
                    'Figurine' => 'Figurine',
                    'Autres *' => 'Autres',
                ],
                'expanded' => true, // Pour afficher les cases à cocher au lieu d'une liste déroulante
                'multiple' => false, // Pour permettre la sélection d'un seul choix
                'label' => 'Réponse du vendeur', // Libellé du champ
            ])

            ->add('autres_types', TextType::class, [
                'attr' => [
                    'class' => 'form-control',
                    'placeholder' => "Préciser",
                    
                ],
                'required' => false,
                // 'mapped' =>false
            ])

            ->add('description_produit', TextareaType::class, [
                'constraints' =>
                new Length([
                    'min' => 0,
                    'maxMessage' => 'Votre demande ne peux exceder {{ limit }} charactères',
                    'max' => 300,
                ]),
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormulaireDemandeProduit::class,
        ]);
    }
}
