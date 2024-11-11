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

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('matricule', NumberType::class)
            ->add('nom', TextType::class)
            ->add('prenom', TextType::class)
            ->add('nombre_enfant', IntegerType::class)
            ->add('address', TextType::class)
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
            ->add('date_cin', DateType::class)
            ->add('taux_journalier', NumberType::class)
            ->add('titre', ChoiceType::class, [
                'choices' => [
                    'Madame' => 'Madame',
                    'Monsieur' => 'Monsieur',
                    'Mademoiselle' => 'Mademoiselle',
                ],
                'multiple' => false,
                'expanded' => false,  
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
