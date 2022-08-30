<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Status;
use App\Entity\Country;
use App\Entity\Returns;
use App\Entity\ReturnImages;
use App\Entity\ReturnVideos;
use App\Entity\EmailTemplate;
use App\Entity\ReasonSettings;
use App\Entity\ResellerAddress;
use App\Entity\ResellerShipments;
use App\Entity\ResellerShipmentItems;
use App\Repository\ReturnsRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ResellerShipmentItemsRepository;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Session\Attribute\AttributeBag;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ReturnEditType extends AbstractType
{
    public $requestStack;
    public $doctrine;
    public $returnsRepository;
    public $orderId;
    public $userEmail;
    public $slugger;
    public $params;
    public $return;
    public $webShopOrderId;
    public $status;
    

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine) {
        // $this->returns = $returns;
        // return dd($returns);
       
        $this->requestStack = $requestStack;
        $currentRequest = $this->requestStack->getCurrentRequest();
        $currentRoute = $currentRequest->getRequestUri();
        $this->doctrine = $doctrine;
        //find return Id 
        $returnId = (int) filter_var($currentRoute, FILTER_SANITIZE_NUMBER_INT);
        $this->return = $this->doctrine->getRepository(Returns::class)->findOneBy(['id' => $returnId]);
        $this->webShopOrderId = $this->return->getWebshopOrderId();
        
        
    }   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('order_id', TextType::class, [
            'required' => 'Order is required field',
            'attr' => ['class' => 'form-control'],
            'empty_data' =>$this->webShopOrderId,
            'data' => $this->webShopOrderId,
            'mapped' => false
            ])
            ->add('status', EntityType::class, [
            'data' => $this->return->getStatus(),
            'class' => Status::class,
            'choice_label' => function ($status) {
                return $status->getName();
            },
            'attr' => ['class' => 'form-control'],
           
            ])
            // ->add('items', TextType::class, [
            //     'required' => '',
            //     'attr' => ['class' => 'form-control'],
            //     'data' => $this->return->getItem(),
            //     'mapped' => false
            // ])
          
            ->add('reasons', TextType::class, [
                'required' => 'Reason is required field',
                'attr' => ['class' => 'form-control'],
                'empty_data' =>$this->return->getReason(),
                'data' => $this->return->getReason(),
                'mapped' => false
            ])
          
            ->add('reference', TextType::class, [
                'required' => 'References is required field',
                'attr' => ['class' => 'form-control'],
                'data' =>  $this->return->getReference(),
                'mapped' => false
            ])
            ->add('client_name', TextType::class, [
                'required' => 'Order id is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->return->getClientName(),
                'mapped' => false
            ])
            ->add('client_email', TextType::class, [
                'required' => 'Client email is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->return->getUserEmail(),
                'mapped' => false
            ])
           
            ->add('company_name', TextType::class, [
                'required' => 'Company name is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->return->getCompanyName(),
                'mapped' => false
            ])
            ->add('street', TextType::class, [
                'required' => 'Strret is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->return->getStreet(),
                'mapped' => false
            ])
            ->add('postal_code', TextType::class, [
                'required' => 'Postal code is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->return->getPostCode(),
                'mapped' => false
            ]);
          
           
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Returns',
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'return',
        ]);
    }
}