<?php

namespace App\Form;

use App\Entity\Marque;
use App\Entity\Modele;
use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque',EntityType::class,[
                'class'=> Marque::class,
                'choice_label'=>'libelle'
            ])
            ->add('modele',EntityType::class,[
                'class'=> Modele::class,
                'choice_label'=>'libelle'
            ])
            ->add('numserie')
            ->add('Puissance')
            ->add('Annee')
            ->add('Etat')
            ->add('Siege')
            ->add('Interieur')
            ->add('Motorisation')
            ->add('Kilometrage')
            ->add('DateMiseCircu')
            ->add('DatePubli')
            ->add('couleur')
            ->add('prix')
            ->add('image', FileType::class, ['mapped'=>false,'attr'=>['name'=>'image','required'=>false]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }

}
