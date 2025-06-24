<?php

namespace App\Form;

use App\Entity\Contact;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ContactForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email')
            ->add('firstname', TextType::class, ['label' => 'Prénom'])
            ->add('lastname', )
            ->add('subject', ChoiceType::class, [
                'label' => 'Sujet',
                'choices' => [
                    'Problème technique' => 'bug',
                    'Je n\'arrive pas à me connecter' => 'authentification_connexion',
                    'Je n\'arrive pas à créer un compte' => 'authentification_creation',
                    'Suggestion de fonctionnalité' => 'suggestion',
                    'Question sur l\'utilisation' => 'question',
                    'Information sur les offres' => 'offre',
                    'Facturation ou paiement' => 'facturation',
                    'Sécurité / confidentialité' => 'sécurité',
                    'Autre' => 'autre',
                ],
                'placeholder' => '-- Choisissez un sujet --',
            ])
            ->add('message', TextareaType::class, [
                'label' => 'Message',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le message ne peut pas être vide.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, ['label'=>'Envoyer']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Contact::class,
        ]);
    }
}
