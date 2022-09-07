<?php

namespace App\Form;

use App\Entity\Commentary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class CommentaryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment', TextareaType::class, [
                'label' => 'Commentaire',
                'attr' => [
                    'placeholder' => 'Entrez votre commentaire sur l\'article'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un commentaire.'
                    ]),
                    new Length([
                        'min' => 5,
                        'max' => 2500,
                        'minMessage' => 'Votre commentaire est trop court, il doit comporter un minimum de {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Commenter <i class="fas fa-paper-plane"></i>',
                'label_html' => true,
                'attr' => [
                    'class' => 'd-block col-3 mx-auto btn btn-primary'
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentary::class,
        ]);
    }
}
