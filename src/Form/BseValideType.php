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
        ->add('nom_detenteur', TextType::class)
        ->add('prenom_detenteur', TextType::class)
        ->add('fonction_detenteur', TextType::class)
        ->add('etat_validation_or', ChoiceType::class, [
            'choices' => [
                'Accepter' => 'accepte',
                'Refuser' => 'refuse',
            ],
            'multiple' => false,
            'expanded' => false
        ])
        ->add('etat_payment_or', TextType::class,[
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
