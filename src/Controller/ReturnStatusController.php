<?php

namespace App\Controller;

use App\Entity\Status;
use App\Entity\Country;
use App\Entity\EmailTemplate;
use App\Entity\Returns;
use App\Entity\ReturnStatus;
use App\Entity\ReturnSettings;
use App\Entity\ResellerAddress;
use App\Entity\ResellerShipments;
use Symfony\Component\Mime\Email;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReturnStatusController extends AbstractController
{

    /**
     * @Route("/return={returnId}/status={statusId}", methods={"GET", "POST"}, name="action_return_status")
     */
    public function index(ManagerRegistry $doctrine, int $returnId, $statusId, MailerInterface $mailer): Response
    {
       
        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id'=>$returnId]);
        $status = $doctrine->getRepository(Status::class)->findOneBy(['id'=>$statusId]);

        if(!$return || (!$status))
        {
            $response = new Response();
            $response->setStatusCode(404);

            return $this->render('errors/404.html.twig', [], $response);
        }
        
        else
        {
           
            $return->setStatusId($status->getId());

            $entityManager = $doctrine->getManager();
            $entityManager->persist($return);
            $entityManager->flush();

            $returnstatus = new ReturnStatus();
            
            $returnstatus->setReturnId($return->getId());
            $returnstatus->setStatusId($status->getId());

            $returnstatus->setStatus($status);
            $returnstatus->setCreatedAt(new \DateTime());

            $entityManager->persist($returnstatus);
            

            $webshop = $doctrine->getRepository(ResellerShipments::class)->findOneBy(['webshopOrderId'=>$return->getWebShopOrderId()]);
            $reseleraddress = $doctrine->getRepository(ResellerAddress::class)->findOneBy(['id'=>$webshop->getDeliveryAddressId()]);
            $city = $reseleraddress->getCity();
            $postal_code = $reseleraddress->getPostalCode();
            $phone = $reseleraddress->getPhone();
            $street = $reseleraddress->getStreet();
            
            //find country
            $findcountry = $doctrine->getRepository(Country::class)->findOneBy(['id'=>$reseleraddress->getCountryId()]);
            $country = $findcountry->getName();
           

            $rs = $doctrine->getRepository(ReturnSettings::class)->findLastInserted();
    
            $imagelogo = $rs->getImageLogo();
            $emailtemplate = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status'=>$status->getId()]);
            
           

           
                $entityManager->flush();

                $email = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status'=>$status]);

                // $emailtemplate = $email->getBody();

             
                
                // if($emailtemplate)
                // {
                //     return $this->redirectToRoute('app_email_template', ['status'=>$status->getId(), 'data'=> $datanew], Response::HTTP_SEE_OTHER);
                // }
                $servername = $_SERVER['SERVER_NAME'];
                $search = array('[webshop_name]','[webshop_order_id]', '[status]', '[name]', '[address]', '[phone]', '[postal_code]', '[city]', '[country]');
                $replace = array($servername, $return->getWebshopOrderId(), $status->getName(), $return->getClientName(), $city.', '.$street, $phone, $postal_code, $city, $country);
                    
                $template = $email->getBody();
                $sendtemplateCustom = (str_ireplace($search, $replace, $template, $count));
                
            try{
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
                return $this->redirectToRoute('app_client');
            }
            catch(\Exception $e)
            {
                return  new Response ("Something went wrong", 500);
            }
           
            
        }


    }
}
