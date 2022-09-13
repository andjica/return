<?php

namespace App\Form;

use App\Entity\Common\Country;
use App\Entity\Returns\ReturnSettings;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

class ReturnSettingsType extends AbstractType
{

    public $logo; 
    public $background;


    public function __construct(ManagerRegistry $doctrine)
    {
        $returnSetting = $doctrine->getRepository(ReturnSettings::class)->findOneBy([]);

        
        if($returnSetting)
        {
            $this->logo = $returnSetting->getImageLogo();
            $this->background = $returnSetting->getImageBackground();
        }
        else
        {

            $this->logo = "blank.png";
            $this->background = "blank.png";
        }

    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('image_logo', FileType::class, [
                'data_class' => null,
                'required' => false,
                'empty_data' =>  $this->logo,
                'attr' => ['class' => ''],
                'label' => 'Your image logo',
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
            ->add('image_background', FileType::class, [
                'data_class' => null,
                'required' => false,
                'empty_data' =>  $this->background,
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
            ->add('title', TextType::class, [
                'required' => 'Title is required field',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('status', ChoiceType::class, [
                'choices'  => [
                    'Active' => 1,
                    'Inactive' => 0,
                ],
                'attr' => ['class'=>'form-control']
            ])
            ->add('return_period', ChoiceType::class, [
                'choices'  => [
                    '14' => 14,
                    '18' => 18,
                ],
                'attr' => ['class'=> 'form-control']
            ])
            // ->add('countries', EntityType::class, [
            //     'class' => Country::class,
            //     // 'empty_data' => function () {
            //     //     $returnSetting = $this->doctrine->getRepository(ReturnSettings::class)->findOneBy([]);
            //     //     $currentCountryName = $returnSetting->getCountry()->getName();
            //     //     $currentCountryId = $returnSetting->getCountry()->getId();
            //     //     return dd($currentCountryName());
            //     // },
            //     'choice_label' => function ($country) {
            //         return $country->getName();
            // },
            //     'attr' => ['class' => 'form-control'],
            //     'mapped' => false,
            //     'required' => 'Country is required field',
            // ])
            ->add('street', TextType::class, [
                'required' => 'Street is required field',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('postcode', TextType::class, [
                'required' => 'Post code is required field',
                'attr' => ['class' => 'form-control'],
            ])
            
          
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ReturnSettings::class,
        ]);
    }
}
