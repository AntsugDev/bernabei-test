<?php

namespace App\Security;

use App\Repository\LoginRepository;
use App\Utils\Service\ConsumerService;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthCustAuthenticator  extends AbstractAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    public const COOKIE_NAME = 'app_user';

    private UrlGeneratorInterface $urlGenerator;

    private ConsumerService $service;

    private  LoginRepository $entityManager;



    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param ConsumerService $service
     * @param LoginRepository $entityManager
     * @param Security $security
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, ConsumerService $service, LoginRepository $entityManager, Security $security)
    {
        $this->urlGenerator = $urlGenerator;
        $this->service = $service;
        $this->entityManager = $entityManager;
        $this->security = $security;
    }


    public function supports(Request $request): bool
    {


        $user = $request->request->get('_user');
        $consumer = $this->service;
        dump($user,$consumer);die;
//        return new Passport(
//            new UserBadge($user->getUserIdentifier(),function () use($user,$consumer) {
//                $user = $this->entityManager->validate($user->getUserIdentifier(),$user->getPassword(),$consumer);
//                if(!$user)
//                    throw  new UserNotFoundException("Utente non trovato");
//                return $user;
//            }),
//            new PasswordCredentials($user->getPassword()),
//            [
//                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),]
//        );

    }

    public function authenticate(Request $request): Passport {
        return new Passport();
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response{
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response{
        return null;
    }

//    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
//    {
//
//        try {
//            $cookie = new Cookie(self::COOKIE_NAME,base64_encode($token->getCookies()),0,'/','user',);
//            $response = new RedirectResponse($this->urlGenerator->generate('app_home'));
//            $response->headers->setCookie($cookie);
//            $response->send();
//            return $response;
//        }catch (\AuthenticationException $e){
//            throw new \Exception('Eccezione in fase di autenticazione('.$e->getMessage().')');
//        }
//    }
//
//
//    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
//    {
//        try {
//            return new RedirectResponse($this->urlGenerator->generate(self::LOGIN_ROUTE));
//        }catch (\AuthenticationException $e) {
//            throw new \Exception('Eccezione in fase di autenticazione(' . $exception->getMessage() . ')');
//        }
//    }
//
//    protected function getLoginUrl(Request $request): string
//    {
//        return (string) $request->getUser();
//    }
//
//
//
//    public function start(Request $request, AuthenticationException $authException = null): Response
//    {
//        try {
//            return new RedirectResponse($this->urlGenerator->generate('app_login'));
//        }catch (\AuthenticationException $e) {
//            throw new \Exception('Eccezione in fase di autenticazione(' . $authException->getMessage() . ')');
//        }
//    }


}
