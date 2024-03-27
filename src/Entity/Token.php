<?php

namespace App\Entity;

use App\Repository\TokenRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TokenRepository::class)]
class Token
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'tokens')]
    private ?User $userToken = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 255)]
    private ?string $token = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $expireDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserToken(): ?User
    {
        return $this->userToken;
    }

    public function setUserToken(?User $userToken): static
    {
        $this->userToken = $userToken;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;

        return $this;
    }

    public function getExpireDate(): ?\DateTimeImmutable
    {
        return $this->expireDate;
    }

    public function setExpireDate(\DateTimeImmutable $expireDate): static
    {
        $this->expireDate = $expireDate;

        return $this;
    }
}
