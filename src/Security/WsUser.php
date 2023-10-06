<?php


namespace App\Security;


use App\Utils\Responses\UserResponse;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Uid\Uuid;

class WsUser  implements UserInterface,TokenInterface,PasswordAuthenticatedUserInterface
{
    /**
     * @var string
     */
    private $username;


    /**
     * @var string
     */
    private $password;



    /**
     * @var string
     */
    private $role;

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $firstname;

    /**
     * @var string
     */
    private $lastname;



    /**
     * @var string
     */
    private $plainPassword;


    public function __construct()
    {
        $this->role = "ROLE_USER";
    }



    /**
     * @return string
     */
    public function getPlainPassword(): string
    {
        return $this->plainPassword;
    }



    /**
     * @param string $plainPassword
     */
    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }




    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * @param string $username
     */
    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return !is_null($this->password) ? $this->password : '123456';
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getRole(): string
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole(string $role): void
    {
        $this->role = $role;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstname(): string
    {
        return $this->firstname;
    }

    /**
     * @param string $firstname
     */
    public function setFirstname(string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return string
     */
    public function getLastname(): string
    {
        return $this->lastname;
    }

    /**
     * @param string $lastname
     */
    public function setLastname(string $lastname): void
    {
        $this->lastname = $lastname;
    }


    public function __toString(): string
    {
        return (string) $this;
    }

    public function getRoleNames(): array
    {
        return $this->role;
    }

    public function getUser(): ?UserInterface
    {
        return  $this;
    }

    private function loadData(array $data)
    {

        foreach ($data as $k => $v) {

            $this[$k] = $v;
        }
        return $this;
    }


    public function __serialize(): array
    {
        return [
            "id" => $this->id,
            "username" => $this->username,
            "password" => $this->password,
            "roles" => $this->role,
            "firstname" => $this->firstname,
            "lastname" => $this->lastname

        ];

    }

    public function __unserialize(mixed $data): void
    {
        if(!is_string($data))
            $data = json_encode($data,true);

        $data = unserialize($data);
    }

    public function getRoles(): array
    {
        $roles[] = 'ROLE_USER';
        return array_unique($roles);
    }

    public function eraseCredentials()
    {
        return null;

    }

    public function getUserIdentifier(): string
    {
        return (string) $this->firstname.' '.$this->lastname;
    }

    public   function  setUser(UserInterface $user){}

     public function getAttributes() : array {return array();}
     public function setAttributes(array $attributes){}
     public function hasAttribute(string $name): bool{return true;}
     public function getAttribute(string $name): mixed{return null;}
     public function setAttribute(string $name, mixed $value){}


     public function getCookies() {
        return base64_encode(json_encode( $this->__serialize()));
     }



}
