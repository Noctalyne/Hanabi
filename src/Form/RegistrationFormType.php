<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder


            // ->add('email', EmailType::class,
            // [
            //     'attr'=>['class'=>'register'],
            //     'label'=> 'Saisir votre Email : ',
            //     'required'=>true
            // ])


            ->add('email', EmailType::class, [
                'attr' => [
                    // 'label' => 'email : ',
                    'class' => 'form-control register',
                    'placeholder' => "Saisir votre Email.."
                ],
                'required' => true
            ])

            // ->add('username',TextType::class, [
            //         'attr' => ['class' => 'register'],
            //         'label' => 'Saisir votre nom : ',
            //         'required' => true
            // ])

            ->add('username', TextType::class, [
                'attr' => [
                    'class' => 'form-control register',
                    'placeholder' => "Entrez un nom d'utilisateur.."
                ],
                'required' => true
            ])


            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mot de passes doivent être identique.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'constraints' => [new NotBlank(['message' => 'Vous devez entrer un mot de passe..'])],
                // new Length([
                //         'min' => 6,
                //         'minMessage' => 'Votre mot de passe doit faire {{ limit }} caractères minimum',
                //         // max length allowed by Symfony for security reasons
                //         'max' => 60,
                //     ]),
                // 'autocomplete' => 'new-password',     'placeholder' => "Saisir un mot de passe.."'placeholder' => "Saisir à nouveau"
                'first_options'  => ['label' => 'Password : ',],
                'second_options' => ['label' => 'Repeat Password',],
            ])


            //     ->add('plainPassword', PasswordType::class, [
            //         // instead of being set onto the object directly,
            //         // this is read and encoded in the controller
            //         'mapped' => false,
            //         'attr' => [
            //             'autocomplete' => 'new-password',
            //             'class' =>'form-control',
            //             'placeholder' => "Rensseigner un mot de passe.."
            //         ],
            //         'constraints' => [
            //             new NotBlank([
            //                 'message' => 'Vous devez entrer un mot de passe..',
            //             ]),
            //             new Length([
            //                 'min' => 6,
            //                 'minMessage' => 'Votre mot de passe doit faire {{ limit }} caractères minimum',
            //                 // max length allowed by Symfony for security reasons
            //                 'max' => 60,
            //             ]),
            //         ],
            //     ])
            // ;
            ->add('agreeTerms', CheckboxType::class, [
                'attr' => [
                    'type' => "checkbox",
                    'class' => "form-check-input m-1"
                ],
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les CGU.',
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
