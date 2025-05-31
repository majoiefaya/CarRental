<?php

namespace App\Form;

use App\Entity\Vente;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Voiture;
use App\Entity\Client;


class VenteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('montant')
            ->add('DateVente')
            ->add('voiture',EntityType::class,[
                'class'=>Voiture::class,
                'choice_label'=>'numserie',
            ])
            ->add('client',EntityType::class,[
                'class'=> Client::class,
                'choice_label'=>'nom'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vente::class,
        ]);
    }
}
