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

class BstOrType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('destination', TextType::class)
            ->add('motif', TextType::class)
            ->add('date_bse', DateType::class, [
                'data'=> new \DateTime(),
                'attr'=> ['readonly'=> true],
            ])
            ->add('lieu_bse', TextType::class)
            ->add('duree_mission', NumberType::class)
            ->add('matricule', NumberType::class,[
                    'data' => $options['id'],
                    'attr'=> ['readonly'=> true],
                ])
            ->add('etat_validation', TextType::class,[
                'data' => 'en_attente',
                'attr'=> ['readonly'=> true],
            ])
            ->add('etat_payment_or', TextType::class,[
                'data' => 'en_attente',
                'attr'=> ['readonly'=> true],
            ])
            ->add('etat_payment_bst', TextType::class,[
                'data' => 'en_attente',
                'attr'=> ['readonly'=> true],
            ])
            ->add('etat', TextType::class,[
                'data' => 'Ordre de route avec BST',
                'attr'=> ['readonly'=> true],
            ])
            ->add('nom_chaufeur', TextType::class)
            ->add('prenom_chafeur', TextType::class)
            ->add('tel_transporteur', TextType::class)
            ->add('Coperative', TextType::class)
            ->add('id_transport', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'id' => null,
        ]);
    }
}
