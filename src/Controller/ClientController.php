<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Country;
use App\Entity\Returns;
use App\Entity\ReturnStatus;
use App\Entity\ReturnSettings;
use App\Form\SearchReturnType;
use App\Entity\ResellerShipments;
use Doctrine\ORM\Query\Expr\Func;
use App\Repository\CountryRepository;
use App\Repository\ReturnsRepository;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReturnSettingsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class ClientController extends AbstractController
{

   
    private $requestStack;
    private $doctrine;

    public function __construct(RequestStack $requestStack, ManagerRegistry $doctrine)
    {
        $this->requestStack = $requestStack;
        $this->doctrine = $doctrine;
    }
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

            $all = $request->request->all();
            
            $email = $all['search_return']['user_email'];
            $webshopOrderId = $all['search_return']['webshop_order_id'];
            $session = $this->requestStack->getSession();

                // stores an attribute in the session for later reuse
            $session->set('webshop_order_id', $webshopOrderId);
            $session->set('user_email', $email);
            // gets an attribute by name
           
            return $this->redirectToRoute('create_return');
        
            
        }
        // $return->add($findreturn);
        return $this->renderForm('return/find.html.twig', ['form' => $form]);
    }

    /**
     * @Route("/return/create/bla", name="create_return", methods={"GET"})
     */
    public function createreturn(): Response
    {
        $session = $this->requestStack->getSession();
        $webshopOrderId = $session->get('webshop_order_id');
        $email = $session->get('user_email');
        
        $order = $this->doctrine->getRepository(ResellerShipments::class)->findOneBy(['webshopOrderId'=>$webshopOrderId]);
           
        
        return $this->render('$0.html.twig', []);
    }

    /**
     * @Route("/return/by", name="return_by")
     */
    public function getreturn(): Response
    {
        return $this->render('$0.html.twig', []);
    }

}
