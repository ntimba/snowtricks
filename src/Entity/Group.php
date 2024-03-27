<?php

namespace App\Entity;

use App\Model\HasSlugInterface;
use App\Model\TimestampableInterface;
use App\Repository\GroupRepository;
use App\Traits\HasSlugTrait;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group implements HasSlugInterface, TimestampableInterface
{
    use HasSlugTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[ORM\ManyToOne(inversedBy: 'trickGroup')]
    private ?Trick $trick = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }
}
