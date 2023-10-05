<?php

namespace App\Entity;

use App\Repository\LoginRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: LoginRepository::class)]
class Login
{
    #[ORM\Id]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 125)]
    private ?string $email = "";

    #[ORM\Column(length: 50)]
    private ?string $password = "";

    #[ORM\Column(length: 250)]
    private ?string $firstname = "";

    #[ORM\Column(length: 250)]
    private ?string $lastname = "";

    /**
     * @return string|null
     */
    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    /**
     * @param string|null $firstname
     */
    public function setFirstname(?string $firstname): void
    {
        $this->firstname = $firstname;
    }

    /**
     * @return streing|string|null
     */
    public function getLastname(): streing|string|null
    {
        return $this->lastname;
    }

    /**
     * @param streing|string|null $lastname
     */
    public function setLastname(streing|string|null $lastname): void
    {
        $this->lastname = $lastname;
    }



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @param int|null $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }


}
