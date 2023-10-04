<?php


namespace App\Utils\Service;

use App\Entity\Login;
use App\Security\WsUser;
use App\Utils\Responses\UserResponse;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\PreAuthenticatedToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Symfony\Component\Security\Http\Firewall;

class LoginService
{
    use ContainerAwareTrait;

    const COOKIE_USER = 'app_user';
    const TOKEN_STORAGE = 'security.token_storage';
    const FIREWALL =  "firewall.test.bernabei";


    protected $requestStack;

    /**
     * @param ContainerInterface $container
     * @param RequestStack       $requestStack
     */
    public function __construct(ContainerInterface $container, RequestStack $requestStack)
    {
        $this->setContainer($container);
        $this->requestStack = $requestStack;
    }

    /**
     * @throws \Exception
     */
    public function loginUserDev(Login $login, Request $request)
    {
        try {
            $url = $this->container->get('router')->generate('app_home');
            $response = new RedirectResponse($url);
            $this->setUserDev($login->getEmail(), $login->getPassword(), $request, $response);
            return $response;
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            throw new \Exception($msg);
        }
    }

    /**
     * @param $arrayUser
     * @param Request      $request
     * @param Response     $response
     *
     * @return WsUser
     */


    public function setUserDev(string $email, string $password, Request $request, Response $response)
    {

        //todo check api exist user


        $user = $this->buildWsUser($email, $password);
        $token = new PreAuthenticatedToken($user, self::FIREWALL, $user->getRoles());
        //$this->get(self::TOKEN_STORAGE)->setToken($token);
        $event = new InteractiveLoginEvent($request, $token);
        //$request->getSession()->set('user',$user);
       // $this->container->get('event_dispatcher')->dispatch("security.interactive_login", $event);
        $this->saveUserCookieDev($user, $request, 1, $response);
        return $user;
    }
    /**
     * @param UserResponse $userResponse
     * @return WsUser
     */
    private function buildWsUser(string $username, string $password)
    {
        return new WsUser($username, $password);
    }

    /**
     * @param WsUser        $user
     * @param Response|null $response
     *
     * @return $this
     */
    public function saveUserCookieDev(WsUser $user, Request $request, $cdGroup, Response $response = null)
    {
        $newResponse = false;
        if (is_null($response)) {
            $response = new Response();
            $newResponse = true;
        }
        if (is_null($user)) {
            $user = $this->container->get(self::TOKEN_STORAGE)->getToken()->getUser();
        }
        $data = $user->serialize();
        $data = base64_encode($data);


        $cookie = new Cookie(self::COOKIE_USER, $data, 0, '/', null, false);
        $response->headers->setCookie($cookie);
        if ($newResponse) {
            $response->send();
        }
        return $this;
    }

    /**
     * @return WsUser|null
     */
    public function getUserFromCookie()
    {
        $cookie = $this->container->get('request_stack')->getMasterRequest()->cookies->get(self::COOKIE_USER);
        if (is_null($cookie)) {
            return null;
        }
        $cookie = base64_decode($cookie);
        $user = new WsUser();
        $user->unserialize($cookie);
        return $user;
    }

    /**
     * @param Response|null $response
     * @return $this
     */
    public function clearUserCookie(Response $response = null)
    {
        $newResponse = false;
        // se è nulla la response ne imposto una adesso
        if ($response === null) {
            $response = new Response();
            $newResponse = true;
        }
        // elimino il cookie
        $response->headers->clearCookie(self::COOKIE_USER, '/', null, false);
        // se necessario invio la nuova response
        if ($newResponse) {
            $response->send();
        }
        return $this;
    }
}
