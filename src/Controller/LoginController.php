<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LoginFormType;
use App\Entity\Login;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(Request $request,AuthenticationUtils  $authenticationUtils): Response
    {


        $entity = new Login();
        $form = $this->createForm(LoginFormType::class,null,['entity' => $entity]);
        $form->handleRequest($request);
        dump($request->getSession());
        $error = $authenticationUtils->getLastAuthenticationError();
        return $this->render('security/login.html.twig', [
            'errorMsg' => !is_null($error) ? $error->getMessage() : null,
            'username' => $entity->getEmail(),
            'form' => $form->createView()
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(Request $request,): Response
    {
        return new RedirectResponse('/?logout');
    }
}
