<?php

namespace App\Form;

use App\Entity\Common\Country;
use App\Entity\EmailTemplate;
use App\Entity\Reseller\ResellerShipments;
use App\Entity\ResellerAddress;
use App\Entity\ResellerShipmentItems;
use App\Entity\Returns\ReasonSettings;
use App\Entity\Returns\ReturnImages;
use App\Entity\Returns\Returns;
use App\Entity\Returns\ReturnVideos;
use App\Entity\Returns\Status;
use App\Repository\Returns\ReturnsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints\File;

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
    public $slugger;
    public $params;
    public $session;
    public $email;
    public $webshopOrderId;
    

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine,ReturnsRepository $returnsRepository, SluggerInterface $slugger, ParameterBagInterface $params) {
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
        
        $this->session = $this->requestStack->getSession();
       
        $this->webshopOrderId = $this->session->get('webshop_order_id');
        $this->email = $this->session->get('user_email');
       
        $this->order = $this->doctrine->getRepository(ResellerShipments::class)->findOneBy(['webshopOrderId'=>$this->webshopOrderId]);
        $this->resellerAddress = $this->doctrine->getRepository(ResellerAddress::class)->findOneBy(['id'=>$this->order->getDeliveryAddressId()]);
        $this->shipmentsItems = $this->doctrine->getRepository(ResellerShipmentItems::class)->findBy(['shipment_id'=> $this->order->getId()]);
        
        $this->orderId = $this->webshopOrderId;
        $this->userEmail = $this->email;
        $this->returnsRepository = $returnsRepository;
        $this->slugger = $slugger;
        $this->params = $params;

    }   


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('order_id', TextType::class, [
            'required' => 'Order is required field',
            'attr' => ['class' => 'form-control'],
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
            // ->add('countries', EntityType::class, [
            //     'class' => Country::class,
            //     'choice_label' => function ($country) {
            //         return $country->getName();
            // },
            //     'attr' => ['class' => 'form-control']
            // ])
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
                'attr' => ['class' => 'form-control'],
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
            ->add('photos', FileType::class, [
                'multiple' => true,
                'data_class' => null,
                'required' => false,
                'empty_data' =>  "",
                'attr' => ['class' => 'form-control', 'multiple' => 'multiple'],
                'label' => 'Your images',
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
            ->add('videos', FileType::class, [
                'multiple' => true,
                'data_class' => null,
                'required' => false,
                'empty_data' =>  "",
                'attr' => ['class' => 'form-control', 'multiple' => 'multiple'],
                'label' => 'Your videos',
                'label_attr' => ['class'=> 'required'],
                'constraints' => [
                    new File([
                        'maxSize' => '3000k',
                        'mimeTypes' => [
                            'video/.flv',
                            'video/.mp4',
                        ],
                        'mimeTypesMessage' => 'Please upload Image, image must be in jpg, jpeg, png format',
                    ])
                ],
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {

                $request = $this->requestStack->getCurrentRequest();
                $form = $event->getForm();
                $return = new Returns();

               
                $all  = $request->request->all();
                // return dd($all);
                $shablon = $all['return']['shablon'];

                //find status
                $statusId = $all['return']['status'];
                $status = $this->doctrine->getRepository(Status::class)->findOneBy(['id'=>$statusId]);
               
                $emailT = $this->doctrine->getRepository(EmailTemplate::class)->findOneBy(['status'=>$status]);
                
                if(!$emailT)
                {
                    return dd("Mora se napraviti email");
                }
                //find country
                $countryId = $this->resellerAddress->getCountryId();
                $country = $this->doctrine->getRepository(Country::class)->findOneBy(['id'=>$countryId]);
              
                $clientEmail = $all['return']['client_email'];
                $clientName = $all['return']['client_name'];
                $companyName = $all['return']['company_name'];
                $street = $all['return']['street'];
                $postalCode = $all['return']['postal_code'];
                $itemId = $all['return']['items'];
                $item = $this->doctrine->getRepository(ResellerShipmentItems::class)->findOneBy(['id'=>$itemId]);
                
                if(!$item)
                {
                    return $form->get('items')->addError(new FormError('Item doesnt exist'));              
                }

                $alredyExist = $this->doctrine->getRepository(Returns::class)->findOneBy(['item'=>$item]);
               
                if($alredyExist)
                {
                    
                    return $form->get('items')->addError(new FormError('Item is in process for return'));        
                }

                $return->setReference($this->order->getReference());
                $return->setWebshopOrderId($this->orderId);
                $return->setStatus($status);
                $return->setUserEmail($this->userEmail);
                $return->setClientEmail($clientEmail);
                $return->setClientName($clientName);
                $return->setCompanyName($companyName);
                $return->setStreet($street);
                $return->setPostCode($postalCode);
                $return->setItem($item);
                // $return->setStatus($status);
                $return->setCountry($country);
                $return->setCreatedAt(new \DateTime());
                //quantity check
                $quantity = $all['return']['quantity'];
                $findOrder = $this->doctrine->getRepository(ResellerShipmentItems::class)->findOneBy(['id'=>$itemId]);
               
                if($quantity > $findOrder->getQty())
                {
                    return $form->get('quantity')->addError(new FormError('The quantity must not be greater than the existing one'));              
                }
                else
                {
                    $return->setReturnQuantity($quantity);
                }


                
                if($shablon == "admin-r")
                {
                    $reasonId = $all['return']['reasons'];
                    $reasons = $this->doctrine->getRepository(ReasonSettings::class)->findOneBy(['id'=>$reasonId]);
                    
                    $return->setReason($reasons->getName());
                    
                    $this->returnsRepository->add($return);
                }
                else
                {
                    $reasonUser = $request->request->get('reason-user');
                    $return->setReason($reasonUser);

                    $this->returnsRepository->add($return);

                    
                    $files = $request->files->all();
                    
                    $photos = $files['return']['photos'];
                    $videos = $files['return']['videos'];
                    
                    if($photos != null)
                    {
                        
                        foreach ($photos as $photo) {

                           
                            $savephoto = new ReturnImages();
                            $savephoto->setReturns($return);
                           
                            $savephoto->setCreatedAt(new \DateTime());
                            $originalname = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                            $sname = $this->slugger->slug($originalname);
            
                            $newName = $sname.'-'.uniqid().'.'.$photo->guessExtension();
                        
                                $photo->move(
                                    $this->params->get('return_images'),
                                    $newName
                                );
                        
                            $savephoto->setUrl($newName);
                            
                            $entityManagerPhoto = $this->doctrine->getManager();
                           
                            $entityManagerPhoto->persist($savephoto); 
                            try{
                                $entityManagerPhoto->flush();
                                
                            }
                            catch (FileException $e) {
    
                                $contents = $this->renderView('errors/500.html.twig', []);
                        
                                return new Response($contents, 500);
                            }
                        }
        
                        
                    }

                    if($videos != null)
                    {
                        foreach ($videos as $video) {

                           
                            $savevideo = new ReturnVideos();
                            $savevideo->setReturns($return);
                            
                            $savevideo ->setCreatedAt(new \DateTime());
                            $originalname = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
                            $sname = $this->slugger->slug($originalname);
            
                            $newName = $sname.'-'.uniqid().'.'.$video->guessExtension();
                        
                                $video->move(
                                    $this->params->get('return_videos'),
                                    $newName
                                );
                        
                            $savevideo ->setUrl($newName);
                            
                            $entityManagerVideo = $this->doctrine->getManager();
                           
                            $entityManagerVideo->persist($savevideo); 
                            try{
                                $entityManagerVideo->flush();
                                
                            }
                            catch (FileException $e) {
    
                                $contents = $this->renderView('errors/500.html.twig', []);
                        
                                return new Response($contents, 500);
                            }
                        }
                    }
                }

             
                // - returns and -returnstatus send email
                $emailT = $this->doctrine->getRepository(EmailTemplate::class)->findOneBy(['status'=>$status]);

                if(!$emailT)
                {
                    return dd("Mora se napraviti email");
                }

                

                $servername = $_SERVER['SERVER_NAME'];
                
                $search = array('[webshop_name]','[webshop_order_id]', '[status]', '[name]', '[address]', '[phone]', '[postal_code]', '[country]');
                $replace = array($servername, $return->getWebshopOrderId(), $status->getName(), $return->getClientName(), $return->getStreet(), '[phone]', $return->getPostCode(), $country->getName());
                    
                $template = $emailT->getBody();
                
                $newtemplate = (str_ireplace($search, $replace, $template, $count));
                
                $emailT = (new TemplatedEmail())
                ->from('admin@example.com')
                ->to($return->getUserEmail())
                ->subject($emailT->getSubject())
                ->htmlTemplate('email/status.html.twig')
                ->context([
                    'emailtemplate' => $newtemplate,
                ]);
                
               
               
                $this->session->clear();
            })
           
        ;
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'return',
        ]);
    }
}