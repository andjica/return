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
            return dd($reasons);
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
            

            $ids = [];
            foreach($reasons as $r=>$value)
            {
                $ids[] = $r;
            }

           
          
            $list = [2,3,4,5,6,7,8,9,10];
            
            $edit = $doctrine->getRepository(ReasonSettings::class)->findBy(['id' => $ids]);

            foreach($reasons as $r=>$value)
            {
                $editarray = $doctrine->getRepository(ReasonSettings::class)->findBy(['id' => $ids]);
                foreach($editarray as $edit)
                {
                   
                    if($r == "")
                    {
                       
                        $edit->setName(""); 
                        $edit->setActive(2);
                        $edit->setUpdatedAt(new \DateTime());
                        $entityManager->persist($edit);
                    }
                    
                   $edit->setName($value); 
                   $edit->setActive(1);
                    
                    $edit->setUpdatedAt(new \DateTime());
                     $entityManager->persist($edit);
                    
                    
                  
                   
                }
               
            }
            $entityManager->flush();
           
            // $entityManager->flush();

            // $andjica = [];

            // foreach($reasons as $r=>$value)
            // {
            //     foreach($edit as $s)
            //     {
            //         if($value == "")
            //         {
            //             $s->setName("");
            //             $s->setActive(2);
            //         }
            //         $s->setName($andjica[] = $value);
            //         $s->setActive(1);
            //         $s->setUpdatedAt(new \DateTime());
            //         $entitym = $this->doctrine->getManager();
            //         $entitym->persist($s);
                    
            //     }
               
                
            // }
            
            // $entitym->flush();
           
            // foreach($edit as $rs)
            // {
                
            //    foreach($reasons as $r=>$value)
            //     {
            //         $rs->setName($value);
                   
            //         $rs->setActive(1);
            //         $rs->setUpdatedAt(new \DateTime());
                  
            
            //     }
                
                
            // }
            
            
            // foreach ($otherreasons as $obj) {
                
            //     $map[$obj->getId()] = $obj;

               
               
            //     $obj->setName($andjica);
                
                
            //     $entityManager2 = $this->doctrine->getManager();
            //     $entityManager2->persist($obj); 
                                
            //      $entityManager2->flush();  
            
            // }
            // return dd($obj);
            // $em->flush();
            // foreach ($otherreasons as $entity) {
            //     $map[$entity->getId()] = $entity;

            //     $andjica = [];
                 
                    
            //         $map[$entity->setName()] = "ss";
            //         $map[$entity->setActive()] = 1;
            //         $map[$entity->setUpdatedAt()] = (new \DateTime());
                
            // }
            // $entityManager = $this->doctrine->getManager();
            // return dd($entityManager->persist($entity));
                           
            //     $entityManager->flush();    
            // return dd($map);
            // if (array_keys($list) !== array_keys($map)) {
            //     return dd("ne slaze se");
            // }
            // $i = 0;
           
            // $name = [];
            
            // return dd($res);
               
            //    foreach($otherreasons as $r)
            //     {
            //             foreach($reasons as $t)
            //             {
            //                 $r->setName($t);

            //                 $r->setActive(1);
                                
            //                 $r->setUpdatedAt(new \DateTime());
                           
            //             }
                          
                            
            //     }
               
                   
               
            
               
            // $this->getDoctrine()->getEntityManager()->flush();
            // foreach ($list as $id => $form_data) {
               
            //     foreach($map as $s)
            //     {
            //         return dd($s->setName());
                    
            //         $s->setActive(1);
            //         $s->setUpdatedAt(new \DateTime());
            //         $entityManager = $this->doctrine->getManager();
            //         $entityManager->persist($s);
                            
            //         $entityManager->flush();     
            //     }
            // }

            // foreach($otherreasons as $r)
            // {
            //     $andjica = [];
            //     foreach($reasons as $value)
            //     {
                 
            //         $andjica[] = $value;
                    
            //         foreach($andjica as $s)
            //         {
            //             $r->setName($s);
            //             $r->setActive(1);
                            
            //             $r->setUpdatedAt(new \DateTime());
            //             $entityManager = $this->doctrine->getManager();
            //             $entityManager->persist($r);
                            
            //             $entityManager->flush();     
            //         }
                   
                   
            //      }
                
              
               
            // }

            

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
