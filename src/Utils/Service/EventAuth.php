<?php

namespace App\Utils\Service;

use App\Client\ConsumerClient;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\FinishRequestEvent;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Security\Core\Security;

class EventAuth implements EventSubscriberInterface
{

    private Security $security;

    /**
     * @param Security $security
     */
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'validateToken',
            KernelEvents::FINISH_REQUEST => 'CheckController',
        ];
    }


    public function CheckController(FinishRequestEvent $event)
    {
        try {


        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }

    public function validateToken(RequestEvent $requestEvent)
    {



        try {

            $request  = $requestEvent->getRequest();
            if (stristr($request->getRequestUri(), 'auth') !== false) {
                $token =  $request->headers->has('autorizzation')  && !is_null($request->headers->get('autorizzation'));
                if (!$token) {
                    $requestEvent->setResponse(new RedirectResponse('/error-cust?status=404&msg=' . base64_encode('Richiesta errata, token non presente')));
                }
            }
//            else if (stristr($request->getRequestUri(), 'home') !== false) {
//                if(!$this->security->isGranted(['ROLE_USER','ROLE_ADMIN'])){
//                    $requestEvent->setResponse(new RedirectResponse('/error?status=401&msg=' . base64_encode('Non si è autorizzati ad accedere alla pagina home, senza essere loggati')));
//                }
//            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
    }
}
