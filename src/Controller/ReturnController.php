<?php

namespace App\Controller;

use App\Entity\Common\Country;
use App\Entity\Reseller\Shipment;
use App\Entity\Reseller\ShipmentItem;
use App\Entity\Returns\EmailTemplate;
use App\Entity\Returns\ReasonSettings;
use App\Entity\Returns\ReturnImages;
use App\Entity\Returns\Returns;
use App\Entity\Returns\ReturnStatus;
use App\Entity\Returns\ReturnVideos;
use App\Entity\Returns\Status;
use App\Form\ReturnEditType;
use App\Form\ReturnType;
use App\Repository\Returns\ReturnsRepository;
use Doctrine\Persistence\ManagerRegistry;
use Egulias\EmailValidator\Result\Reason\Reason;
use Psr\Log\LoggerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

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

                return $this->redirectToRoute('returns');
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

        $form = $this->createForm(ReturnEditType::class, $return);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
           
        
           $requestedit = $request->get('return_edit');
           $reference = $requestedit['reference'];
           $orderId = $requestedit['order_id'];
           $statusId = $requestedit['status'];
           $reason = $requestedit['reasons'];
           $status = $doctrine->getRepository(Status::class)->findOneBy(['id'=>$statusId]);
           $clientname = $requestedit['client_name'];
           $clientemail = $requestedit['client_email'];
           $companyname = $requestedit['company_name'];
           $street = $requestedit['street'];
           $postalcode = $requestedit['postal_code'];


           $return->setReference($reference);
           $return->setWebShopOrderId($orderId);
           $return->setStatus($status);
           $return->setReason($reason);
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
            'return' => $return
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


}
