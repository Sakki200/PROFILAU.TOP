<?php

namespace App\Form;

use App\Entity\JobOffer;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotNull;

class JobOfferType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'A postuler' => 'send',
                    'En attente' => 'waiting',
                    'Entretien' => 'interview',
                    'Refusé' => 'rejected',
                    'Accepté' => 'accepted'
                ],
                'label' => "Statut de la demande :"
            ])
            ->add('title', TextType::class, [
                'label' => "Nom de votre offre :"
            ])
            ->add('company', TextType::class, [
                'label' => "Nom de la compagnie :"
            ])
            ->add('link', TextType::class, [
                'label' => "Lien de l'offre :",
                'required' => false
            ])
            ->add('location', TextType::class, [
                'label' => "Lieu de l'offre :",
                'required' => false
            ])
            ->add('salary', TextType::class, [
                'label' => "Salaire :",
                'required' => false
            ])
            ->add('contactPerson', TextType::class, [
                'label' => "Nom du contact :",
                'required' => false
            ])
            ->add('contactEmail', TextType::class, [
                'label' => "Email de ce contact :",
                'required' => false
            ])
            ->add('applicationDate', DateType::class, [
                'label' => "Date de postulation :",
                'required' => false
            ])
            ->add('submit', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => JobOffer::class,
        ]);
    }
}
