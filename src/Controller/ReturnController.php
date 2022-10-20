<?php

namespace App\Controller;

use App\Form\ReturnType;
use App\Form\ReturnEditType;
use Psr\Log\LoggerInterface;
use App\Entity\Common\Country;
use App\Entity\Returns\Status;
use App\Entity\Returns\Returns;
use App\Entity\Reseller\Address;
use App\Entity\Reseller\Shipment;
use App\Entity\Returns\ReturnItems;
use App\Entity\Returns\ReturnImages;
use App\Entity\Returns\ReturnStatus;
use App\Entity\Returns\ReturnVideos;
use Symfony\Component\Mailer\Mailer;
use App\Entity\Reseller\ShipmentItem;
use App\Entity\Returns\EmailTemplate;
use App\Entity\Reseller\ShipmentLabel;
use App\Entity\Returns\ReasonSettings;
use App\Entity\Returns\ReturnSettings;
use App\Entity\Returns\ReturnShipments;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use App\Repository\Returns\ReturnsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Egulias\EmailValidator\Result\Reason\Reason;
use Symfony\Component\Validator\Constraints\Json;
use Symfony\Component\HttpFoundation\JsonResponse;
use ProxyManager\Factory\RemoteObject\Adapter\JsonRpc;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ReturnController extends AbstractController
{

    /**
     * @Route("/return/item={id}", methods={"GET", "POST"}, name="create_return-reason")
     */
    public function createreturn(ManagerRegistry $doctrine, Request $request, int $id, SluggerInterface $slugger)
    {

        // return dd($request->request->all());
        $orderId = $request->request->get('orderId');
        $quantity = $request->request->get('quantity');
        $email = $request->request->get('email');
        $itemsId = $request->request->get('itemsId');
        
        $order = $doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId' => $orderId]);
      
        $item = $doctrine->getRepository(ShipmentItem::class)->find($itemsId);
        // $shipmentLabel = $doctrine->getRepository(ShipmentLabel::class)->findOneBy(['shipment' => $order->getId()]);
        
        // $shipmenturl = $shipmentLabel->getLabelFileName();
       
        if (!$order) {
            // throw $this->createNotFoundException('The order does not exist', 

            $res = [];

            return $this->render('errors/404.html.twig', $res, new Response('Order doesnt exists, No result', 404));

        }


        if ($quantity == "" || $quantity == 0) {

            $res = [];

            return $this->render('errors/404.html.twig', $res, new Response('Quantity doesnt exists', 404));

        }

        if ($quantity > $item->getQty()) {

            $res = [];

            return $this->render('errors/404.html.twig', $res, new Response('Quantity is larger then current', 404));
        }

        //if quantity is veca od one u bazi proveri to 

        if ($email == "") {

            $res = [];

            return $this->render('errors/404.html.twig', $res, new Response('Email doesnt exists', 404));
        }

        if ($itemsId == "") {

            $res = [];

            return $this->render('errors/404.html.twig', $res, new Response('Items doesnt exists', 404));
        }

        //from reseller address get client info
        $client = $order->getDeliveryAddress();

        //clien info
        $clientemail = $client->getEmail();
        $clientname = $client->getName();
        $companyname = $client->getCompanyName();
        $street = $client->getStreet();

        //Status is IN PROGRESS ID - 1 from status entity
        $statusId = 1;
        //reference
        $reference = $order->getReference();
       
        $adminReasons = $request->request->get('reasons');
        $userReasons = $request->request->get('userreasons');

        $return = new Returns();

        $return->setReference($reference);
        $return->setWebshopOrderId($orderId);
        $return->setUserEmail($email);
        $return->setClientEmail($clientemail);
        $return->setClientName($clientname);
        $return->setCompanyName($companyname);
        $return->setStreet($street);
        // $return->setStatusId($statusId);
        $return->setCreatedAt(new \DateTime());
        $return->setReturnQuantity($quantity);
        $return->setItem($item);


        //return status table
        $status = $doctrine->getRepository(Status::class)->findOneBy(['id' => 1]);

        $returnstatus = new ReturnStatus();

        // $returnstatus->setStatusId(1);

        $returnstatus->setStatus($status);
        $returnstatus->setCreatedAt(new \DateTime());

        //make relationship for status : one to many
        $return->setStatus($status);

        //make relationship for countries 

        $country = $doctrine->getRepository(Country::class)->findOneBy(['id' => 147]);

        $return->setCountry($country);

        if ($userReasons != "") {
            //we have user reason 

            $return->setReason($userReasons);
            $entityManager = $doctrine->getManager();
            $entityManager->persist($return);


            try {
                $entityManager->flush();
                $returnstatus->setReturns($return);
                $entityManager->persist($returnstatus);
                $entityManager->flush();
            } catch (\Exception $e) {

                $res = [];

                return $this->render('errors/404.html.twig', $res, new Response('User doesnt have reason', 404));
            }


            // $photos = $request->request->all('photos');

            $photos = $request->files->all('photos');
            $videos = $request->files->all('videos');


            if ($photos) {
                //validation of photos
                //uradi to
                foreach ($photos as $photo) {

                    $savephoto = new ReturnImages();
                    $savephoto->setReturns($return);

                    $savephoto->setCreatedAt(new \DateTime());
                    $entityManagerPhoto = $doctrine->getManager();
                    $entityManagerPhoto->persist($savephoto);


                    $originalname = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $sname = $slugger->slug($originalname);

                    $newName = $sname . '-' . uniqid() . '.' . $photo->guessExtension();

                    $photo->move(
                        $this->getParameter('return_images'),
                        $newName
                    );

                    $savephoto->setUrl($newName);
                }

                try {
                    $entityManagerPhoto->flush();

                } catch (\Exception $e) {
                    return new Response ("Something went wrong", 500);
                }


            } else {
                $response = new Response('No result', 404);
                $res = ['response' => $response];

                return $this->render('errors/404.html.twig', $res, new Response('', 404));
            }

            if ($videos) {
                foreach ($videos as $video) {

                    $savevideo = new ReturnVideos();
                    $savevideo->setReturns($return);

                    $savevideo->setCreatedAt(new \DateTime());
                    $entityManagerVideo = $doctrine->getManager();
                    $entityManagerVideo->persist($savevideo);


                    $originalname = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
                    $sname = $slugger->slug($originalname);

                    $newName = $sname . '-' . uniqid() . '.' . $video->guessExtension();

                    $video->move(
                        $this->getParameter('return_videos'),
                        $newName
                    );

                    $savevideo->setUrl($newName);
                }

                try {
                    $entityManagerVideo->flush();

                } catch (\Exception $e) {
                    return new Response ("Something went wrong", 500);
                }
            }

            return $this->redirectToRoute('returns');
        } else {
            $reasons = $doctrine->getRepository(ReasonSettings::class)->findOneBy(['id' => $adminReasons]);
            $return->setReason($reasons->getName());


            $entityManager = $doctrine->getManager();
            $entityManager->persist($return);
            $entityManager->flush();


            $returnstatus->setReturns($return);
            $entityManager->persist($returnstatus);

            try {
                $entityManager->flush();

                return $this->redirect('/shipment/'.$orderId.'&'.$return->getId());
            } catch (\Exception $e) {
                return new Response ("Something went wrong", 500);
            }

        }


    }

   

    //page

    /**
     * @Route("/return/create/", name="create_return", methods={"GET", "POST"})
     */
    public function create(Request $request, ReturnsRepository $returnsRepository, SluggerInterface $slugger): Response
    {

        $returnsRepository = new Returns();
        $form = $this->createForm(ReturnType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // return dd($request->request->all());
            // return dd($form);
            $count = $form->getErrors()->count();
            if ($count > 0) {

                $contents = $this->renderView('errors/500.html.twig', []);

                return new Response($contents, 500);
            } else {
                // return dd("Upada ovde");
                return $this->redirectToRoute('returns');
            }


        }

        return $this->renderForm('return/new.html.twig', ['form' => $form]);
    }


    //page for edit return

    /**
     * @Route("/return/edit/{id}/andjca", methods={"GET", "POST"}, name="edit_return")
     */
    public function editreturn(int $id, ManagerRegistry $doctrine)
    {
        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id' => $id]);

        if (!$return) {
            $contents = $this->renderView('errors/404.html.twig', []);

            return new Response($contents, 404);
        }

        $status = $doctrine->getRepository(Status::class)->notInStatus(['id' => $return->getStatus()]);

        $countries = $doctrine->getRepository(Country::class)->findAll();


        $images = $doctrine->getRepository(ReturnImages::class)->findBy(['returns' => $return->getId()]);


        $itemsId = $return->getItem();

        //get item which is in table return :)
        if ($itemsId) {
            $items = $doctrine->getRepository(ShipmentItem::class)->find($itemsId);

            //get total price form ressellershitems
            $quantity = $items->getQty();
            $price = $items->getPrice();

            $total = $quantity * $price;


        } else {
            $items = 0;
        }

        $data = [
            'return' => $return,
            'status' => $status,
            'countries' => $countries,
            'images' => $images,
            'items' => $items,
            'itemId' => $itemsId,
            'total' => $total
        ];

        return $this->render('return/edit-return.html.twig', $data);


    }

    //page for edit return

    /**
     * @Route("/return/edit/{id}", methods={"GET", "POST"}, name="edit_return")
     */
    public function update(Request $request, $id, ManagerRegistry $doctrine): Response
    {
        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id' => $id]);
       
        if (!$return) {
            $contents = $this->renderView('errors/404.html.twig', []);

            return new Response($contents, 404);
        }

        $returnItems = $doctrine->getRepository(ReturnItems::class)->findBy(['return_id'=>$return->getId()]);
       
        $form = $this->createForm(ReturnEditType::class, $return);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
           
        
           $requestedit = $request->get('return_edit');
           $reference = $requestedit['reference'];
           $orderId = $requestedit['order_id'];
           $statusId = $requestedit['status'];
        //    $reason = $requestedit['reasons'];
           $status = $doctrine->getRepository(Status::class)->findOneBy(['id'=>$statusId]);
           $clientname = $requestedit['client_name'];
           $clientemail = $requestedit['client_email'];
           $companyname = $requestedit['company_name'];
           $street = $requestedit['street'];
           $postalcode = $requestedit['postal_code'];


           $return->setReference($reference);
           $return->setWebShopOrderId($orderId);
           $return->setStatus($status);
        //    $return->setReason($reason);
           $return->setClientName($clientname);
           $return->setClientEmail($clientemail);
           $return->setCompanyName($companyname);
           $return->setStreet($street);
           $return->setPostCode($postalcode);
           
           $entitym = $doctrine->getManager();
           $entitym->persist($return);
           $entitym->flush();


           $this->addFlash('success', 'You made changes successfully :)');
           return $this->redirectToRoute('returns');
        }
        return $this->renderForm('return/edit.html.twig', [
            'form' => $form,
            'return' => $return,
            'returnItems' => $returnItems
        ]);
    }


    //edit return

    /**
     * @Route("/return/update", methods={"GET", "POST"}, name="/return/update/")
     */
    public function edit(ManagerRegistry $doctrine, Request $request, LoggerInterface $logger)
    {
        $id = $request->request->get('returnId');

        $token = $request->get('token');

        if (!$this->isCsrfTokenValid('edit-return', $token)) {
            $logger->info("CSRF failure");

            $contents = $this->renderView('errors/500.html.twig', []);

            return new Response($contents, 500);
        }

        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id' => $id]);

        //for go back to the same route
        $currentroute = $request->headers->get('referer');
        if (!$return) {
            return new Response("Not found", 404);
        }


        $clientname = $request->request->get('clientname');
        if (!$clientname) {
            $this->addFlash('errors', 'Name of client is required field');
            return $this->redirect($currentroute);
        }
        $companyname = $request->request->get('companyname');

        if (!$companyname) {
            $this->addFlash('errors', 'Name of company is required field');
            return $this->redirect($currentroute);
        }


        $countryId = $request->request->get('countries');
        if ($countryId == 0) {
            $return->setCountry($return->getCountries());
        } else if ($countryId == NULL) {
            $return->setCountry($return->getCountries());
        } else {
            //because of the relation one to many :)
            $country = $doctrine->getRepository(Country::class)->findOneBy(['id' => $countryId]);
            $return->setCountry($country);
        }

        $street = $request->request->get('street');

        if (!$street) {
            $this->addFlash('errors', 'Street address is required field');
            return $this->redirect($currentroute);
        }

        $statusId = $request->request->get('status');
        if ($statusId == 0) {
            $this->addFlash('errors', 'Status is required');
            return $this->redirect($currentroute);
        }

        if ($statusId == NULL) {
            $this->addFlash('errors', 'Status is required');
            return $this->redirect($currentroute);
        }

        $return->setClientName($clientname);
        $return->setCompanyName($companyname);
        $return->setStreet($street);
        $return->setUpdatedAt(new \DateTime());

        $status = $doctrine->getRepository(Status::class)->findOneBy(['id' => $statusId]);
        $return->setStatus($status);

        $country = $doctrine->getRepository(Country::class)->findOneBy(['id' => $countryId]);

        //preprare for edit
        $entityManager = $doctrine->getManager();
        $entityManager->persist($return);


        if ($statusId == $return->getStatusId()) {

            $return->setStatus($status);
            $entityManager->flush();
            $entityManager->persist($return);
            $entityManager->flush();

        } else {
            $entityManager->flush();
            $entityManager->persist($return);
            $entityManager->flush();

            // $return->setStatusId($statusId);
            $returnstatus = new ReturnStatus();

            $returnstatus->setStatus($status);
            $returnstatus->setReturns($return);
            $returnstatus->setStatus($status);
            $returnstatus->setCreatedAt(new \DateTime());
            $entityManager->persist($returnstatus);
            $entityManager->flush();

            $this->addFlash('success', 'You update successfully');
            return $this->redirect($currentroute);

        }


        $this->addFlash('success', 'You update successfully');
        return $this->redirect($currentroute);

    }

    /**
     * @Route("/deny/{id}", methods={"GET", "POST"}, name="deny")
     */
    public function andjicaproba(int $id, ManagerRegistry $doctrine, MailerInterface $mailer)
    {


        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id' => $id]);

        if (!$return) {
            return new Response(json_encode(array('poruka' => 'Nema rezultata')));
        }
        $return->setAction("Deny");
        $entityManager = $doctrine->getManager();
        $entityManager->persist($return);

        $entityManager->flush();

        return new Response(json_encode(array('id' => $id, 'return' => $return->getId())));

    }

    /**
     * @Route("/create/manually", methods={"GET", "POST"}, name="create_manually_return")
     */
    public function createmanually(ManagerRegistry $doctrine, Request $request, LoggerInterface $logger, MailerInterface $mailer, SluggerInterface $slugger)
    {
        // return dd($request->request->all());
        $token = $request->get('token');

        if (!$this->isCsrfTokenValid('create-manually', $token)) {
            $logger->info("CSRF failure");

            return new Response("Operation not allowed", Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }

        $newReturn = new Returns();

        $orderId = $request->request->get('orderId');

        $currentroute = $request->headers->get('referer');

        if ($orderId == "") {
            $this->addFlash('errors', 'OrderId is required');
            return $this->redirect($currentroute);
        }
        $order = $doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId' => $orderId]);

        if (!$order) {
            // throw $this->createNotFoundException('The order does not exist', 
            $this->addFlash('errors', 'Order id is not in system');
            return $this->redirect($currentroute);

        }

        $products = $doctrine->getRepository(ShipmentItem::class)->findBy(['shipment' => $order]);

        if ($products == null) {
            $this->addFlash('errors', 'You must choose products');
            return $this->redirect($currentroute);
        }

        $customeremail = $order->getDeliveryAddress()->getEmail();

        $shablon = $request->request->get('shablon');

        if ($shablon == "admin-r") {
            $reasonAdmin = $request->request->get('reasons-admin');
            $reasons = $doctrine->getRepository(ReasonSettings::class)->findOneBy(['id' => $reasonAdmin]);
            $reason = $reasons->getName();

            $newReturn->setReason($reason);
        } else {
            $reasonUser = $request->request->get('reason-user');

            if ($reasonUser == "") {
                $this->addFlash('errors', 'Must choose reason');
                return $this->redirect($currentroute);
            } else {
                $newReturn->setReason($reasonUser);
            }
        }

        $quantity = $request->request->get('quantity');
        if ($quantity == "") {
            $this->addFlash('errors', 'Must insert quantity');
            return $this->redirect($currentroute);
        }

        $country = $request->request->get('countries');

        if ($country == 0) {
            $this->addFlash('errors', 'Must choose country');
            return $this->redirect($currentroute);
        } else if (!$country) {
            $this->addFlash('errors', 'Country is required field');
            return $this->redirect($currentroute);
        } else {
            $countries = $doctrine->getRepository(Country::class)->findOneBy(['id' => $country]);

            $newReturn->setCountry($countries);
        }

        $street = $request->request->get('street');

        $street = $request->request->get('postcode');


        if ($street == "") {
            $this->addFlash('errors', 'Must choose street');
            return $this->redirect($currentroute);
        }

        $postcode = $request->request->get('postcode');
        if ($postcode == "") {
            $this->addFlash('errors', 'Must choose post code');
            return $this->redirect($currentroute);
        }

        $status = $request->request->get('status');
        if ($status == 0) {
            $this->addFlash('errors', 'Must choose status');
            return $this->redirect($currentroute);
        } else if (!$status) {
            $this->addFlash('errors', 'Status is required field');
            return $this->redirect($currentroute);
        } else {
            $st = $doctrine->getRepository(Status::class)->findOneBy(['id' => $status]);

            $newReturn->setStatus($st);

            $returnstatus = new ReturnStatus();

            $returnstatus->setStatusId($st->getId());

            $returnstatus->setStatus($st);
            $returnstatus->setCreatedAt(new \DateTime());

        }

        $item = $request->request->get('items-products');

        $client = $order->getDeliveryAddress();

        $newReturn->setWebshopOrderId($orderId);
        $newReturn->setReference($order->getReference());
        $newReturn->setClientName($client->getName());
        $newReturn->setCompanyName($client->getCompanyName());
        $newReturn->setUserEmail($customeremail);
        $newReturn->setClientEmail($client->getEmail());
        $newReturn->setReturnQuantity($quantity);
        $newReturn->setStreet($street);
        $newReturn->setPostCode($postcode);
        $newReturn->setCreatedAt(new \DateTime());
        $newReturn->setItem($item);
        $entityManager = $doctrine->getManager();
        $entityManager->persist($newReturn);

        try {

            $entityManager->flush();
            $photos = $request->files->all('photos');
            $videos = $request->files->all('videos');


            if ($photos != null) {
                //validation of photos
                //uradi to
                foreach ($photos as $photo) {


                    $savephoto = new ReturnImages();
                    $savephoto->setReturns($newReturn);

                    $savephoto->setCreatedAt(new \DateTime());
                    $entityManagerPhoto = $doctrine->getManager();
                    $entityManagerPhoto->persist($savephoto);


                    $originalname = pathinfo($photo->getClientOriginalName(), PATHINFO_FILENAME);
                    $sname = $slugger->slug($originalname);

                    $newName = $sname . '-' . uniqid() . '.' . $photo->guessExtension();

                    $photo->move(
                        $this->getParameter('return_images'),
                        $newName
                    );

                    $savephoto->setUrl($newName);
                }

                try {
                    $entityManagerPhoto->flush();

                } catch (FileException $e) {

                    $contents = $this->renderView('errors/500.html.twig', []);

                    return new Response($contents, 500);
                }


            }

            if ($videos) {
                foreach ($videos as $video) {

                    $savevideo = new ReturnVideos();
                    $savevideo->setReturns($newReturn);

                    $savevideo->setCreatedAt(new \DateTime());
                    $entityManagerVideo = $doctrine->getManager();

                    $originalname = pathinfo($video->getClientOriginalName(), PATHINFO_FILENAME);
                    $sname = $slugger->slug($originalname);

                    $newName = $sname . '-' . uniqid() . '.' . $video->guessExtension();

                    $video->move(
                        $this->getParameter('return_videos'),
                        $newName
                    );

                    $savevideo->setUrl($newName);
                }

                try {
                    $entityManagerVideo->persist($savevideo);
                    $entityManagerVideo->flush();

                } catch (FileException $e) {

                    $contents = $this->renderView('errors/500.html.twig', []);

                    return new Response($contents, 500);
                }
            }
        } catch (\Exception $e) {
            $contents = $this->renderView('errors/500.html.twig', []);

            return new Response($contents, 500);
        }

        //for returnstatus table
        $returnstatus->setReturns($newReturn);
        $entityManagerRS = $doctrine->getManager();
        $entityManagerRS->persist($returnstatus);

        try {

            //create status
            $entityManagerRS->flush();

            //if is everything insert to two tables

            //make photo and videos saved - if is sended


            // - returns and -returnstatus send email
            $email = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status' => $st]);

            $servername = $_SERVER['SERVER_NAME'];

            $search = array('[webshop_name]', '[webshop_order_id]', '[status]', '[name]', '[address]', '[phone]', '[postal_code]', '[country]');
            $replace = array($servername, $newReturn->getWebshopOrderId(), $st->getName(), $newReturn->getClientName(), $street, '[phone]', $postcode, $country);

            $template = $email->getBody();

            $newtemplate = (str_ireplace($search, $replace, $template, $count));

            $email = (new TemplatedEmail())
                ->from('admin@example.com')
                ->to($newReturn->getUserEmail())
                ->subject($email->getSubject())
                ->htmlTemplate('email/status.html.twig')
                ->context([
                    'emailtemplate' => $newtemplate,
                ]);

            try {
                $mailer->send($email);
            } catch (\Exception $e) {
                $contents = $this->renderView('errors/500.html.twig', []);

                return new Response($contents, 500);
            }

        } catch (\Exception $e) {
            $contents = $this->renderView('errors/500.html.twig', []);

            return new Response($contents, 500);
        }

        $this->addFlash('success', 'You create a new return successfully');
        return $this->redirect($currentroute);


    }

    //ajax
    /**
     * @Route("/return/user/reason/create", name="return_ajax_save", methods={"POST"})
     */
    public function createUserReason(Request $request, ManagerRegistry $doctrine): Response
    {
        if($request->isXmlHttpRequest())
        {
           
            if ($request->isXmlHttpRequest()) {     

                $orderId = $request->request->get('orderId');
                $email = $request->request->get('email');
                $adminreason = $request->request->get('adminreason');
                $returnQuantity = $request->request->get('quantityForReturn');
                $itemId = $request->request->get('itemId');
                //order must be unique
                $orderFromDb = $doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId'=>$orderId]);
                
                $reason = $doctrine->getRepository(ReasonSettings::class)->findOneBy(['id'=>$adminreason]);

                if($reason)
                {
                    //check if order exist
                    if($orderFromDb)
                    {
                        //check email - email must be unique
                        $userfromDb = $doctrine->getRepository(Address::class)->findOneBy(['email' => $email]);

                        //if email exists in db
                        if($userfromDb)
                        {
                            //check if return already exist 
                            $return = $doctrine->getRepository(Returns::class)->findOneBy(['user_email'=>$email, 'webshop_order_id'=>$orderId]);
                            
                            

                            $returnSetting = $doctrine->getRepository(ReturnSettings::class)->findOneBy([]);
                        
                            $country = $doctrine->getRepository(Country::class)->findOneBy(['id' => $returnSetting->getCountry()->getId()]);
                            $status = $doctrine->getRepository(Status::class)->findOneBy(['id' => 1]);
                            $reference = $orderFromDb->getReference();


                            $entitym = $doctrine->getManager();
                                //new item for returning
                            $item = $doctrine->getRepository(ShipmentItem::class)->findOneBy(['id' => $itemId]);

                            //check if item exist and edit it 
                            $returnItem = $doctrine->getRepository(ReturnItems::class)->findOneBy(['item_id'=>$item, 'return_id'=>$return]);


                            if($returnItem)
                            {
                                //if returnQuantity from request == 0 then delete item from db
                                
                            
                                    //if return item exist edit it
                                    $returnItem->setReason($reason->getName());
                                    $returnItem->setReturnQuantity($returnQuantity);
                                    $returnItem->setUpdatedAt(new \DateTime());
                                    $entitym->persist($returnItem);
                                    $entitym->flush();

                                    return new JsonResponse('Success! Updated item with id '.$returnItem->getId(), 200);
                                
                                    
                                
                                
                            }
                            else
                            {
                                
                                //add new return item
                                $newReturnItem = new ReturnItems();
                                $newReturnItem->setReason($reason->getName());
                                $newReturnItem->setReturnQuantity($returnQuantity);
                                

                                //check if item exist in db
                                if($item)
                                {
                                    $newReturnItem->setItem($item);
                                    $newReturnItem->setItemName($item->getTitle());
                                }
                                else
                                {
                                    return  new JsonResponse('Not Found item', 404);
                                }

                                $newReturnItem->setCreatedAt(new \DateTime());
                                if(!$return)
                                {
                                    $newReturn = new Returns();
                                    $newReturn->setCountry($country);
                                    $newReturn->setStatus($status);
                                    $newReturn->setReference($reference);
                                    $newReturn->setWebshopOrderId($orderId);
                                    $newReturn->setUserEmail($email);
                                    $newReturn->setClientName($userfromDb->getName());
                                    $newReturn->setClientEmail($userfromDb->getEmail());
                                    $newReturn->setCompanyName($userfromDb->getCompanyName());
                                    $newReturn->setStreet($userfromDb->getStreet());
                                    $newReturn->setPostCode($userfromDb->getPostalCode());
                                    $newReturn->setConfirmed(0);
                                    $newReturn->setCreatedAt(new \DateTime());
                                    
                                    $entitym->persist($newReturn);
                                    $entitym->flush();

                                    //get return id from new return and set it to new item for returning
                                    $newReturnItem->setReturns($newReturn);
                                    $entitym->persist($newReturnItem);
                                    $entitym->flush();
                                    $statuscode = new Response('Created', 200);
                                    return new JsonResponse(['returnId'=> $newReturn->getId(), 'statuscode'=>$statuscode]);
                                }
                                else
                                {
                                    
                                    $newReturnItem->setReturns($return);
                                    $entitym->persist($newReturnItem);
                                    $entitym->flush();

                                    $statuscode = new Response('Created', 201);
                                    return new JsonResponse(['returnId'=> $return->getId(), 'statuscode'=>$statuscode]);
                                }
                            }

                        }
                        else
                        {
                            return new JsonResponse('Not found order', 404);
                        }
                    }
                    else
                    {
                        return new JsonResponse('Server side error', 500);
                    }
                }
                else
                {
                    return new JsonResponse('Not found reason', 404);
                }

               
            }
            else
            {
                return new Response(null, 405);
            }
          
        }
    }

    /**
     * @Route("/return/delete/returnitem", name="delete_return_item")
     */
    public function createmanyreturnItems(Request $request, ManagerRegistry $doctrine)
    {
        $orderId = $request->request->get('orderId');
        $email = $request->request->get('email');
        $reason = $request->request->get('adminreason');
        $returnQuantity = $request->request->get('quantityForReturn');
        $itemId = $request->request->get('itemId');
        
        $return = $doctrine->getRepository(Returns::class)->findOneBy(['user_email'=>$email, 'webshop_order_id'=>$orderId]);
        $returnItem = $doctrine->getRepository(ReturnItems::class)->findOneBy(['return_id'=>$return->getId()]);
        if($returnQuantity == 0)
        {
            
            $entitydelete = $doctrine->getManager();
            $entitydelete->remove($returnItem);
            $entitydelete->flush($returnItem);

            if($entitydelete)
            { 
                //count all returnitems
                $returnItems = $doctrine->getRepository(ReturnItems::class)->findBy(['return_id'=>$return->getId()]);

                if(!$returnItems)
                {
                    $count = 0;
                    return  new JsonResponse(['count'=>$count]);
                }
                else
                {
                    return new JsonResponse('There is still item for returning');
                }
            }
            
        }
      
    }

   /**
    * @Route("/return/confirm/save", name="create_confirmed")
    */
   public function confirmsave(Request $request, ManagerRegistry $doctrine, MailerInterface $mailer)
   {
    $submittedToken = $request->request->get('token');

        
    if ($this->isCsrfTokenValid('confirm-save', $submittedToken)) {

        $orderId = $request->request->get('orderId');
        $email = $request->request->get('email');
        $returnId = $request->request->get('returnId');

        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id'=>$returnId, 'user_email'=>$email, 'webshop_order_id'=>$orderId, 'confirmed'=>0]);
        $st = $doctrine->getRepository(Status::class)->findOneBy(['key_name' => 'in_progress']);
        
        if($return)
        {
            $return->setConfirmed(1);
            $entitym = $doctrine->getManager();
            $entitym->persist($return);
            $entitym->flush();
            
            $email = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status' => $st]);

            $servername = $_SERVER['SERVER_NAME'];

            $order = $doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId' => $orderId]);
            $client = $order->getDeliveryAddress();

            //clien info
            $clientemail = $client->getEmail();
            $clientname = $client->getName();
            $companyname = $client->getCompanyName();
            $street = $client->getStreet();
            $search = array('[webshop_name]', '[webshop_order_id]', '[status]', '[name]', '[address]', '[phone]', '[postal_code]', '[country]');
            $replace = array($servername, $return->getWebshopOrderId(), $st->getName(), $return->getClientName(), $street, '[phone]', $return->getPostCode(), $return->getCountry()->getName());

            $template = $email->getBody();

            $newtemplate = (str_ireplace($search, $replace, $template, $count));

            $email = (new TemplatedEmail())
                ->from('admin@example.com')
                ->to($return->getUserEmail())
                ->subject($email->getSubject())
                ->htmlTemplate('email/status.html.twig')
                ->context([
                    'emailtemplate' => $newtemplate,
                ]);

            try {
                $mailer->send($email);
            } catch (\Exception $e) {
                $contents = $this->renderView('errors/500.html.twig', []);

                return new Response($contents, 500);
            }

           
            
            return $this->redirect('/shipment/'.$orderId. '&' .$return->getId());
        }
        }
        else
        {
            $contents = $this->renderView('errors/500.html.twig', []);

            return new Response($contents, 500);
        }
    
    }

    /**
     * @Route("/return/delete", name="return_delete")
     */
    public function delete(Request $request, ManagerRegistry $doctrine): Response
    {
         $requestis = $request->request->all();
         $array = $requestis['array'];
         
         //delete Returns
         $deleteReturns = $doctrine->getRepository(Returns::class)->findBy(['id'=>$array]);
         
         $returnIds = [];

         foreach($deleteReturns as $ids)
         {
            $returnIds[] = $ids->getId();
         }
        
         $deleteReturnItems = $doctrine->getRepository(ReturnItems::class)->findBy(['return_id'=>$returnIds]);
       
         //delete ReturnItems with id from return which is deleted
         $em = $doctrine->getManager();

         foreach($deleteReturnItems as $dReturnItem)
         {
            $em->remove($dReturnItem);
         }
        
         foreach($deleteReturns as $dReturns)
         {
            $em->remove($dReturns);
           
         }

         try
         {
            //delete all
            $em->flush();
            return new JsonResponse('Success! You delete all successfully ', 200);
         }
         catch (\Exception $e) {

            $res = [];

            return $this->render('errors/500.html.twig', $res, new Response('Something went wrong', 500));
        }
        
    }

}
