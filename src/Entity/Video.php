<?php

namespace App\Entity;

use App\Model\MediaInterface;
use App\Model\TimestampableInterface;
use App\Repository\VideoRepository;
use App\Traits\MediaTrait;
use App\Traits\TimestampableTrait;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video implements MediaInterface, TimestampableInterface
{

    use MediaTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'videos')]
    private ?Trick $trick = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $embedCode = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmbedCode(): ?string
    {
        return $this->embedCode;
    }

    public function setEmbedCode(string $embedCode): static
    {
        $this->embedCode = $embedCode;

        return $this;
    }
}
