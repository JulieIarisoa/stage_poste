<?php
// src/Form/BstType.php
namespace App\Form;
use App\Entity\Bst;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BstType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('num_bst', TextType::class)
            ->add('id_transport', TextType::class)
            ->add('date_bst', DateType::class)
            ->add('lieu_bst', TextType::class)
            ->add('nom_transporteur', TextType::class)
            ->add('telephone_transport', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Bst::class,
        ]);
    }
}
