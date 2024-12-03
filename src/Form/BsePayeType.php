<?php
// src/Form/BstPayeType.php
namespace App\Form;
use App\Entity\Bse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BsePayeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('payeur_or', TextType::class)
        ->add('code_postale_payement_or', TextType::class)
        ->add('date_payement_or', DateType::class,[
            'data' => new \DateTime(),
        ])
        ->add('etat_payment_or', TextType::class,[
            'data' => 'paye'
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bse::class,
        ]);
    }
}
