<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Payments;
use App\Form\PaymentsType;
use App\Entity\PayCategory;
use App\Repository\PaymentsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

#[Route('/settings/payment')]
class PaymentController extends AbstractController
{
    public $data = [];
    public $payments = [];

    public function __construct(ManagerRegistry $doctrine)
    {

        //everybody will extends status for vertical sidebar -- for email template customization
        $this->data = $doctrine->getRepository(Status::class)->findAll();
        $this->payments = $doctrine->getRepository(PayCategory::class)->findAll();
    }

     /**
     * @Route("/{name}", name="configure_payment", methods={"GET"})
     */
    // public function getpayment(ManagerRegistry $doctrine, $name): Response
    // {
    
    //    $requestName = $name;
    //    $paycategory = $doctrine->getRepository(PayCategory::class)->findOneBy(['name' => $requestName]);
       
    //    if(!$paycategory)
    //    {
    //         $res = [];
    //         return $this->render('errors/404.html.twig', $res,  new Response('There is no result, payment doesnt exist', 404));
    //    }


    //    if($paycategory->getName() == "Stripe")
    //    {
        
    //         $stripe = $doctrine->getRepository(Payments::class)->findOneBy(['category'=> $paycategory->getId()]);
            
    //         if($stripe)
    //         {
    //             //edit stripe
    //             return dd("Postoji");
    //         }
    //         else
    //         {
    //             //create stripe
    //             $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>'Stripe']);
    //             return $this->redirect(strtolower($category->getName()).'/create/');
    //         }
    //    }
    //    else if($paycategory->getName() == "Mollie")
    //    {
    //         $mollie = $doctrine->getRepository(Payments::class)->findOneBy(['category'=> $paycategory->getId()]);
    //         if($mollie)
    //         {
    //             //edit stripe
    //             return dd("Postoji");
    //         }
    //         else
    //         {
    //             $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>'Mollie']);
    //             return $this->redirect(strtolower($category->getName().'/create/'));
    //         }
    //    }
    //    else
    //    {
    //     $res = [];
    //         return $this->render('errors/404.html.twig', $res,  new Response('There is no result, payment doesnt exist', 404));
    //    }
      
       
       
    // }

    /**
     * @Route("/{name}/create/", name="create_payment")
     */
    public function createstripe(Request $request, PaymentsRepository $paymentsRepository, ManagerRegistry $doctrine, $name, SluggerInterface $slugger): Response
    {   
       
        $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>$name]);
        
        $payment = $doctrine->getRepository(Payments::class)->findOneBy(['category'=>$category]);
        
        if($payment)
        {
            return $this->redirectToRoute('edit_payment', ['name'=>strtolower($category->getName())], Response::HTTP_SEE_OTHER);
        }
       
        $payment = new Payments();
        $form = $this->createForm(PaymentsType::class, $payment);
        $form->handleRequest($request);
        
         if ($form->isSubmitted()) {
           
            $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>'Stripe']);
           
            $payment->setPayCategory($category);
            $payment->setCreatedAt(new \DateTime());
            $image = $form->get('image')->getData();
            
            if($image)
            {
                
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newImage = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                
                try {
                    $image->move(
                        $this->getParameter('return_payment_images'),
                        $newImage
                    );
                } catch (FileException $e) {

                    $e = $this->renderView('errors/500.html.twig', []);
                    return new Response($e, 500);
                }
                $payment->setImage($newImage);
            }
            
            $paymentsRepository->add($payment, true);
            
            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        //put category in hidden field
       
                
        return $this->renderForm('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
            'status' => $this->data,
            'payments' => $this->payments,
            'category' => $category
        ]);
    }


 
    #[Route('/new', name: 'app_payment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, PaymentsRepository $paymentsRepository): Response
    {
        $payment = new Payments();
        $form = $this->createForm(PaymentsType::class, $payment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $paymentsRepository->add($payment, true);

            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('payment/new.html.twig', [
            'payment' => $payment,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_payment_show', methods: ['GET'])]
    public function show(Payments $payment): Response
    {
        return $this->render('payment/show.html.twig', [
            'payment' => $payment,
        ]);
    }

    /**
     * @Route("/{name}/edit/", name="edit_payment", methods={"GET", "POST"})
     */
    public function edit(Request $request, PaymentsRepository $paymentsRepository, ManagerRegistry $doctrine, $name, SluggerInterface $slugger): Response
    {
        
        $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>$name]);
        
        $payment = $doctrine->getRepository(Payments::class)->findOneBy(['category'=>$category]);
        
        if(!$payment)
        {
            return dd("Treba ruta create");
        }
       
        $form = $this->createForm(PaymentsType::class, $payment);
        $form->handleRequest($request);
        
         if ($form->isSubmitted()) {
           
            $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>'Stripe']);
           
            $payment->setPayCategory($category);
            $payment->setCreatedAt(new \DateTime());
            $image = $form->get('image')->getData();
            
            if($image)
            {
                
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newImage = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                
                try {
                    $image->move(
                        $this->getParameter('return_payment_images'),
                        $newImage
                    );
                } catch (FileException $e) {

                    $e = $this->renderView('errors/500.html.twig', []);
                    return new Response($e, 500);
                }
                $payment->setImage($newImage);
            }
            
            $paymentsRepository->add($payment, true);
            
            return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
        }

        //put category in hidden field
       
                
        return $this->renderForm('payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
            'status' => $this->data,
            'payments' => $this->payments,
            'category' => $category
        ]);
    }

    #[Route('/{id}', name: 'app_payment_delete', methods: ['POST'])]
    public function delete(Request $request, Payments $payment, PaymentsRepository $paymentsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$payment->getId(), $request->request->get('_token'))) {
            $paymentsRepository->remove($payment, true);
        }

        return $this->redirectToRoute('app_payment_index', [], Response::HTTP_SEE_OTHER);
    }
}
