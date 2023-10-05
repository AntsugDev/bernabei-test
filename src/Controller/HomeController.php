<?php

namespace App\Controller;

use App\Entity\Table;
use App\Form\TableSearchType;
use App\Utils\Service\ConsumerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(ConsumerService $service,Request $request): Response
    {



        $post = $request->request->all();


        $table = new Table();

        if(count($post) > 0){
            $table->setNrPage($post['searchTable']['nrPage']);
            $table->setSize($post['searchTable']['size']);
            $table->setOrder($post['searchTable']['order']);
            $table->setSortBy($post['searchTable']['sortBy']);
            $table->setTitle($post['searchTable']['title']);
            $table->setDescription($post['searchTable']['description']);
        }else{
            $table->setNrPage(0);
            $table->setSize(10);
            $table->setOrder('DESC');
            $table->setSortBy('title');
        }


        $queryString = $table->getQueryString();
        $body        = $table->getBody();


        $form = $this->createForm(TableSearchType::class,null,['table' => $table]);


        $table = $service->requestConsumer($queryString,$body);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'table' => $table['content'],
            'form' => $form->createView(),


        ]);
    }
}
