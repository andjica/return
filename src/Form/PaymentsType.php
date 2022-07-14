<?php

namespace App\Form;

use App\Entity\Payments;
use Symfony\Component\Form\AbstractType;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class PaymentsType extends AbstractType
{
    public $image;

    public function __construct()
    {
         $this->image = "blank.png";
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('public_key', TextType::class, [
                'required' => 'Title is required field',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('secret_key', TextType::class, [
                'required' => 'Title is required field',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('image', FileType::class, [
                'data_class' => null,
                'required' => false,
                'empty_data' =>  $this->image,
                'attr' => ['class' => 'form-control'],
                'label' => 'Your image background',
                'label_attr' => ['class'=> 'required'],
                'constraints' => [
                    new File([
                        'maxSize' => '3000k',
                        'mimeTypes' => [
                            'image/*',
                            
                        ],
                        'mimeTypesMessage' => 'Please upload Image, image must be in jpg, jpeg, png format',
                    ])
                ],
            ])
      
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Payments::class,
        ]);
    }
}
