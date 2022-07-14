<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Country;
use App\Entity\Returns;
use App\Entity\ReturnStatus;
use App\Entity\ReturnSettings;
use App\Form\SearchReturnType;
use App\Repository\CountryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReturnSettingsRepository;
use App\Repository\ReturnsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ClientController extends AbstractController
{

    /**
     * @Route("/returns", methods={"GET", "POST"}, name="app_client")
     */
    public function index(ManagerRegistry $doctrine): Response
    {

        $returns = $doctrine->getRepository(Returns::class)->findBy(['action'=> null], ['created_at' => 'DESC']);
        $status = $doctrine->getRepository(Status::class)->findAll();
        
        $data =  [ 
            'controller_name' => 'Andjica',
            'returns' => $returns,
            'status' => $status
        ];

        return $this->render('client/index.html.twig', $data);
    }


    /**
     * @Route("/settigs-return", methods={"GET", "POST"}, name="settigs-return")
     */
    public function FunctionName(): Response
    {
        return $this->render('return_settings/new.html.twig', []);
    }


    /**
     * @Route("/show-process/{id}", methods={"GET", "POST"}, name="show-process")
     */
    public function showprocess(int $id, ManagerRegistry $doctrine)
    {
        $returnprocess = $doctrine->getRepository(ReturnStatus::class)->findBy(['return_id'=>$id]);
        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id' => $id]);


        $data = [
            'return' => $return,
            'proccess' => $returnprocess
        ];
        return $this->render('return_status/show-process.html.twig', $data);
    }

    /**
     * @Route("/return/search", name="search_return")
     */
    public function search(Request $request, ReturnsRepository $return, ManagerRegistry $doctrine): Response
    {
        

        $form = $this->createForm(SearchReturnType::class);
        
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
            $userEmail = $form->get('user_email')->getData();
            $webshopOrderId =  $form->get('webshop_order_id')->getData();

            //pause here
            $return = $doctrine->getRepository(Returns::class)->findOneBy(['user_email'=>$userEmail, 'webshop_order_id'=>$webshopOrderId]);
            return dd($return);
        }
        // $return->add($findreturn);
        return $this->renderForm('return/find.html.twig', ['form' => $form]);
    }

}
