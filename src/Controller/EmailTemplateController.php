<?php

namespace App\Controller;

use App\Entity\Email;
use App\Entity\Status;
use Psr\Log\LoggerInterface;
use App\Entity\EmailTemplate;
use App\Entity\ReturnSettings;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class EmailTemplateController extends AbstractController
{
    private $data = [];
    private $imagelogo = [];

    public function __construct(ManagerRegistry $doctrine)
    {

        //everybody will extends status for vertical sidebar -- for email template customization
        $this->data = $doctrine->getRepository(Status::class)->findAll();

        //imagelogo
        $rs = $doctrine->getRepository(ReturnSettings::class)->findLastInserted();
    
        $this->imagelogo = $rs->getImageLogo();
  
    }

    /**
     * @Route("settings/email/{name}", methods={"GET", "POST"}, name="app_email")
     */
    public function index(string $name, ManagerRegistry $doctrine): Response
    {

        $findstatus = $doctrine->getRepository(Status::class)->findOneBy(['key_name' => $name]);
        
        $email = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status'=>$findstatus->getId()]);
        
        if(!$email)
        {
            $data = [
                'name' => $name,
                'status' => $this->data,
                'imagelogo' => $this->imagelogo,
                'findstatus' => $findstatus
            ];
            
            return $this->render('email/index.html.twig', $data);
        }
        else
        {
            return $this->redirectToRoute('app_email_template_edit', ['status'=>$findstatus->getKeyName()], Response::HTTP_SEE_OTHER);

            
        }
        
       
    }

    /**
     * @Route("/email/create/{name}", methods={"GET", "POST"}, name="app_email_create_template")
     */
    public function andjica(string $name, ManagerRegistry $doctrine, Request $request)
    {
       
        // return dd($request->request->all());
        $templatecustom = $request->request->get('templatecustom');

        $statusId = $request->request->get('statusId');
        $status = $doctrine->getRepository(Status::class)->findOneBy(['id'=> $statusId]);
        

        //subject
        $subject = $request->request->get('subject');
      
        //search in template
        $search = array('[webshop_order_id]', '[status]', '[name]', '[address]', '[phone]', '[postal_code]', '[city]', '[country]');

        //replace with another word
        $replace = array('{{webshop_order_id}}', '{{status}}', '{{name}}', '{{address}}', '{{phone}}', '{{postal_code}}', '{{city}}', '{{country}}');

        $currentroute = $request->headers->get('referer');
     
        if($subject == "")
        {
            $this->addFlash('errors', 'Subject  is required field');
            return $this->redirect($currentroute);
        }
        
        $newEmail = new EmailTemplate();
        
        $shablon = $request->request->get('shablon');
        
        if($shablon == "custom")
        {
            $newEmail->setBody($templatecustom);
            
        }
       else
       {
           
            $templateuser = $request->request->get('templateuser');
            $newEmail->setBody($templateuser);

            $background = $request->request->get('background');
            
            $newEmail->setBackgroundColor($background);

       }

       $newEmail->setStatus($status);
       $newEmail->setSubject($subject);
       $newEmail->setTemplateShablon($shablon);
   
       $entityManager = $doctrine->getManager();
       $entityManager->persist($newEmail);

       $entityManager->flush();

       $newEmail->getId();

      
       return $this->redirectToRoute('app_email_template_edit', ['status'=>$status->getKeyName()], Response::HTTP_SEE_OTHER);


        
           
    }

    /**
     * @Route("settings/email-template/edit/{status}", methods={"GET", "POST"}, name="app_email_template_edit")
     */
    public function edit(string $status, ManagerRegistry $doctrine)
    {
        $findstatus = $doctrine->getRepository(Status::class)->findOneBy(['key_name' => $status]);
        $email = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['status' => $findstatus->getId()]);
        
        $data = [
            'email' => $email,
            'status' => $this->data,
            'imagelogo' => $this->imagelogo,
            'findstatus' => $findstatus
        ];
        return $this->render('/email/edit-email.html.twig',$data);
    }

    /**
     * @Route("email-template/update/", methods={"GET", "POST"}, name="email_update")
     */
    public function update(ManagerRegistry $doctrine, Request $request, LoggerInterface $logger)
    {

        $token = $request->request->get('token');

        if (!$this->isCsrfTokenValid('update-email', $token))
        {
            $logger->info("CSRF failure");

            return new Response("Operation not allowed",  Response::HTTP_BAD_REQUEST,
                ['content-type' => 'text/plain']);
        }

        //proveri ovaj deo 
        $host = "https://".$request->getHost().":8000/";

        $emailId = $request->request->get('emailId');

        $editEmail = $doctrine->getRepository(EmailTemplate::class)->findOneBy(['id'=>$emailId]);
        
        $statusId = $request->request->get('statusId');
        $status = $doctrine->getRepository(Status::class)->findOneBy(['id'=> $statusId]);
        
        $currentroute = $request->headers->get('referer');

        $subject = $request->request->get('subject');
        if($subject == "")
        {
            $this->addFlash('errors', 'Subject  is required field');
            return $this->redirect($currentroute);
        }
        $shablon = $request->request->get('shablon');
        
        $templatecustom = $request->request->get('templatecustom');

        $search = array('src="../../');
        $replace = array('src="'.$host);
        

        if($shablon == "custom")
        {
           
            $newTemplateCustom = (str_ireplace($search, $replace, $templatecustom, $count));
           
            $editEmail->setBody($templatecustom);
            
        }
        else
        {
            
            $templateuser = $request->request->get('templateuser');
            $newTemplateUser = (str_ireplace($search, $replace, $templatecustom, $count));
            $editEmail->setBody($newTemplateUser);

            $background = $request->request->get('background');
            $editEmail->setBackgroundColor($background);

        }

        
        $editEmail->setStatus($status);
        $editEmail->setSubject($subject);
        $editEmail->setTemplateShablon($shablon);
    
        $entityManager = $doctrine->getManager();
       
 
        try{
            $entityManager->persist($editEmail);
            $entityManager->flush();

            $this->addFlash('success', 'You made changes successfully');
            return $this->redirect($currentroute);
        }
        catch(\Exception $e)
        {
            
            $res = [];

            return $this->render('errors/500.html.twig', $res,  new Response('Something went wrong', 500));
        }
    }



    




}
