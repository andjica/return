<?php

namespace App\Controller;

use Exception;
use App\Entity\Status;
use App\Entity\Payments;
use Psr\Log\LoggerInterface;
use Webmozart\Assert\Assert;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Polyfill\Intl\Idn\Resources\unidata\Regex;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PaymentController extends AbstractController
{
    public function __construct(ManagerRegistry $doctrine)
    {

        //everybody will extends status for vertical sidebar -- for email template customization
        $this->data = $doctrine->getRepository(Status::class)->findAll();
  
    }

    /**
     * @Route("/payment/create", methods={"GET", "POST"}, name="payment_create")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        //check is payment configuration aleready exists
        $count = $doctrine->getManager()->getRepository(Payments::class)->findOneBy([]);
         
        if ($count)
        {
            return $this->redirectToRoute('payment_edit', [], Response::HTTP_SEE_OTHER); 
        }
        else
        {
            return $this->render('payment/index.html.twig', [
                'controller_name' => 'PaymentController',
            ]);

        }
        
        
    }

    /**
     * @Route("/payment/insert", methods={"GET", "POST"}, name="payment_insert")
     */
    public function insert(Request $request, LoggerInterface $logger, ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $token = $request->request->get('token');

        if(!$this->isCsrfTokenValid('insert-return', $token))
        {
            $logger->info("CSRF failure");

           $contents = $this->renderView('errors/500.html.twig', []);
        
           return new Response($contents, 500);
        }
        //for back if we have errors 
        $currentroute = $request->headers->get('referer');
        
       
        //new object payment
        $newpayment = new Payments();
        
        $publickey = $request->request->get('pkey');
       
        if($publickey == "")
        {
            $this->addFlash('erpublickey', 'Public key is required filed');
            return $this->redirect($currentroute);
        }
        if(!preg_match("/[0-9a-zA-Z]{15,}/",$publickey))
        {
            $this->addFlash('erpublickey', 'Invalid public key payment');
            return $this->redirect($currentroute);
        }

        $newpayment->setPublickey($publickey);

        $secretkey = $request->request->get('skey');

        if($secretkey != "")
        {
            if(!preg_match("/[0-9a-zA-Z]{15,}/",$secretkey))
            {
                $this->addFlash('ersecretkey', 'Invalid sekret key format');
                return $this->redirect($currentroute);
            }
            
            $newpayment->setSecretkey($secretkey);
        }

        $photo = $request->files->get('image');

        if($photo)
        {
            $extenstion = $photo->guessExtension();
            $allowedext = array('jpg', 'jpeg', 'png');

            if(!in_array($extenstion, $allowedext))
            {
                $this->addFlash('errors', 'Wrong format type for payment image, logo must be in extenstions: jpg, jpeg, png');
                return $this->redirect($currentroute);
            }
           
            //validate file size
            $filesize = filesize($photo); // bytes
            //2,5MB
            if($filesize > 2500000)
            {
                $this->addFlash('errors', 'Payment image is too large');
                return $this->redirect($currentroute);
            }

            $originalname = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
            
            $sname = $slugger->slug($originalname);
            $newName = $sname.'-'.uniqid().'.'.$photo->guessExtension();

            try {
                
                $photo->move(
                    $this->getParameter('return_payment_images'),
                    $newName
                );
               
                $newpayment->setImage($newName);

            } catch (FileException $e) {

                $contents = $this->renderView('errors/500.html.twig', []);
        
                return new Response($contents, 500);
            }

            $prepare = $doctrine->getManager();
            $prepare->persist($newpayment);
            $prepare->flush();
            
            return $this->redirect('/payment/edit');

        }
        else
        {
            $prepare = $doctrine->getManager();
            $prepare->persist($newpayment);
            $prepare->flush();
            
            return $this->redirect('/payment/edit');
        }
        // return $this->redirect('/payment/edit');
    }

    /**
     * @Route("/payment/edit", methods={"GET", "POST"}, name="payment_edit")
     */
    public function edit(ManagerRegistry $doctrine)
    {
        $payment = $doctrine->getRepository(Payments::class)->findOneBy([], ['id' => 'DESC']);

        if(!$payment)
        {
            return $this->redirectToRoute('payment_create');
        }

        return $this->render('payment/edit.html.twig', [
            'payment' => $payment,
            'status' => $this->data
        ]);

    }

    /**
     * @Route("/payment/update", methods={"GET", "POST"}, name="payment_update")
     */
    public function update(Request $request, LoggerInterface $logger, ManagerRegistry $doctrine, SluggerInterface $slugger)
    {
        $token = $request->request->get('token');

        if(!$this->isCsrfTokenValid('update-payment', $token))
        {
            $logger->info("CSRF failure");

           $contents = $this->renderView('errors/500.html.twig', []);
        
           return new Response($contents, 500);
        }
        //for back if we have errors 
        $currentroute = $request->headers->get('referer');

        //last inserted
        $payment = $doctrine->getManager()->getRepository(Payments::class)->findOneBy([]);

        $publickey = $request->request->get('pkey');

        if($publickey == "")
        {
            $this->addFlash('erpublickey', 'Public key is required filed');
            return $this->redirect($currentroute);
        }
        else
        {
            if($publickey == $payment->getPublicKey())
            {
                $payment->setPublicKey($payment->getPublicKey());
            }
            else
            {
                if(!preg_match("/[0-9a-zA-Z]{15,}/",$publickey))
                {
                    $this->addFlash('erpublickey', 'Invalid public key payment');
                    return $this->redirect($currentroute);
                }

                $payment->setPublicKey($publickey);
            }
        }

        $secretkey = $request->request->get('skey');

        
        //ovde nastavi
        if($secretkey != "")
        {

            if($secretkey == $payment->getSecretKey())
            {
                $payment->setSecretKey($payment->getSecretKey());
            }
           
           
            if(!preg_match("/[0-9a-zA-Z]{15,}/",$secretkey))
            {
                $this->addFlash('ersecretkey', 'Invalid sekret key format');
                return $this->redirect($currentroute);
            }
            else
            {
                
               $payment->setSecretkey($secretkey);
            }
                
           
            
        }

        $entity = $doctrine->getManager();
                

        $photo1 = $request->files->get('image');
        
        if($photo1 == null)
        {
                //if already image exist
                if($payment->getImage() != null)
                {
                    $payment->setImage($payment->getImage());
                   
                    try{
                        $entity->persist($payment);
                        $entity->flush();
                        $this->addFlash('success', 'You made changes successfully');
                        return $this->redirect($currentroute);
                    }
                    catch(Exception $e)
                    {
                        $contents = $this->renderView('errors/500.html.twig', []);
        
                        return new Response($contents, 500);
                    }
                    
                }
              
        }
        else
        {
            //delete current image from server and than insert new one

            //validate extenstion
            $extenstion = $photo1->guessExtension();
            $allowedext = array('jpg', 'jpeg', 'png');

            if(!in_array($extenstion, $allowedext))
            {
                $this->addFlash('image', 'Wrong format type for payment image, image must be in extenstions: jpg, jpeg, png');
                return $this->redirect($currentroute);
            }
        
            //validate file size
            $filesize = filesize($photo1); // bytes
            //2,5MB
            if($filesize > 2500000)
            {
                $this->addFlash('image', 'Logo image is too large');
                return $this->redirect($currentroute);
            }

            $originalname = pathinfo($photo1->getClientOriginalName(), PATHINFO_FILENAME);
            
            $sname = $slugger->slug($originalname);
            $newName = $sname.'-'.uniqid().'.'.$photo1->guessExtension();
            
            
            try {
                $lastimage = $payment->getImage();
                $fs = new Filesystem();
                $fs->remove($this->getParameter('return_payment_images').'/'.$lastimage);
                
                $photo1->move(
                    $this->getParameter('return_payment_images'),
                    $newName
                );
            
                $payment->setImage($newName);


            } catch (FileException $e) {

                $contents = $this->renderView('errors/500.html.twig', []);
        
                return new Response($contents, 500);
            }

            //save object
            try{
                $entity->persist($payment);
                $entity->flush();
                $this->addFlash('success', 'You made changes successfully');
                return $this->redirect($currentroute);
            }
            catch(Exception $e)
            {
                $contents = $this->renderView('errors/500.html.twig', []);

                return new Response($contents, 500);
            }
            
           
        }
        
        
    }
    
}
