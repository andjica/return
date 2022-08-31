<?php

namespace App\Form;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchReturnType extends AbstractType
{
    public $requestStack;
    public $doctrine;

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine) {
        //$this->reCaptcha = $reCaptcha;
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;

    }
   

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('user_email', TextType::class, [
                'required' => 'Email is required field',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('webshop_order_id', TextType::class, [
                'required' => 'Nummber of order id is required field',
                'attr' => ['class' => 'form-control'],
            ]);
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}