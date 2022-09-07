<?php

namespace App\Form;

use App\Entity\Article;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\Image;

class ArticleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de votre article',
                'attr' => [
                    'placeholder' => 'Entrez un titre'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un titre.'
                    ]),
                ],
            ])
            ->add('subtitle', TextType::class, [
                'label' => 'Sous-titre de votre article',
                'attr' => [
                    'placeholder' => 'Entrez un sous-titre'
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez saisir un sous-titre.'
                    ]),
                ],
            ])
            ->add('content', TextareaType::class, [
                'label' => false,
                'attr' => [
                    'placeholder' => 'Entrez le contenu de votre article'
                ],
            ])
            ->add('category', EntityType::class, [
                'label' => 'Catégorie',
                'class' => Category::class,
                'choice_label' => 'name',
                'attr' => [
                    'placeholder' => 'Entrez le contenu de votre article'
                ],
            ])
            ->add('photo', FileType::class, [
                'label' => 'Photo de l\'article',
                'data_class' => null,
                'attr' => [
                    'value' => $options['photo'],
                    'data-default-file' => $options['photo'],
                ],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Les types de fichiers autorisés sont : .jpeg et .png'
                    ]),
                ],
            ])
            ->add('submit', SubmitType::class, [
                'attr' => [
                    'class' => 'd-block col-3 mx-auto btn btn-primary'
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Article::class,
            'allow_file_upload' => true,
            'photo' => null,
        ]);
    }
}
