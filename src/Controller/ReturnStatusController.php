<?php

namespace App\Controller;

use App\Entity\Common\Country;
use App\Entity\Returns\Status;
use App\Entity\Returns\Returns;
use App\Entity\Reseller\Address;
use App\Entity\Reseller\Shipment;
use Symfony\Component\Mime\Email;
use App\Entity\Returns\ReturnStatus;
use App\Entity\Reseller\ShipmentItem;
use App\Entity\Returns\EmailTemplate;
use App\Entity\Returns\ReturnSettings;
use App\Entity\Returns\ReturnShipments;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\Returns\ShippingOptionSettings;
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

        $return = $doctrine->getRepository(Returns::class)->findOneBy(['id' => $returnId]);
        $status = $doctrine->getRepository(Status::class)->findOneBy(['id' => $statusId]);
        $email = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status' => $status]);
        $returnInfoSetting = $doctrine->getRepository(ReturnSettings::class)->findOneBy([]);
        $country = $doctrine->getRepository(Country::class)->findOneBy(['id' => 147]);

        $findUser = $doctrine->getRepository(Address::class)->findOneBy(['email'=>$return->getUserEmail()]);
        $item = $doctrine->getRepository(ShipmentItem::class)->findOneBy(['id'=>$return->getItem()->getId()]);

        if (!$return || (!$status)) {
            $response = new Response();
            $response->setStatusCode(404);

            return $this->render('errors/404.html.twig', [], $response);
        }
        elseif(!$email instanceof EmailTemplate){

            $this->addFlash('alert', 'First, you need to configure email template for: '.$status->getName().' status.');
                return $this->redirectToRoute('returns');
        } else {


            $returnstatusExist = $doctrine->getRepository(ReturnStatus::class)->findOneBy(['returns'=>$return, 'status'=>$status]);
            
            if($returnstatusExist)
            {
                $this->addFlash('alert', 'You have already choosen status: '.$returnstatusExist->getStatus()->getName());
                return $this->redirectToRoute('returns');
    
            }

            
            if($status->getName() == "Accept")
            {

                $shippingOption = $doctrine->getRepository(ShippingOptionSettings::class)->findOneBy([]);
                
                if(!$shippingOption instanceof ShippingOptionSettings)
                {
                    $contents = $this->renderView('errors/500.html.twig', []);

                    return new Response($contents, 500);
                }

                //make Api
                date_default_timezone_set('UTC'); 
                $time_stamp = date('c');
                $uri = '/nl/api/shipment/create';
            
                $data = [
                    'shipping_option' => $shippingOption->getShippingOptionKeyName(),
                    'sender_address' => false,
                    'return_address' => [
                        'country' => $returnInfoSetting->getCountry()->getIsoCode(),
                        'name' => 'Jane Doe',
                        'company_name' => 'Some company, BV',
                        'postal_code' => $returnInfoSetting->getPostCode(),
                        'house_number' => $returnInfoSetting->getHouseNumber(),
                        'house_number_addition' => '',
                        'street' => $returnInfoSetting->getStreet(),
                        'city' => $returnInfoSetting->getCityName(),
                        'phone' => '088-1234567',
                        'email' => 'jd@example.com',
                    ],
                    'country' => $country->getIsoCode(),
                    'customer_id' =>  '',
                    'name' => $findUser->getName(),
                    'company_name' => $findUser->getCompanyName(),
                    'postal_code' => $findUser->getPostalCode(),
                    'house_number' => $findUser->getHouseNumber(),
                    'house_number_addition' => '',
                    'street' =>  $findUser->getStreet(),
                    'city' => $findUser->getCity(),
                    'phone' => $findUser->getPhone(),
                    'email' => $findUser->getEmail(),
                    'weight' => 1,
                    'labels_num' => 1,
                    'webshop_order_id' => $return->getWebshopOrderId(),
                    'items' => [
                        [
                            'sku' => $item->getSku(),
                            'qty' => $return->getReturnQuantity(),
                            'title' => $item->getTitle(),
                            'location' => '',
                        ], [
                            'sku' => $item->getSku(),
                            'qty' => $item->getQty(),
                            'title' => $item->getTitle(),
                            'location' => $item->getLocation(),
                            'weight' => $item->getWeight(),
                            'length' => $item->getLength(),
                            'width' => $item->getWidth(),
                            'height' => $item->getHeight(),
                            'price' => $item->getPrice(),
                        ]
                    ],
                ];

                $post_data = json_encode($data);
                $resselerShipment = $doctrine->getRepository(Shipment::class)->findOneBy(['webshopOrderId'=>$return->getWebShopOrderId()]);
                if($resselerShipment instanceof Shipment) {
                        
                    // $public_key = '4569912fc1fd4418e252585d9212688e';
                     // $secret_key = 'fcd2606e6e1bde3ebcca0a63743b1866';
                     $public_key = $resselerShipment->getDomain()->getApiPublicKey();
                     $secret_key = $resselerShipment->getDomain()->getApiSecretKey();
                     $method = 'POST';
                     $hash_string = $public_key . '|' . $method . '|' . $uri . '|' . $post_data . '|' . $time_stamp;
                     $hash = hash_hmac('sha512', $hash_string, $secret_key);
                     $curl = curl_init();
                     // return dd($this->getParameter('aaparcel_domain'));
                     // $this->getParameter('aaparcel_domain')
                     curl_setopt($curl, CURLOPT_URL,$this->getParameter('aaparcel_domain'). $uri);
                     curl_setopt($curl, CURLOPT_POST, true);
                     curl_setopt($curl, CURLOPT_HTTPHEADER, array(
                         'Content-Type: application/json',
                         'charset: utf-8',
                         'x-date: ' . $time_stamp,
                         'x-public: ' . $public_key,
                         'x-hash:' . $hash,
                     ));
                     curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
                     curl_setopt($curl, CURLOPT_HEADER, false);
                     curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl, CURLOPT_FAILONERROR, true);
                     $response = curl_exec($curl);
                     // return dd($response);
                     // print_r($response);
                     $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);
     
                     if (curl_errno($curl)) {
                         echo 'Error curl: ' . curl_error($curl);
                     }
     
                     
                     
                     $uri2 = '/nl/api/shipment/labels';
                     $data2 = [
                         'shipment_ids' => [$resselerShipment->getId()],
                         'printer' => 'laser_a4'
                     ];
                     $post_data2 = json_encode($data2); 
                     // return dd($post_data2);
                     $hash_string = $public_key . '|' . $method . '|' . $uri2 . '|' . $post_data2 . '|' . $time_stamp;
     
                     $hash = hash_hmac('sha512', $hash_string, $secret_key); 
                     $curl2 = curl_init();
                     curl_setopt($curl2, CURLOPT_URL, $this->getParameter('aaparcel_domain') . $uri2);
                     curl_setopt($curl2, CURLOPT_POST, true);
                     curl_setopt($curl2, CURLOPT_HTTPHEADER, [
                         'Content-Type: application/json',
                         'charset: utf-8',
                         'x-date: ' . $time_stamp,
                         'x-public: ' . $public_key,
                         'x-hash:' . $hash,
                     ]);
                     curl_setopt($curl2, CURLOPT_POSTFIELDS, $post_data2);
                     curl_setopt($curl2, CURLOPT_HEADER, false);
                     curl_setopt($curl2, CURLOPT_RETURNTRANSFER, true);
                     curl_setopt($curl2, CURLOPT_FAILONERROR, true);
                     $response2 = curl_exec($curl2);
                     $http_code = curl_getinfo($curl2, CURLINFO_HTTP_CODE);
                     
                     $responsedecode = json_decode($response2, TRUE);
     
     
                     // return dd($response2);
                     // if($responsedecode == null)
                     // {
                         
                     // }
                     $labelUrl = $responsedecode['success']['labels_url'];
                     
                     $newReturnShipment = new ReturnShipments();
                     $newReturnShipment->setReturnId($return->getId());
                     $newReturnShipment->setShipmentId($resselerShipment->getId());
                     $newReturnShipment->setLabelsUrl($labelUrl);
                     
                     $newReturnShipment->setCreatedAt(new \DateTime());
                     $en = $doctrine->getManager();
                     $en->persist($newReturnShipment);
                     $en->flush();
                     
                     //labelUrl prenesi na sledecu rutu
                    //  if($response2)
                    //  {
                         
                    //      return $this->redirect('/shipment/'.$return->getWebshopOrderId(). '&' .$returnId. '&' .$newReturnShipment->getId(). '/success');
                    //  }
    
                        
                    }
                    else {
                        $contents = $this->renderView('errors/500.html.twig', []);
    
                        return new Response($contents, 500);
                    }
            }

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

                
                if($status->getName() == "Accept")
                {
                    $labelprinted = $doctrine->getRepository(ReturnShipments::class)->findOneBy(['return_id'=>$return->getId()]);
                    $emailLabel = (new TemplatedEmail())
                    ->from('admin@example.com')
                    ->to($return->getUserEmail())
                    ->subject("Your label printed")
                    ->htmlTemplate('email/label-printed.html.twig')
                    ->context([
                        'labelprinted' => $labelprinted->getLabelsUrl(),
                    ]);
                    $mailer->send($emailLabel);
                }

                $mailer->send($email);

                $this->addFlash('success', 'You made changes successfully :)');
                return $this->redirectToRoute('returns');
                
            } catch (\Exception $e) {
                return new Response ("Something went wrong", 500);
            }


        }


    }
}
