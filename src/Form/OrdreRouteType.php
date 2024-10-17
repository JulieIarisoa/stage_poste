<?php
// src/Form/OrdreRouteType.php
namespace App\Form;

use App\Entity\OrdreRoute;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrdreRouteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('num_or', TextType::class)
            ->add('date_or', DateType::class)
            ->add('duree_deplacement', NumberType::class)
            ->add('nom_detenteur', TextType::class)
            ->add('fonction_detenteur', TextType::class)
            ->add('lieu_depart', TextType::class)
            ->add('date_depart', DateType::class)
            ->add('heure_depart', TimeType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrdreRoute::class,
        ]);
    }
}
