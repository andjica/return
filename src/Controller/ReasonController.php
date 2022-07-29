<?php

namespace App\Controller;

use App\Entity\Status;

use App\Form\ReasonType;
use App\Entity\PayCategory;
use App\Entity\ReasonSettings;
use App\Entity\ReturnSettings;
use Symfony\Component\Form\FormError;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReasonController extends AbstractController
{

    private $data = [];
    public $payments = [];
    public $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {

       //everybody will extends status for vertical sidebar -- for email template customization
       $this->data = $doctrine->getRepository(Status::class)->findAll();
       $this->payments = $doctrine->getRepository(PayCategory::class)->findAll();
       $this->doctrine = $doctrine;
  
    }

    /**
     * @Route("/settings/reason/create", name="create_reason")
     */
    public function create(ManagerRegistry $doctrine, Request $request): Response
    {
        $form = $this->createForm(ReasonType::class);
        $form->handleRequest($request);
       
        if($form->isSubmitted())
        {
           
            // $request = $this->requestStack->getCurrentRequest();

            $all = $request->request->all();
            $reasons = $all['reasons']['text'];
            $firstreason = $all['reason']['first_reason'];
            

            if($firstreason == "")
            {
                return $form->get('first_reason')->addError(new FormError('You must choose one reason'));        
            }

            
            $newFirstReason = new ReasonSettings();
            $newFirstReason->setName($firstreason);
            $newFirstReason->setActive(1);
            $newFirstReason->setCreatedAt(new \DateTime());

            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($newFirstReason);
            $entityManager->flush();

            if(count($reasons) > 1)
            {
                
                $reasonExist = array_filter($reasons);
                // return dd(count($reasonExist));
                foreach($reasons as $r)
                    {
                        $newReasons = new ReasonSettings();
                        if($r == "")
                        {
                            $newReasons->setName("");
                            $newReasons->setActive(2);
                        }
                        else
                        {
                            $newReasons->setName($r);
                            $newReasons->setActive(1);
                        }
                        
                        $newReasons->setCreatedAt(new \DateTime());
                        
                        
                        
                        $entityManager->persist($newReasons);
                        $entityManager->flush();
                    }

                  

            }
            return $this->redirectToRoute('edit_reason');
        }

        return $this->renderForm('reason/new.html.twig', [
            
            'form' => $form,
            'status' => $this->data,
            'payments' => $this->payments
        ]);
    }
   
     /**
     * @Route("/settings/reason/edit", name="edit_reason")
     */
    public function edit(Request $request, ManagerRegistry $doctrine): Response
    {
        $otherreasons = $doctrine->getRepository(ReasonSettings::class)->findothers(['id'=>1]);
        $countreasons = count($otherreasons);
        
        $form = $this->createForm(ReasonType::class);
        $form->handleRequest($request);
       
        if($form->isSubmitted())
        {
            $all = $request->request->all();
            $reasons = $all['reasons']['text'];
           
            $firstreason = $all['reason']['first_reason'];
            
           
            if($firstreason == "")
            {
                return $form->get('first_reason')->addError(new FormError('You must choose one reason'));        
            }

            $first = $this->doctrine->getRepository(ReasonSettings::class)->findOneBy(['id'=>1]);
            $first->setName($firstreason);
            $first->setActive(1);
            $first->setUpdatedAt(new \DateTime());
            $entityManager = $this->doctrine->getManager();
            $entityManager->persist($first);
            $entityManager->flush();
            

        

          
           
            $list = [2,3,4,5,6,7,8,9,10];
            $otherreasons = $doctrine->getRepository(ReasonSettings::class)->findBy(['id'=>$list]);
            
            foreach($otherreasons as $r)
            {
                $andjica = [];
                foreach($reasons as $value)
                {
                 
                    $andjica[] = $value;
                    
                    foreach($andjica as $s)
                    {
                        $r->setName($s);
                        $r->setActive(1);
                            
                        $r->setUpdatedAt(new \DateTime());
                        $entityManager = $this->doctrine->getManager();
                        $entityManager->persist($r);
                            
                        $entityManager->flush();     
                    }
                   
                   
                 }
                
              
               
            }

            

        }

        return $this->renderForm('reason/edit.html.twig', [
            'form' => $form,
            'status' => $this->data,
            'payments' => $this->payments,
            'otherreasons' => $otherreasons,
            'countreasons' => $countreasons,
        ]);
    }
   
}
