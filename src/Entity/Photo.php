<?php

namespace App\Entity;

use App\Model\MediaInterface;
use App\Model\TimestampableInterface;
use App\Repository\PhotoRepository;
use App\Traits\MediaTrait;
use App\Traits\TimestampableTrait;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PhotoRepository::class)]
class Photo implements MediaInterface, TimestampableInterface
{
    use MediaTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'photos')]
    private ?Trick $trick = null;

    #[ORM\Column(length: 255)]
    private ?string $filePath = null;



    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFilePath(): ?string
    {
        return $this->filePath;
    }

    public function setFilePath(string $filePath): static
    {
        $this->filePath = $filePath;

        return $this;
    }
}
