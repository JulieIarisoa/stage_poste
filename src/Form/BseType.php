<?php
// src/Form/EmployeType.php
namespace App\Form;
use App\Entity\Bse;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('num_bse', NumberType::class)
            ->add('destination', TextType::class)
            ->add('motif', TextType::class)
            ->add('date_bse', DateType::class)
            ->add('depense_engage', NumberType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bse::class,
        ]);
    }
}
