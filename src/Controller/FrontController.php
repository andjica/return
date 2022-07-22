<?php

namespace App\Controller;

use App\Entity\Users;
use App\Entity\ReasonSettings;
use App\Entity\ReturnSettings;
use App\Entity\ResellerShipments;
use Symfony\Component\Mime\Email;
use Proxies\__CG__\App\Entity\Retur;
use Symfony\Component\Mailer\Mailer;
use App\Entity\ResellerShipmentItems;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Notifier\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\Transport;

class FrontController extends AbstractController
{

    private $data = [];

    public function __construct(ManagerRegistry $doctrine)
    {

        
        $rs = $doctrine->getRepository(ReturnSettings::class)->findLastInserted();
    
        if(!$rs)
        {
             return $this->redirect('/return/notfound');
        }
        $this->data['imagelogo'] = $rs->getImageLogo();
        $this->data['imagebackground'] = $rs->getImageBackground();
        $this->data['title'] = $rs->getTitle();
        
        
    }
    /**
     * @Route("/return/notfound", name="RouteName")
     */
    public function notfound(): Response
    {
        $contents = $this->renderView('errors/404.html.twig', []);
        
        return new Response($contents, 404);
    }
    //Welcome to frontcontroller for customers :)
    /**
     * @Route("/return", methods={"GET", "POST"}, name="app_front")
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        
        
        $returnsettings = $doctrine->getRepository(ReturnSettings::class)->findOneBy(['status'=>1]);
    

        if(!$returnsettings)
        {
            $contents = $this->renderView('errors/404.html.twig', []);
        
            return new Response($contents, 404);
        }
  
        return $this->render('front/index.html.twig', $this->data);
    }


    //finding if order and email exist
    /**
     * @Route("findorder", methods={"GET", "POST"}, name="app_find_order")
     */
    public function findorder(ManagerRegistry $doctrine, Request $request)
    {
        $returnsettings = $doctrine->getRepository(ReturnSettings::class)->findOneBy(['status'=>1]);
    

        if(!$returnsettings)
        {
            $contents = $this->renderView('errors/404.html.twig', []);
        
            return new Response($contents, 404);
        }
        
        //for going back to the same route
        $currentroute = $request->headers->get('referer');
       
        $orderId = $request->request->get('orderId');
       
        $email = $request->request->get('email');

        //find order
        $order = $doctrine->getRepository(ResellerShipments::class)->findOneBy(['webshopOrderId'=>$orderId]);
        
        $arrayerrors = [];

        if($order)
        {
            //find customer id and then customer from user table
            $customerId = $order->getCustomerId();
           
            $customer = $doctrine->getRepository(Users::class)->findOneBy(['id' => $customerId]);
            
            $customeremail = $customer->getUsername();
           
            if($customer)
            {
                if($customeremail == $email)
                {
                    //go to new page :) 
                    return $this->redirectToRoute('order_products', ['orderId'=>$orderId, 'email'=>$email], Response::HTTP_SEE_OTHER);
                }
                else
                {
                    $arrayerrors[] = $this->addFlash('errors', 'There is no customer with email adress: '.$email);
                    
                    return $this->redirect($currentroute);
                }
                
            }
            else
            {
                $arrayerrors[] = $this->addFlash('errors', 'OOps, something went wrong, try latter');
               
                return $this->redirect($currentroute);
            }
        }
        else
        {
            $arrayerrors[] = $this->addFlash('errors', 'There is no Order with ID: '.$orderId);
            
            return $this->redirect($currentroute);
        }
      
    }

    /**
     * @Route("return/order-products/orderId={orderId}&email={email}", methods={"GET"}, name="order_products")
     */
    public function reasons(ManagerRegistry $doctrine, Request $request, int $orderId, $email)
    {   
        $returnsettings = $doctrine->getRepository(ReturnSettings::class)->findOneBy(['status'=>1]);
    

        if(!$returnsettings)
        {
            $contents = $this->renderView('errors/404.html.twig', []);
        
            return new Response($contents, 404);
        }

        $orderId = $orderId;
        $email = $email;
       
        //find order & email - because of the security reasons find it again

        $order = $doctrine->getRepository(
        Shipments::class)->findOneBy(['webshopOrderId' => $orderId]);
        $user = $doctrine->getRepository(Users::class)->findOneBy(['username'=>$email]);
        
        if($order && $user)
        {
            $shippmentId = $order->getId();

            $products = $doctrine->getRepository(ResellerShipmentItems::class)->findBy(['shipment_id' => $shippmentId]);
           
          
            //return total price of order
            $prices = [];

            foreach($products as $p)
            {
                $prices[] = $p->getPrice() * $p->getQty();
            }
            $total = array_sum($prices);


            //return all active reasons
            $reasons = $doctrine->getRepository(ReasonSettings::class)->findBy(['active' => 1]);
            
            $allproducts = ['data' => $this->data, 
                'products' => $products,
                'total' => $total,
                'reasons' => $reasons,
                'orderId' => $orderId,
                'email' => $email
            ];
            
        
            return $this->render('front/order-products.html.twig', $allproducts);
        }
        else
        {
            $this->addFlash('errors', 'There is no Order with ID '.$orderId.' and email: '.$email);
            return $this->redirectToRoute('app_front');
        }
        
        
        
        
    }

   
    
    

}
