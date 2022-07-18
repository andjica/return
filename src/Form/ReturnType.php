<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Status;
use App\Entity\Country;
use App\Entity\Returns;
use App\Entity\ReasonSettings;
use App\Entity\ResellerAddress;
use App\Entity\ResellerShipmentItems;
use App\Entity\ResellerShipments;
use App\Repository\ResellerShipmentItemsRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReturnType extends AbstractType
{
    public $requestStack;
    public $doctrine;
    public $resellerAddress;
    public $order;
    public $shipmentsItems;
    

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine) {
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
        
        $session = $this->requestStack->getSession();
        $webshopOrderId = $session->get('webshop_order_id');
        $email = $session->get('user_email');
        
        $this->order = $this->doctrine->getRepository(ResellerShipments::class)->findOneBy(['webshopOrderId'=>$webshopOrderId]);
        $this->resellerAddress = $this->doctrine->getRepository(ResellerAddress::class)->findOneBy(['id'=>$this->order->getDeliveryAddressId()]);
        $this->shipmentsItems = $this->doctrine->getRepository(ResellerShipmentItems::class)->findBy(['shipment_id'=> $this->order->getId()]);
        
        

    }   
    

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('order_id', TextType::class, [
            'required' => 'Order is required field',
            'attr' => ['class' => 'form-control', 'disabled'=>'disabled'],
            'data' => $this->order->getId()
            ])
            ->add('status', EntityType::class, [
            'class' => Status::class,
            'choice_label' => function ($status) {
                return $status->getName();
            },
            'attr' => ['class' => 'form-control']
            ])
            //pause here
            ->add('items', EntityType::class, [
                'class'=> ResellerShipmentItems::class,
                'choice_label' => function ($items) {
                    return $items->getTitle();
                },
                'attr' => ['class' => 'form-control']
            ])
            ->add('countries', EntityType::class, [
                'class' => Country::class,
                'choice_label' => function ($country) {
                    return $country->getName();
            },
                'attr' => ['class' => 'form-control']
            ])
            ->add(
                'shablon', ChoiceType::class, 
                [
                    'choices' => [
                        'Admin reason' => 'admin-r',
                        'User reason' => 'user-r',
                ],
                'expanded' => true,
                'choice_attr' => [
                    'Admin reason' => ['class' => 'sha', 'checked'=>'checked'],
                    'User reason' => ['class' => 'sha'],
                ],
            ])
            ->add('reasons', EntityType::class, [
                'class' => ReasonSettings::class,
                'choice_label' => function ($reasons) {
                    return $reasons->getName();
            },
                'attr' => ['class' => 'form-control']
            ])
            ->add('quantity', TextType::class, [
                'required' => 'Order id is required field',
                'attr' => ['class' => 'form-control'],
            ])
            ->add('reference', TextType::class, [
                'required' => 'References is required field',
                'attr' => ['class' => 'form-control', 'disabled'=>'disabled'],
                'data' => $this->order->getReference()
            ])
            ->add('client_name', TextType::class, [
                'required' => 'Order id is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->resellerAddress->getName()
            ])
            ->add('client_email', TextType::class, [
                'required' => 'Client email is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->resellerAddress->getEmail()
            ])
           
            ->add('company_name', TextType::class, [
                'required' => 'Order id is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->resellerAddress->getCompanyName()
            ])
            ->add('street', TextType::class, [
                'required' => 'Order id is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->resellerAddress->getStreet()
            ])
            ->add('postal_code', TextType::class, [
                'required' => 'Postal code is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->resellerAddress->getPostalCode()
            ])
          
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

                // $form = $event->getForm();
                
                // $request = $this->requestStack->getCurrentRequest();
                // $all = $request->request->all();
                
                // $email = $all['search_return']['user_email'];
                // // return dd($request);
                // $webshopOrderId = $all['search_return']['webshop_order_id'];
                // $order = $this->doctrine->getRepository(ResellerShipments::class)->findOneBy(['webshopOrderId'=>$webshopOrderId]);
                
                // if(!$order)
                // {
                //     $form->get('webshop_order_id')->addError(new FormError('There is no order with id: '.$webshopOrderId));              
                // }
                // else
                // {
                //     $customerId = $order->getCustomerId();
                    
                //     $customer = $this->doctrine->getRepository(Users::class)->findOneBy(['id' => $customerId]);
                    
                //     $customeremail = $customer->getUsername();
                    

                //     if($email != $customeremail)
                //     {
                //         $form->get('user_email')->addError(new FormError('There user with email : '.$email));              
                //     }

                // }
                

            })
           
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Status::class,
        ]);
    }
}