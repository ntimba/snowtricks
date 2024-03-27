<?php

namespace App\Entity;

use App\Model\HasSlugInterface;
use App\Model\TimestampableInterface;
use App\Repository\TrickRepository;
use App\Traits\HasSlugTrait;
use App\Traits\TimestampableTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
class Trick implements HasSlugInterface, TimestampableInterface
{
    use HasSlugTrait;
    use TimestampableTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    private ?string $status = null;

    #[ORM\OneToMany(targetEntity: Group::class, mappedBy: 'trick')]
    private Collection $trickGroup;

    #[ORM\ManyToOne(inversedBy: 'tricks')]
    private ?User $userTrick = null;

    #[ORM\OneToMany(targetEntity: Photo::class, mappedBy: 'trick')]
    private Collection $photos;

    #[ORM\OneToMany(targetEntity: Video::class, mappedBy: 'trick')]
    private Collection $videos;

    #[ORM\OneToMany(targetEntity: Comment::class, mappedBy: 'trick')]
    private Collection $comments;

    public function __construct()
    {
        $this->trickGroup = new ArrayCollection();
        $this->photos = new ArrayCollection();
        $this->videos = new ArrayCollection();
        $this->comments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getTrickGroup(): Collection
    {
        return $this->trickGroup;
    }

    public function addTrickGroup(Group $trickGroup): static
    {
        if (!$this->trickGroup->contains($trickGroup)) {
            $this->trickGroup->add($trickGroup);
            $trickGroup->setTrick($this);
        }

        return $this;
    }

    public function removeTrickGroup(Group $trickGroup): static
    {
        if ($this->trickGroup->removeElement($trickGroup)) {
            // set the owning side to null (unless already changed)
            if ($trickGroup->getTrick() === $this) {
                $trickGroup->setTrick(null);
            }
        }

        return $this;
    }

    public function getUserTrick(): ?User
    {
        return $this->userTrick;
    }

    public function setUserTrick(?User $userTrick): static
    {
        $this->userTrick = $userTrick;

        return $this;
    }

    /**
     * @return Collection<int, Photo>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(Photo $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setTrick($this);
        }

        return $this;
    }

    public function removePhoto(Photo $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getTrick() === $this) {
                $photo->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): static
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }
}
