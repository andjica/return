<?php

namespace App\Form;

use App\Entity\Returns;
use App\Entity\ResellerShipments;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

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
                'required' => 'Order id is required field',
                'attr' => ['class' => 'form-control'],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

                $form = $event->getForm();
                
                $request = $this->requestStack->getCurrentRequest();
                $all = $request->request->all();
                
                $email = $all['search_return']['user_email'];
                // return dd($request);
                $webshopOrderId = $all['search_return']['webshop_order_id'];
                $order = $this->doctrine->getRepository(ResellerShipments::class)->findOneBy(['webshopOrderId'=>$webshopOrderId]);
               
                if(!$order)
                {
                    $form->get('webshop_order_id')->addError(new FormError('There is no order with id: '.$webshopOrderId));              
                }
            

            })
           
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}