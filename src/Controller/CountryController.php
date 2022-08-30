<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CountryController extends AbstractController
{

    /**
     * @Route("/country", methods={"GET", "POST"}, name="app_country")
     */
    public function index(): Response
    {
        return $this->render('country/index.html.twig', [
            'controller_name' => 'CountryController',
        ]);
    }
}
