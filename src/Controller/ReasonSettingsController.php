<?php

namespace App\Controller;

use App\Entity\ReasonSettings;
use Symfony\Bridge\Doctrine\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReasonSettingsController extends AbstractController
{

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
