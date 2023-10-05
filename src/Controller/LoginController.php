<?php

namespace App\Controller;

use App\Security\AuthCustAuthenticator;
use App\Security\WsUser;
use App\Utils\Service\AuthService;
use App\Utils\Service\ConsumerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Form\LoginFormType;
use App\Entity\Login;

class LoginController extends AbstractController
{
    #[Route('/', name: 'app_login')]
    public function index(Request $request,ConsumerService $service,AuthCustAuthenticator $authCustAuthenticator): Response
    {
        $error = [];
        $entity = new Login();
        $form = $this->createForm(LoginFormType::class,null,['entity' => $entity]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {
            $response = $service->getUser($form->getData()->getEmail(),$form->getData()->getPassword());
            if(count($response) >0){
                $userInteface = new WsUser($response['username'],$response['pwd'],$response['id'],$response['lastname'],$response['firstaname']);
                $request->request->set('_user',$userInteface);
                return $authCustAuthenticator->onAuthenticationSuccess($request,$userInteface,"FIREWALL-USER");
            }else{
                $error['messageKey'] ="Utente non trovato";
                $error['messageData'] =date('d/m/Y H:i:s',time());
            }
        }

        return $this->render('security/login.html.twig', [
            'error' => $error,
            'username' => $entity->getEmail(),
            'form' => $form->createView()
        ]);
    }
}