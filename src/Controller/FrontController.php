<?php

namespace App\Controller;

use App\Entity\Returns\Returns;
use App\Entity\Reseller\Shipment;
use App\Entity\Returns\ReturnItems;
use App\Entity\Reseller\ShipmentItem;
use App\Entity\Reseller\ShipmentLabel;
use App\Entity\Returns\ReasonSettings;
use App\Entity\Returns\ReturnSettings;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class FrontController extends AbstractController
{

    private $data = [];

    public function __construct(ManagerRegistry $doctrine)
    {


        $rs = $doctrine->getRepository(ReturnSettings::class)->findLastInserted();

        if (!$rs) {
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


        $returnsettings = $doctrine->getRepository(ReturnSettings::class)->findOneBy(['status' => 1]);


        if (!$returnsettings) {
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
        $returnsettings = $doctrine->getRepository(ReturnSettings::class)->findOneBy(['status' => 1]);

        if (!$returnsettings) {
            $contents = $this->renderView('errors/404.html.twig', []);

            return new Response($contents, 404);
        }

        //for going back to the same route
        $currentroute = $request->headers->get('referer');

        $orderId = $request->request->get('orderId');

        $email = $request->request->get('email');

        //find order
        $order = $doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId' => $orderId]);
        
       
        $arrayerrors = [];

        if ($order) {

            $customeremail = $order->getDeliveryAddress()->getEmail();

            if ($customeremail == $email) {
                //go to new page :)
                return $this->redirectToRoute('order_products', ['orderId' => $orderId, 'email' => $email], Response::HTTP_SEE_OTHER);
            } else {
                $arrayerrors[] = $this->addFlash('errors', 'There is no customer with email adress: ' . $email);

                return $this->redirect($currentroute);
            }
        } else {
            $arrayerrors[] = $this->addFlash('errors', 'There is no Order with ID: ' . $orderId);

            return $this->redirect($currentroute);
        }

    }

    /**
     * @Route("return/order-products/orderId={orderId}&email={email}", methods={"GET"}, name="order_products")
     */
    public function reasons(ManagerRegistry $doctrine, Request $request, string $orderId, $email)
    {
        $returnsettings = $doctrine->getRepository(ReturnSettings::class)->findOneBy(['status' => 1]);


        if (!$returnsettings) {
            $contents = $this->renderView('errors/404.html.twig', []);

            return new Response($contents, 404);
        }

        //find order & email - because of the security reasons find it again

        $order = $doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId' => $orderId]);

        if ($order) {

            $return = $doctrine->getRepository(Returns::class)->findOneBy(['webshop_order_id'=>$orderId]);
            if($return)
            {
                $this->addFlash('errors', 'Order id - ' . $orderId . ' and email - ' . $email.' are already in proccess for return');
                return $this->redirectToRoute('app_front');
            }
            $products = $doctrine->getRepository(ShipmentItem::class)->findBy(['shipment' => $order]);
            
            //check if return exist and delete it
            //check if item exist in returnitem table
            //if exist delete it - like refresh
            $return = $doctrine->getRepository(Returns::class)->findOneBy(['webshop_order_id'=>$orderId]);
            if($return)
            {
                $returnId = $return->getId();
                $returnItems = $doctrine->getRepository(ReturnItems::class)->findBy(['return_id'=>$return]);
               
                foreach($returnItems as $ritem)
                {
                    $entitydelete = $doctrine->getManager();
                    $entitydelete->remove($ritem);
                    $entitydelete->flush($ritem);
                }
            }
            //return total price of order
            $prices = [];

            foreach ($products as $p) {
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
        } else {
            $this->addFlash('errors', 'There is no Order with ID ' . $orderId . ' and email: ' . $email);
            return $this->redirectToRoute('app_front');
        }

    }

    /**
     * @Route("/return/confirm", name="confirm_return")
     */
    public function returnConfirmed(Request $request, ManagerRegistry $doctrine)
    {
        $submittedToken = $request->request->get('token');

        
        if ($this->isCsrfTokenValid('confirm-return', $submittedToken)) {

            $orderId = $request->request->get('orderId');
            $email = $request->request->get('email');
            $returnId = $request->request->get('returnId');

            //chech return
            $return = $doctrine->getRepository(Returns::class)->findOneBy(['id'=>$returnId, 'user_email'=>$email, 'webshop_order_id'=>$orderId, 'confirmed'=>0]);
            
            if($return)
            {
                //return items
                $returnItems = $doctrine->getRepository(ReturnItems::class)->findBy(['return_id'=>$return->getId()]);
                
                return $this->render('front/confirm-return.html.twig', 
                [
                    'returnItems' => $returnItems,
                    'data' => $this->data,
                    'orderId' => $orderId,
                    'email' => $email,
                    'returnId' => $returnId
                ]);
            }
            else
            {
                $contents = $this->renderView('errors/404.html.twig', []);

                return new Response($contents, 404);
            }
        }
        else
        {
            $contents = $this->renderView('errors/500.html.twig', []);

            return new Response($contents, 500);
        }
    }


}
