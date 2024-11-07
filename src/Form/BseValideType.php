<?php
// src/Form/BstValideType.php
namespace App\Form;
use App\Entity\Bse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BseValideType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('num_bse', NumberType::class)
        ->add('destination', TextType::class)
        ->add('motif', TextType::class)
        ->add('date_bse', DateType::class)
        ->add('depense_engage', NumberType::class)
        ->add('matricule', NumberType::class)
        ->add('etat_validation', ChoiceType::class, [
            'choices' => [
                'accepte' => 'Accepter',
                'refuse' => 'Refuser',
            ],
            'multiple' => true, // Permettre la sélection multiple
            'expanded' => true,  // Utiliser des boutons radio
        ])
        ->add('etat_payment', TextType::class,[
            'data' => 'en_attente'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bse::class,
        ]);
    }
}
