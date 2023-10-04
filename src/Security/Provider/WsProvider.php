<?php


namespace App\Security\Provider;


use App\Security\WsUser;
use App\Utils\Responses\UserResponse;
use App\Utils\Services\LoginService;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;

class WsProvider implements UserProviderInterface
{
    use ContainerAwareTrait;

    /**
     * WsProvider constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->setContainer($container);
    }

    /**
     * Ricarica le informazioni dell'utente loggato dal cookie
     * @param string $username
     * @return null|WsUser
     */
    public function loadUserByUsername($username)
    {
        $user = $this->container->get(LoginService::class)->getUserFromCookie();
        if (!is_null($user)) {
            return $user;
        }
        return null;
    }

    /**
     * @inheritDoc
     */
    public function refreshUser(UserInterface $user)
    {
        if (!$user instanceof WsUser) {
            throw new \Exception(
                sprintf('Instances of "%s" are not supported.', get_class($user))
            );
        }
        return $this->loadUserByUsername($user->getUsername());
    }

    /**
     * @inheritDoc
     */
    public function supportsClass($class)
    {
        return $class === 'App\Security\WsUser';
    }

    /**
     * @inheritDoc
     */
    public function loadUserByIdentifier(string $identifier): UserInterface
    {
        $user = new UserResponse($identifier, 'ROLES_USER');
        $wsUser = new WsUser($user);
        dump($wsUser);die;
        if(!$wsUser instanceof UserInterface)
            throw new \Exception("loadUserByIdentifier non compatibile con WsUser::class");
        return $wsUser;
    }
}
