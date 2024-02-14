<?php

namespace App\Form;

use App\Entity\FormulaireDemandeProduit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ReponseFormulaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

        // Permet d accepter ou non la demande
            ->add('reponseDemande', ChoiceType::class, [
                'placeholder' => 'Choisir',
                'choices' => [
                        'Oui' => 'Accepter',
                        'En attente' => 'attente',
                        'Non' => 'Refuser',
                ],
                'required' => false, // Pour rendre le champ facultatif
                // 'expanded' => true, // Pour afficher les cases à cocher au lieu d'une liste déroulante
                'multiple' => false, // Pour permettre la sélection d'un seul choix
                'data' => 'Attente',
            ]);;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => FormulaireDemandeProduit::class, //
        ]);
    }
}
