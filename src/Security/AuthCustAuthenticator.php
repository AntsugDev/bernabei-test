<?php

namespace App\Security;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Debug\TraceableEventDispatcher;
use Symfony\Component\PasswordHasher\Exception\InvalidPasswordException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class AuthCustAuthenticator  extends AbstractAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';
    public const COOKIE_NAME = 'app_user';

    private UrlGeneratorInterface $urlGenerator;

    public  const ERROR_USER = "userError";
    public  const ERROR_PWD = "pwdUserError";

    private UserPasswordHasherInterface $hasher;

    private WsUser $user;

    private ContainerInterface $container;




    public const USER = array(
        0 => array(
            "id" => 1,
            "username" => "antonio.sugamele@gmail.com",
            "pwd" => "123456",
            "firstName" => "Antonio",
            "lastName" =>"Sugamele"
        ),
        1 => array(
            "id" => 2,
            "username" => "s.tricarico@bernabei.it",
            "pwd" => "123456",
            "firstName" => "Simone",
            "lastName" =>"Tricarico"
        ),
        2 => array(
            "id" => 3,
            "username" => "tester.dev@gmail.com",
            "pwd" => "123456",
            "firstName" => "Tester",
            "lastName" =>"Dev"
        ),
    );

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param UserPasswordHasherInterface $hasher
     * @param WsUser $user
     * @param Container $container
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, UserPasswordHasherInterface $hasher, WsUser $user, ContainerInterface $container)
    {
        $this->urlGenerator = $urlGenerator;
        $this->hasher = $hasher;
        $this->user = $user;
        $this->container = $container;
    }


    /**
     * @param string $username
     * @param string $password
     * @return bool
     */
    private function validate(string $username,string $password) : WsUser{
        $filter = array_filter(self::USER,function($value) use ($username,$password) {
            return strcmp($value['username'],$username) === 0 && strcmp($value['pwd'],$password) === 0;
        });
        if(count($filter) >0 ){
            $content = $filter[array_keys($filter)[0]];
            $this->user->setId($content['id']);
            $this->user->setUsername($content['username']);
            $this->user->setPassword($content['pwd']);
            $this->user->setFirstname($content['firstName']);
            $this->user->setLastname($content['lastName']);

        }

        return $this->user;
    }

    private function redirectLogin(){
        return new RedirectResponse($this->urlGenerator->generate('app_login'));
    }

    private function passwordString(Request $request) {
        try{
            return $this->hasher->hashPassword($this->user,'abc');
        }catch (InvalidPasswordException $exception){
            $request->getSession()->set(Security::AUTHENTICATION_ERROR,  new AuthenticationException('Eccezione sulla password. (status=password;exception='.$exception->getMessage().')'));
            return $this->redirectLogin();
        }
    }



    public function supports(Request $request): bool
    {
        $all = $request->request->all();
        $form = array_key_exists('formLogin',$all) ?  $all['formLogin'] :[];
        if(count($form) > 0) {
            return true;
        }else return false;

    }



    public function authenticate(Request $request): Passport
    {
        $all = $request->request->all();
        $form = array_key_exists('formLogin',$all) ?  $all['formLogin'] :[];
        if(count($form) > 0) {
            $password = '123456';
            return new SelfValidatingPassport(
                new UserBadge($form['email'], function () use ($form,$password,$request) {
                    try {
                        $content = $this->validate($form['email'], $password);
                        if (!$content){
                            $request->getSession()->set(Security::AUTHENTICATION_ERROR, 'User not found');
                            return $this->redirectLogin();
                        }
                        return $content;
                    }catch (BadRequestException $exception){
                        $request->getSession()->set(Security::AUTHENTICATION_ERROR,  new AuthenticationException('L\'utenza richiesta per la validazione ha prodotto una eccezione. (status=bad;exception='.$exception->getMessage().')'));
                        return $this->redirectLogin();
                    }
                }),
                [
                    new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                ]
            );
        }else
            return new SelfValidatingPassport(
                new UserBadge(self::ERROR_USER),
                [
                    new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
                    ]
            );

    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response{
        try{

//            $preAuth = new UsernamePasswordToken($this->user,self::COOKIE_NAME,$this->user->getRoles());
//           // $this->container->get('security.token_storage')->setToken($preAuth);
//            dump($preAuth);die;
//            $event = new InteractiveLoginEvent($request,$preAuth);
//            $this->container->get('event_dispatcher')->dispatch("security.interactive_login", $event);
            $response = new RedirectResponse($this->urlGenerator->generate('app_home'));
            $response->send();
            return $response;
        }catch (AuthenticationException $exception){
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, new AuthenticationException('Eccezione in fase di autenticazione. (stato=success;exception='.$exception->getMessage().')'));
            return $this->redirectLogin();
        }
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response{
        $request->getSession()->set(Security::AUTHENTICATION_ERROR,  new AuthenticationException('Eccezione in fase di autenticazione. (stato=failure;exception='.$exception->getMessage().')'));
        return $this->redirectLogin();
    }


}
