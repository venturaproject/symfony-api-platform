<?php

namespace App\Shared\Infrastructure\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomepageController extends AbstractController
{

    #[Route('/', name: 'app_homepage')]
    public function index(): Response
    {
      
        $healthData = [
            'status' => 'ok',
            'timestamp' => date('Y-m-d H:i:s'),
     
        ];

        return $this->render('homepage/index.html.twig', [
            'health' => $healthData,
        ]);
    }
}
