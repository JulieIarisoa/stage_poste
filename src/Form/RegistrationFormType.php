<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
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
            ->add('email')
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
            ])
            ->add('matricule', NumberType::class)
            ->add('nombre_enfant', IntegerType::class)
            ->add('address', TextType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Femme' => 'Femme',
                    'Homme' => 'Homme',
                ],
                'multiple' => false,
                'expanded' => false,  
            ])
            ->add('fonction', TextType::class)
            ->add('situation_familiale', ChoiceType::class, [
                'choices' => [
                    'Célibataire' => 'Célibataire',
                    'En couple' => 'En couple',
                    'Marié' => 'Marié',
                    'Mariée' => 'Mariée',
                    'Divorcé' => 'Divorcé',
                    'Divorcée' => 'Divorcée',
                    'Veuf' => 'Veuf',
                    'Veuve' => 'Veuve',
                ],
                'multiple' => false,
                'expanded' => false,  
            ])
            ->add('cin', TextType::class)
            ->add('taux_journalier', ChoiceType::class, [
                'choices' => [
                    '60 000 Ariary' => 60000,
                    '35 000 Ariary' => 35000,
                    '30 000 Ariary' => 30000,
                    '20 000 Ariary' => 20000,
                ],
                'multiple' => false,
                'expanded' => false,  
            ])
            ->add('titre', ChoiceType::class, [
                'choices' => [
                    'Madame' => 'Madame',
                    'Monsieur' => 'Monsieur',
                    'Mademoiselle' => 'Mademoiselle',
                ],
                'multiple' => false,
                'expanded' => false,  
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Detenteur de compte  ' => 'ROLE_ADMIN',
                    'Service comptable  ' => 'ROLE_USER',
                    'Missionnaire  ' => 'ROLE_MISSIONNAIRE',
                    'Payeur  ' => 'ROLE_PAYEUR',
                ],
                'multiple' => true, // Permettre la sélection multiple
                'expanded' => true,  // Utiliser des boutons radio
                'data' => ['ROLE_USER'], // Attribuer un rôle par défaut
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
