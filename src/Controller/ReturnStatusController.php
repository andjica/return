<?php

namespace App\Controller;

use App\Entity\Reseller\Shipment;
use App\Entity\Returns\EmailTemplate;
use App\Entity\Returns\Returns;
use App\Entity\Returns\ReturnSettings;
use App\Entity\Returns\ReturnStatus;
use App\Entity\Returns\Status;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

class ReturnStatusController extends AbstractController
{

    /**
     * @Route("/return={returnId}/status={statusId}", methods={"GET", "POST"}, name="action_return_status")
     */
    public function index(ManagerRegistry $doctrine, int $returnId, $statusId, MailerInterface $mailer): Response
    {

        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id' => $returnId]);
        $status = $doctrine->getRepository(Status::class)->findOneBy(['id' => $statusId]);

        if (!$return || (!$status)) {
            $response = new Response();
            $response->setStatusCode(404);

            return $this->render('errors/404.html.twig', [], $response);
        } else {


            $return->setStatus($status);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($return);
            $entityManager->flush();

            $returnstatus = new ReturnStatus();

            $returnstatus->setReturns($return);

            $returnstatus->setStatus($status);
            $returnstatus->setCreatedAt(new \DateTime());

            $entityManager->persist($returnstatus);


            $webshop = $doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId' => $return->getWebShopOrderId()]);
            $reseleraddress = $webshop->getDeliveryAddress();
            $city = $reseleraddress->getCity();
            $postal_code = $reseleraddress->getPostalCode();
            $phone = $reseleraddress->getPhone();
            $street = $reseleraddress->getStreet();

            //find country
            $findcountry = $reseleraddress->getCountry();
            $country = $findcountry->getName();


            $rs = $doctrine->getRepository(ReturnSettings::class)->findLastInserted();

            $imagelogo = $rs->getImageLogo();
            $emailtemplate = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status' => $status->getId()]);


            $entityManager->flush();

            $email = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status' => $status]);

            // $emailtemplate = $email->getBody();


            // if($emailtemplate)
            // {
            //     return $this->redirectToRoute('app_email_template', ['status'=>$status->getId(), 'data'=> $datanew], Response::HTTP_SEE_OTHER);
            // }
            $servername = $_SERVER['SERVER_NAME'];
            $search = array('[webshop_name]', '[webshop_order_id]', '[status]', '[name]', '[address]', '[phone]', '[postal_code]', '[city]', '[country]');
            $replace = array($servername, $return->getWebshopOrderId(), $status->getName(), $return->getClientName(), $city . ', ' . $street, $phone, $postal_code, $city, $country);

            $template = $email->getBody();
            $sendtemplateCustom = (str_ireplace($search, $replace, $template, $count));

            try {
                $email = (new TemplatedEmail())
                    ->from('admin@example.com')
                    ->to($return->getUserEmail())
                    ->subject($email->getSubject())
                    ->htmlTemplate('email/status.html.twig')
                    ->context([
                        'emailtemplate' => $sendtemplateCustom,
                    ]);

                $mailer->send($email);
                $this->addFlash('success', 'You made changes successfully :)');
                return $this->redirectToRoute('returns');
            } catch (\Exception $e) {
                return new Response ("Something went wrong", 500);
            }


        }


    }
}
