<?php

namespace App\Form;

use App\Entity\Common\Country;
use App\Entity\Returns\Status;
use App\Entity\Returns\Returns;
use App\Entity\Reseller\Address;
use App\Entity\Reseller\Shipment;
use App\Entity\Returns\ReturnStatus;
use App\Entity\Returns\EmailTemplate;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

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
    public $mailer;

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine,  MailerInterface $mailer) {
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
        $this->mailer = $mailer;
        
    }   
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('order_id', TextType::class, [
            'required' => 'Order is required field',
            'attr' => ['class' => 'form-control'],
            // 'empty_data' =>$this->webShopOrderId,
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
                // 'empty_data' =>$this->return->getReason(),
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
            ])
            ->addEventListener(FormEvents::POST_SUBMIT, function (FormEvent $event) {
                $request = $this->requestStack->getCurrentRequest();

                $all  = $request->request->all();
                
                $orderId = $all['return_edit']['order_id'];
                $reference = $all['return_edit']['reference'];
                //find status
                $statusId = $all['return_edit']['status'];
                $status = $this->doctrine->getRepository(Status::class)->findOneBy(['id'=>$statusId]);
               
                $emailT = $this->doctrine->getRepository(EmailTemplate::class)->findOneBy(['status'=>$status]);

                $form = $event->getForm();
                
                if($reference != $this->return->getReference())
                {
                    return  $form->get('reference')->addError(new FormError('This reference '.$reference.' doesnt exist in system'));
                }

                
                if($orderId != $this->webShopOrderId)
                {
                    return  $form->get('order_id')->addError(new FormError('This order '.$orderId.' doesnt exist in system'));
                }

                $returnstatusExist = $this->doctrine->getRepository(ReturnStatus::class)->findOneBy(['returns'=>$this->return, 'status'=>$status]);
              
                if($returnstatusExist)
                {
                    return  $form->get('status')->addError(new FormError('You have already choosen the status '.$status->getName()));
        
                }
                else
                {
                    $returnstatus = new ReturnStatus();
            
                    $returnstatus->setReturns($this->return);
                    
                    $returnstatus->setStatus($status);
                    $returnstatus->setCreatedAt(new \DateTime());
                    $entityManager = $this->doctrine->getManager();
                    $entityManager->persist($returnstatus);
                    $entityManager->flush();
                }
               

                if(!$emailT)
                {
                    
                    return  $form->get('status')->addError(new FormError('You need to configure template email for status: '.$status->getName()));
                    
                }
                $shipmentorder = $this->doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId'=> $this->return->getWebshopOrderId()]);
                
                $address = $this->doctrine->getRepository(Address::class)->findOneBy(['id'=>$shipmentorder->getDeliveryAddress()]);
                $countryId = $address->getCountry();
                
                $country = $this->doctrine->getRepository(Country::class)->findOneBy(['id'=>$countryId]);
                
                $servername = $_SERVER['SERVER_NAME'];
                
                $search = array('[webshop_name]','[webshop_order_id]', '[status]', '[name]', '[address]', '[phone]', '[postal_code]', '[country]');
                $replace = array($servername, $this->return->getWebshopOrderId(), $status->getName(), $this->return->getClientName(), $this->return->getStreet(), '[phone]', $this->return->getPostCode(), $country->getName());
                    
                $template = $emailT->getBody();
                
                $newtemplate = (str_ireplace($search, $replace, $template, $count));
                
                $emailT = (new TemplatedEmail())
                ->from('admin@example.com')
                ->to($this->return->getUserEmail())
                ->subject($emailT->getSubject())
                ->htmlTemplate('email/status.html.twig')
                ->context([
                    'emailtemplate' => $newtemplate,
                ]);
                $this->mailer->send($emailT);
            });                
          
           
    }
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => 'App\Entity\Returns\Returns',
            'csrf_protection' => true,
            // the name of the hidden HTML field that stores the token
            'csrf_field_name' => '_token',
            'csrf_token_id'   => 'return',
        ]);
    }
}