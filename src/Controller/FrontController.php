<?php

namespace App\Controller;

use App\Entity\Reseller\Shipment;
use App\Entity\Reseller\ShipmentItem;
use App\Entity\Returns\ReasonSettings;
use App\Entity\Returns\ReturnSettings;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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

            $products = $doctrine->getRepository(ShipmentItem::class)->findBy(['shipment' => $order]);

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


}
