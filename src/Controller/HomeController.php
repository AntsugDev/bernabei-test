<?php

namespace App\Controller;

use App\Utils\Service\ConsumerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ConsumerService $service): Response
    {
        $queryString = [];
        $body        = [];



        $table = $service->requestConsumer($queryString,$body);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
