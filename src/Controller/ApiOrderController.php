<?php

namespace App\Controller;

use App\Entity\Returns\Returns;
use Doctrine\Persistence\ManagerRegistry;
use GuzzleHttp\Client;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ApiOrderController extends AbstractController
{
  /**
   * @Route("/send-to-trengo/{returnId}", name="make_api_trengo")
   */
  public function apiTrengo(int $returnId, ManagerRegistry $doctrine)
  {
   

    $return = $doctrine->getRepository(Returns::class)->findOneBy(['id'=>$returnId]);
    
    $response = new Response();

    $response->headers->set('Access-Control-Allow-Origin', '*');
    
    
    $client = new Client();

        $data = [
                 

                "id"=> $return->getId(),
                "order_id" => []
                
              
        ];
    // $data = [
    //     "id"=> $return->getId(),
    //     "title"=> "return_".$return->getId(),
    //     "sort_order"=> -1,
    //     "type"=> "PROFILE",
    //     "identifier"=> "return_2",
    //     "field_type"=> "NUMBER",
    //     "placeholder"=> null,
    //     "items"=> [],
    //     "channels"=> [],
    //     "is_required"=> true
    // ];

    $data2 = [
        "title" => "return_id_".$return->getId(),
        "type"=> "PROFILE",
        "field_type"=> "NUMBER",
        "identifier"=> "andjaaa95@gmail.com",
        "email" => "andjaaa95@gmail.com",
        "name" => "Andjela Stojanovic Milosevic",
        "custom_fields" => [
            "id" => 5,
            "title" => "return_id_1",
            "type"=> "PROFILE",
            "identifier"=> "return_id_1",
        ]
    ];
    //return dd($data2);
    $post_data = json_encode($data2);
   
    
        $response2 = $client->request('POST', 'https://app.trengo.com/api/v2/ticket_results', [
            'body' => '{"name":"id","sort_order":0}',
            'headers' => [
              'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiYjI0NzIyOTYzNjcwZjdkYzUxODVhOWQ5MWQ2NGFjMGNiY2Y4ZjY2ZjIzNjllZDdiMWU2MjQ3MzUxNmVjNTRjNDg0MTE2YTMyNzQ1ZGIyOWYiLCJpYXQiOjE2NjY2MjIyMzguMzg0ODgxLCJuYmYiOjE2NjY2MjIyMzguMzg0ODgzLCJleHAiOjQ3OTA3NTk4MzguMzcxOTg3LCJzdWIiOiI1NTIwNjkiLCJzY29wZXMiOltdfQ.jpjFPShb06YLQdNFPktISzqMSuLpERX2FtzbOYp6JlaAP3YPv2vINkMsqcQpfoaTmqDDNkY2oe8BBjaxqUmyQA',
              'accept' => 'application/json',
              'content-type' => 'application/json',
            ],
          
    ]);
    // return dd("andjica");
    return  dd($response2);
    // $public_key = "Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiM2RiOWY3ZGZiNTZjOGFmNWYyNTExYzU1NmRkZThhNmU5ZmEzZTFhNGY3MDAzOTQ3MTU3NGQ4OGUxY2Y4MDdiYzA0MDNiZjQxMjI5MTA0Y2EiLCJpYXQiOjE2NjYzNTk4MjguODkyNjk1LCJuYmYiOjE2NjYzNTk4MjguODkyNjk3LCJleHAiOjQ3OTA0OTc0MjguODc3MDc3LCJzdWIiOiI1NTIwNjkiLCJzY29wZXMiOltdfQ.NhEcqjMM7mk8nfgMZ-qfly6zSPGukemYONe4rNZmpRKt3ASk1tCcaMYMRxYO7HLQk6eBebZ4p-tMsJjdI2j9Qg";
    // $data = [
    //     "customer_id" => "Andjica"
    // ];
    // $post_data = json_encode($data);
    // $curl = curl_init();
    // // return dd($this->getParameter('aaparcel_domain'));
    // // $this->getParameter('aaparcel_domain')
    // curl_setopt($curl, CURLOPT_URL,"https://app.trengo.com/api/v2/tickets/5/custom_fields");

    // curl_setopt($curl, CURLOPT_POST, true);
    // curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    //     'Content-Type: application/json',
    //     'charset: utf-8',
    //     'Authorization:'. $public_key,
    // ));
    // curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
    // curl_setopt($curl, CURLOPT_HEADER, false);
    // curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    // curl_setopt($curl, CURLOPT_FAILONERROR, true);
    // $response = curl_exec($curl);
    // // return dd($response);
    // // print_r($response);
    // return dd($http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE));
   
    // if (curl_errno($curl)) {
    //     echo 'Error curl: ' . curl_error($curl);
    // }
     
  }
}
