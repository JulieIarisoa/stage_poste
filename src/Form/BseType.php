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
            ->add('destination', TextType::class)
            ->add('motif', TextType::class)
            ->add('date_bse', DateType::class, [
                'data'=> new \DateTime(),
                'attr'=> ['readonly'=> true],
                'disabled'=> true,
            ])
            ->add('lieu_bse', TextType::class)
            ->add('duree_mission', NumberType::class)
            ->add('matricule', NumberType::class,[
                    'data' => $options['id'],
                    'attr' => ['readonly' => true],
                    'disabled' => true,
                ])
            ->add('etat_validation_or', TextType::class,[
                'data' => 'en_attente',
                'attr' => ['readonly'=>true],
                'disabled' => true,
            ])
            ->add('etat_payment_or', TextType::class,[
                'data' => 'en_attente',
                'attr' => ['readonly' => true],
                'disabled' => true,
            ])
            ->add('etat', TextType::class,[
                'data' => 'Ordre de route',
                'attr' => ['readonly' => true],
                'disabled' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'id' => null,
        ]);
    }
}
