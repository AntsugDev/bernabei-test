<?php


namespace App\Utils\Responses;


class UserResponse
{

    /**
     * @var string
     */
    private $username;

     /**
     * @var string
     */
    private $role;

     /**
     * @var string
     */
    private $sessionId;

    /**
     * 
     */
    public function __construct(string $username = "Tester")
    {
        $this->username = $username;
        $this->role = "ROLE_USER";
        $this->sessionId = session_id();
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function getRole()
    {
        return $this->role;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }
    public function _This()
    {
        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return get_object_vars($this);
    }
}
