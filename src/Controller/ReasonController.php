<?php

namespace App\Controller;

use App\Entity\Returns\PayCategory;
use App\Entity\Returns\ReasonSettings;
use App\Entity\Returns\Status;
use App\Form\ReasonType;
use Doctrine\Persistence\ManagerRegistry;
use Egulias\EmailValidator\Result\Reason\Reason;
use PhpParser\Comment\Doc;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
       
        $reasons = $doctrine->getRepository(ReasonSettings::class)->findAll();

        if($reasons)
        {
            return $this->redirectToRoute('edit_reason');
        }
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
            $this->addFlash('success', 'You made reasons successfully');
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
            
           
            $connection = $doctrine->getConnection();

            $platform   = $connection->getDatabasePlatform();

            $connection->executeUpdate($platform->getTruncateTableSQL('return_reason_settings', true));
            
            if($firstreason == "")
            {
                return $form->get('first_reason')->addError(new FormError('You must choose one reason'));        
            }
            //save first reason
            $newFirstReason = new ReasonSettings();
            $newFirstReason->setName($firstreason);
            $newFirstReason->setActive(1);
            $newFirstReason->setCreatedAt(new \DateTime());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($newFirstReason);
            $entityManager->flush();

            $entityManager2 = $doctrine->getManager();
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
                        
                        
                        
                        $entityManager2->persist($newReasons);
                        $entityManager2->flush();
                }

                  

            }
            $this->addFlash('success', 'You updated reasons successfully');
            return $this->redirectToRoute('edit_reason');

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
