<?php

namespace App\Controller;

use App\Entity\Login;
use App\Form\LoginFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(): Response
    {

        $entity = new Login();
        $view = $this->createForm(LoginFormType::class,$entity);


        if ($view->isSubmitted() && $view->isValid()) {
            $data = $view->getData();
            dump($data);die;
        }

        return $this->render('login/index.html.twig', [
            'controller_name' => 'LoginController',
            'form' => $view->createView()
        ]);
    }
}
