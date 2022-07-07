<?php

namespace App\Controller;

use App\Entity\PayCategory;
use App\Entity\Status;
use App\Entity\Returns;
use App\Entity\Payments;
use App\Entity\ReturnSettings;
use App\Form\ReturnSettingsType;
use Symfony\Component\Form\FormEvent;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\ReturnSettingsRepository;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

 /**
    * @Route("/settings")
*/
class ReturnSettingsController extends AbstractController
{
    private $data = [];
    private $payments = [];

    public function __construct(ManagerRegistry $doctrine)
    {

        //everybody will extends status for vertical sidebar -- for email template customization
        $this->data = $doctrine->getRepository(Status::class)->findAll();
        $this->payments = $doctrine->getRepository(PayCategory::class)->findAll();

  
    }

    /**
     * @Route("/", name="app_return_settings_index", methods={"GET"})
     */
    public function index(ReturnSettingsRepository $returnSettingsRepository): Response
    {
        return $this->render('return_settings/index.html.twig', [
            'return_settings' => $returnSettingsRepository->findAll(),
        ]);
    }
    /**
     * @Route("/create", name="app_return_settings_new")
     */
    public function new(ManagerRegistry $doctrine, Request $request, ReturnSettingsRepository $returnSettingsRepository, SluggerInterface $slugger): Response
    {
        $settings = $doctrine->getRepository(ReturnSettings::class)->findAll();
        
        if($settings)
        {
           return  $this->redirectToRoute('app_return_settings_edit');
        }

        $returnSetting = new ReturnSettings();
        $form = $this->createForm(ReturnSettingsType::class, $returnSetting);
        $form->handleRequest($request);

        $logoimage = $form->get('image_logo')->getData();
        $backgroundimage = $form->get('image_background')->getData();


        if ($form->isSubmitted() && $form->isValid()) {
  
            if($logoimage)
            {
                $originalFilename = pathinfo($logoimage->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newLogo = $safeFilename.'-'.uniqid().'.'.$logoimage->guessExtension();

                
                try {
                    $logoimage->move(
                        $this->getParameter('return_settings_images'),
                        $newLogo
                    );
                } catch (FileException $e) {

                    $e = $this->renderView('errors/500.html.twig', []);
                    return new Response($e, 500);
                }
            }
            if($backgroundimage)
            {
                $originalFilename = pathinfo($backgroundimage->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newBackground = $safeFilename.'-'.uniqid().'.'.$backgroundimage->guessExtension();

                
                try {
                    $backgroundimage->move(
                        $this->getParameter('return_settings_images'),
                        $newBackground
                    );
                } catch (FileException $e) {

                    $e = $this->renderView('errors/500.html.twig', []);
                    return new Response($e, 500);
                }
            }
            $returnSetting->setImageLogo($newLogo);
            $returnSetting->setImageBackground($newBackground);

            $returnSettingsRepository->add($returnSetting);

            return $this->redirectToRoute('app_return_settings_edit', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('return_settings/new.html.twig', [
            'return_setting' => $returnSetting,
            'form' => $form,
            'status' => $this->data,
            'payments' => $this->payments
        ]);
    }

   
    /**
     * @Route("/edit", name="app_return_settings_edit", methods={"GET", "POST"})
     */
    public function edit(ManagerRegistry $doctrine, Request $request,  ReturnSettingsRepository $returnSettingsRepository, SluggerInterface $slugger): Response
    {
       
        
        $returnSetting = $doctrine->getRepository(ReturnSettings::class)->findOneBy([]);

        if(!$returnSetting)
        {
            return $this->redirectToRoute('app_return_settings_new');
        }
        // $logo = $returnSetting->getImageLogo();
        // $background = $returnSetting->getImageBackground();

       
        $form = $this->createForm(ReturnSettingsType::class, $returnSetting);
        $form->handleRequest($request);
        
        if ($form->isSubmitted()) {
           
          
            $images = $request->files->all();
           
            $logoimage = $images['return_settings']['image_logo'];
            $backgroundimage =  $images['return_settings']['image_background'];
          
            if($logoimage)
            {
               
                $originalFilename = pathinfo($logoimage->getClientOriginalName(), PATHINFO_FILENAME);
                
                $safeFilename = $slugger->slug($originalFilename);
                $newLogo = $safeFilename.'-'.uniqid().'.'.$logoimage->guessExtension();
                
                
                try {

                    $lastimage = $returnSetting->getImageLogo();
                    $fs = new Filesystem();
                    $fs->remove($this->getParameter('return_settings_images').'/'.$lastimage);
                
                    $logoimage->move(
                        $this->getParameter('return_settings_images'),
                        $newLogo
                    );
                   
                    $returnSetting->setImageLogo($newLogo);

                } catch (FileException $e) {

                    $e = $this->renderView('errors/500.html.twig', []);
                    return new Response($e, 500);
                }

                
            }
            
            
            

          
            if($backgroundimage)
            {
                $originalFilename = pathinfo($backgroundimage->getClientOriginalName(), PATHINFO_FILENAME);
                    
                $safeFilename = $slugger->slug($originalFilename);
                $newBackground = $safeFilename.'-'.uniqid().'.'.$backgroundimage->guessExtension();
               
                try {
                    $backgroundimage->move(
                        $this->getParameter('return_settings_images'),
                        $newBackground
                    );

                    $returnSetting->setImageBackground($newBackground);
                } catch (FileException $e) {

                    $e = $this->renderView('errors/500.html.twig', []);
                    return new Response($e, 500);
                }
            }
           
            
           
            $returnSettingsRepository->add($returnSetting);
            return $this->redirectToRoute('app_return_settings_edit', [], Response::HTTP_SEE_OTHER);
        }
           
           
          
        

        return $this->renderForm('return_settings/edit.html.twig', [
            'return_setting' => $returnSetting,
            'form' => $form,
            'status' => $this->data,
            'payments' => $this->payments
        ]);
    }

   
}
