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

    
   
    /**
     * Make sure the request is authorized by matching the auth_token from the URL to the secret. This secret can be found in your custom app configuration.
     */
    if ($_GET['auth_token'] !== 'hwXdLExmwgEmlTxkTWsftVCa1') {
        exit('Unauthorized');
    }
    
    /**
     * Grab the ticket ID from the URL. Trengo automatically appends this ID to the URL.
     */
    $ticket_id = $_GET['ticket_id'];
    
    /**
     * Fetch the complete ticket using the Rest API. 
     */
    $ch = curl_init("https://app.trengo.com/api/v2/tickets/".$ticket_id);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, false);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiIxIiwianRpIjoiNDEzZTZjMDE3MTQ3ZjRhZjcxZGY1NjExYjhhMjViZjNiYTg5M2Y4M2U1MmQ1YmJhMTIwMjc0MTNmMjdmZTdmZGExNmUyN2FhODNiMGM1M2YiLCJpYXQiOjE2Njc4MTYwNzEuMDM5MjQ5LCJuYmYiOjE2Njc4MTYwNzEuMDM5MjUxLCJleHAiOjQ3OTE5NTM2NzEuMDM0Mzg1LCJzdWIiOiIyOTUyMTMiLCJzY29wZXMiOltdfQ.hPyd6kvR4kPxMLiUfrOAeDx_290qYr9MUwGcF53aIqoiuF0_WcQQuMIwp6y-vmXORKhcM3f-kJ0DbiwPkNQSbQ',
        'Content-Type: application/json',
        'Accept: application/json',
    ]);
    $ticket = json_decode(curl_exec($ch), true);
    
    /**
     * Do whatever you want with $ticket 
     */
    print_r($ticket);
    
   
    
   
    
  /**
   * Grab the ticket ID from the URL. Trengo automatically appends this ID to the URL.
   */
  // $ticket_id = $_GET['ticket_id'];
  
  }
}
