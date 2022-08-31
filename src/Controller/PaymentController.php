<?php

namespace App\Controller;

use App\Entity\Returns\PayCategory;
use App\Entity\Returns\Payments;
use App\Entity\Returns\Status;
use App\Form\PaymentsType;
use App\Repository\Returns\PaymentsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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
     * @Route("/{name}/create/", name="create_payment")
     */
    public function creates(Request $request, PaymentsRepository $paymentsRepository, ManagerRegistry $doctrine, $name, SluggerInterface $slugger): Response
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
           
            $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>$name]);
           
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
            
            return $this->redirectToRoute('edit_payment', ['name'=>strtolower($category->getName())], Response::HTTP_SEE_OTHER);
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


    /**
     * @Route("/{name}/edit/", name="edit_payment", methods={"GET", "POST"})
     */
    public function edit(Request $request, PaymentsRepository $paymentsRepository, ManagerRegistry $doctrine, $name, SluggerInterface $slugger): Response
    {
        
        $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>$name]);
        
        $payment = $doctrine->getRepository(Payments::class)->findOneBy(['category'=>$category]);
        $lastimage = $payment->getImage();
        
        if(!$payment)
        {
            return $this->redirectToRoute('create_payment', ['name'=>strtolower($category->getName())], Response::HTTP_SEE_OTHER);
        }
       
        $form = $this->createForm(PaymentsType::class, $payment);
        $form->handleRequest($request);
        
         if ($form->isSubmitted()) {
           
            $category = $doctrine->getRepository(PayCategory::class)->findOneBy(['name'=>$name]);
           
            $payment->setPayCategory($category);
            $payment->setUpdatedAt(new \DateTime());
            $images = $request->files->all();
            $image = $images['payments']['image'];
            
            if($image)
            {
                
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newImage = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                
                try {
                    $fs = new Filesystem();
                    $fs->remove($this->getParameter('return_payment_images').'/'.$lastimage);

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
            
            return $this->redirectToRoute('edit_payment', ['name'=>strtolower($category->getName())], Response::HTTP_SEE_OTHER);
        }

        //put category in hidden field
       
                
        return $this->renderForm('payment/edit.html.twig', [
            'payment' => $payment,
            'form' => $form,
            'status' => $this->data,
            'payments' => $this->payments,
            'category' => $category,
            'payment' => $payment
        ]);
    }

   
}
