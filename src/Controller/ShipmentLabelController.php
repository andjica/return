<?php

namespace App\Controller;

use App\Entity\Common\Country;
use App\Entity\Returns\Returns;
use App\Entity\Reseller\Address;
use App\Entity\Reseller\ShipmentItem;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use App\Entity\Returns\ReturnSettings;
use App\Form\ActiveShippingOptionType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Returns\ShippingOptionSettings;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Constraints\Count;

class ShipmentLabelController extends AbstractController
{
    private $data = [];
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {


        $rs = $doctrine->getRepository(ReturnSettings::class)->findLastInserted();

        if (!$rs) {
            return $this->redirect('/return/notfound');
        }
        $this->data['imagelogo'] = $rs->getImageLogo();
        $this->data['imagebackground'] = $rs->getImageBackground();
        $this->data['title'] = $rs->getTitle();

        $this->doctrine = $doctrine;

    }
    /**
     * @Route("/shippment/{webshopOrderId}&{returnId}", methods={"GET", "POST"}, name="shippment")
     */
    public function getshippment(Request $request, string $webshopOrderId, int $returnId): Response
    {
        
    
        $activeShippingOption = $this->doctrine->getRepository(ShippingOptionSettings::class)->findBy(['enabled'=>1]);
        
        $madedReturn = $this->doctrine->getRepository(Returns::class)->findOneBy(['webshop_order_id'=>$webshopOrderId]);
        
        $findUser = $this->doctrine->getRepository(Address::class)->findOneBy(['email'=>$madedReturn->getUserEmail()]);
        $country = $this->doctrine->getRepository(Country::class)->findOneBy(['id'=>$findUser->getCountry()]);
       
        $returnInfoSetting = $this->doctrine->getRepository(ReturnSettings::class)->findOneBy([]);
    
        $form = $this->createForm(ActiveShippingOptionType::class);


        $findReturn = $this->doctrine->getRepository(Returns::class)->findOneBy(['webshop_order_id'=> $webshopOrderId]);
        $item = $this->doctrine->getRepository(ShipmentItem::class)->findOneBy(['id'=>$findReturn->getItem()->getId()]);

       

        $form->handleRequest($request);
        if($form->isSubmitted())
        {
           $selectedOption = $request->request->get('shippingoption');

           $currentroute = $request->headers->get('referer');
           if(!$selectedOption)
            {
                $this->addFlash('er-select', 'Shipping option is required field');
                return $this->redirect($currentroute);        
            }
            elseif($selectedOption == 0)
            {
                $this->addFlash('er-select', 'There is no shipping option with id 0');
                return $this->redirect($currentroute);       
            }
            else
            {
                
                $shippingOption = $this->doctrine->getRepository(ShippingOptionSettings::class)->findOneBy(['shipping_option_id'=>$selectedOption]);
               
                date_default_timezone_set('UTC'); 
                $time_stamp = date('c');
                $uri = 'http://aaparceld:11000/nl/api/shipment/create';
            
                $data = [
                    'shipping_option' => $shippingOption->getShippingOptionKeyName(),
                    'sender_address' => false,
                    'return_address' => [
                        'country' => $returnInfoSetting->getCountry()->getName(),
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
                    'country' => $country->getName(),
                    'customer_id' =>  $findUser->getId(),
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
                    'webshop_order_id' => $webshopOrderId,
                    'items' => [
                        [
                            'sku' => $item->getSku(),
                            'qty' => $findReturn->getReturnQuantity(),
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
               
                $public_key = '4569912fc1fd4418e252585d9212688e';
                $secret_key = 'fcd2606e6e1bde3ebcca0a63743b1866';
                $method = 'POST';
                $hash_string = $public_key . '|' . $method . '|' . $uri . '|' . $post_data . '|' . $time_stamp;
                $hash = hash_hmac('sha512', $hash_string, $secret_key);
                $curl = curl_init();

                curl_setopt($curl, CURLOPT_URL, 'http://docker.for.mac.localhost:13000' . $uri);
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

                print_r($response);
                $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

                if (curl_errno($curl)) {
                    echo 'Error curl: ' . curl_error($curl);
                }

                die;
            }
        }

        return $this->renderForm('shipping_label/index.html.twig', 
        [
            'imagelogo'=>$this->data['imagelogo'],
            'imagebackground'=>$this->data['imagebackground'],
            'shotpions' => $activeShippingOption,
            'madedReturn' => $madedReturn,
            'finduser' => $findUser,
            'country' => $country,
            'returninfo' => $returnInfoSetting,
            'form' => $form
        ]);
    }

    
}
