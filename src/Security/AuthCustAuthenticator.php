<?php

namespace App\Security;

use PHPUnit\Exception;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthCustAuthenticator  extends AbstractLoginFormAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    public const COOKIE_NAME = 'app_user';

    private UrlGeneratorInterface $urlGenerator;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     */
    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }


    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {

        try {
            $cookie = new Cookie(self::COOKIE_NAME,base64_encode($token->getCookies()),0,'/','user',);
            $response = new RedirectResponse($this->urlGenerator->generate('app_home'));
            $response->headers->setCookie($cookie);
            $response->send();
            return $response;

        }catch (\Exception $e){
            throw new \Exception('Eccezione in fase di autenticazione('.$e->getMessage().')');
        }
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): Response
    {
        try {
            return new RedirectResponse($this->urlGenerator->generate(self::LOGIN_ROUTE));
        }catch (\AuthenticationException $e) {
            throw new \Exception('Eccezione in fase di autenticazione(' . $exception->getMessage() . ')');
        }
    }

    protected function getLoginUrl(Request $request): string
    {
        return (string) $request->getUser();
    }

    public function authenticate(Request $request): Passport
    {
        $user = $request->request->get('_user');
        return new Passport(
            new UserBadge($user->getUserIdentifier()),
            new PasswordCredentials($user->getPassword()),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),            ]
        );
    }

    public function start(Request $request, AuthenticationException $authException = null): Response
    {
        try {
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
        }catch (\AuthenticationException $e) {
            throw new \Exception('Eccezione in fase di autenticazione(' . $authException->getMessage() . ')');
        }
    }


}
