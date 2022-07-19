<?php

namespace App\Form;

use App\Entity\Users;
use App\Entity\Status;
use App\Entity\Country;
use App\Entity\Returns;
use App\Entity\ReturnImages;
use App\Entity\ReasonSettings;
use App\Entity\ResellerAddress;
use App\Entity\ResellerShipments;
use App\Entity\ResellerShipmentItems;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\ChoiceList;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Repository\ResellerShipmentItemsRepository;
use App\Repository\ReturnsRepository;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ReturnType extends AbstractType
{
    public $requestStack;
    public $doctrine;
    public $resellerAddress;
    public $order;
    public $shipmentsItems;
    public $returnsRepository;
    public $orderId;
    public $userEmail;

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine,ReturnsRepository $returnsRepository) {
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
        
        $session = $this->requestStack->getSession();
        $webshopOrderId = $session->get('webshop_order_id');
        $email = $session->get('user_email');
        
        $this->order = $this->doctrine->getRepository(ResellerShipments::class)->findOneBy(['webshopOrderId'=>$webshopOrderId]);
        $this->resellerAddress = $this->doctrine->getRepository(ResellerAddress::class)->findOneBy(['id'=>$this->order->getDeliveryAddressId()]);
        $this->shipmentsItems = $this->doctrine->getRepository(ResellerShipmentItems::class)->findBy(['shipment_id'=> $this->order->getId()]);
        
        $this->orderId = $webshopOrderId;
        $this->userEmail = $email;
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
            ->add('items', ChoiceType::class, [
                'choices'=>  $items = $this->shipmentsItems,
                'choice_label' => function($items, $key, $index) {
                  
                    return $items->getTitle();
                    
                },
                'choice_value' => function($items) {
                  
                    return $items ? $items->getId() : '';

                    
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
                'data' =>  $this->order->getReference()
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
                'required' => 'Company name is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->resellerAddress->getCompanyName()
            ])
            ->add('street', TextType::class, [
                'required' => 'Strret is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->resellerAddress->getStreet()
            ])
            ->add('postal_code', TextType::class, [
                'required' => 'Postal code is required field',
                'attr' => ['class' => 'form-control'],
                'data' => $this->resellerAddress->getPostalCode()
            ])
          
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

                $request = $this->requestStack->getCurrentRequest();
                $form = $event->getForm();
                $this->returnsRepository = new Returns();

               
                $all =  $all = $request->request->all();
                return dd($all);
                $shablon = $all['return']['shablon'];

                //find status
                $statusId = $all['return']['status'];
                $status = $this->doctrine->getRepository(Status::class)->findOneBy(['id'=>$statusId]);
               
                $clientEmail = $all['return']['client_email'];
                $clientName = $all['return']['client_name'];
                $companyName = $all['return']['company_name'];
                $street = $all['return']['street'];
                $postalCode = $all['return']['postal_code'];

                $this->returnsRepository->setReference($this->order->getReference());
                $this->returnsRepository->setWebshopOrderId($this->orderId);
                $this->returnsRepository->setStatus($status);
                $this->returnsRepository->setUserEmail($this->userEmail);
                $this->returnsRepository->setClientEmail($clientEmail);
                $this->returnsRepository->setClientName($clientName);
                $this->returnsRepository->setCompanyName($companyName);
                $this->returnsRepository->setStreet($street);
                $this->returnsRepository->setPostCode($postalCode);
                
                if($shablon == "admin-r")
                {
                    $reasonId = $all['return']['reasons'];
                    $reasons = $this->doctrine->getRepository(ReasonSettings::class)->findOneBy(['id'=>$reasonId]);
                    
                    $this->returnsRepository->setReason($reasons->getName());
                }
                else
                {
                    $reasonUser = $request->request->get('reason-user');
                    
                    $photos = $request->request->all('photos');
                    
                    if($photos)
                    {
                        foreach ($photos as $photo) {


                            $savephoto = new ReturnImages();
                            $savephoto->setReturnId($this->returnsRepository->getId());
                            
                            $savephoto->setCreatedAt(new \DateTime());
                            $entityManagerPhoto = $this->doctrine->getManager();
                            $entityManagerPhoto->persist($savephoto); 
        
                            
                            $originalname = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                            $sname = $this->slugger->slug($originalname);
            
                            $newName = $sname.'-'.uniqid().'.'.$photo->guessExtension();
                        
                                $photo->move(
                                    $this->getParameter('return_images'),
                                    $newName
                                );
                        
                            $savephoto->setUrl($newName);
                        }
        
                        try{
                            $entityManagerPhoto->flush();
                            
                        }
                        catch (FileException $e) {

                            $contents = $this->renderView('errors/500.html.twig', []);
                    
                            return new Response($contents, 500);
                        }
                    }
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