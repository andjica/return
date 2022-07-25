<?php

namespace App\Controller;

use App\Entity\Status;

use App\Form\ReasonType;
use App\Entity\PayCategory;
use App\Entity\ReasonSettings;
use App\Entity\ReturnSettings;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReasonController extends AbstractController
{

    private $data = [];
    private $imagelogo = [];
    public $payments = [];

    public function __construct(ManagerRegistry $doctrine)
    {

        $this->doctrine = $doctrine;

        //everybody will extends status for vertical sidebar -- for email template customization
        $this->data = $doctrine->getRepository(Status::class)->findAll();
        
        //imagelogo
        $rs = $doctrine->getRepository(ReturnSettings::class)->findLastInserted();
    
        $this->imagelogo = $rs->getImageLogo();

        $this->payments = $doctrine->getRepository(PayCategory::class)->findAll();

  
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
           
        }

        return $this->renderForm('reason/new.html.twig', [
            
            'form' => $form,
            'status' => $this->data,
            'payments' => $this->payments
        ]);
    }
    //return twig for adding a  new reasons
    /**
     * @Route("/reason/settings/new", methods={"GET", "POST"}, name="settings-reason")
     */
    public function getSettingsReasons(): Response
    {
        return $this->render('reasons_settings/index.html.twig', []);
    }
    //add active reasons

    #[Route("/reason/settings/add", name:"add-reason-settings")]
    public function add(Request $request)
    {

       
         $reasons = $request->request->all('reasons');
         $actives =  $request->request->all('active');
        
       
        foreach ($reasons as   $reason)
        {
            $newReason = new ReasonSettings();
            $newReason->setName($reason);

           
    
               
        }

       
         return dd($newReason);
    }
}
