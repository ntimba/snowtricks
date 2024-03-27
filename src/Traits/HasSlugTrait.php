<?php

namespace App\Traits;

use Doctrine\ORM\Mapping as ORM;

trait HasSlugTrait
{

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $slug = null;

    /**
     * Get the name associated with the slug.
     *
     * @return string|null The name associated with the slug.
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name associated with the slug
     *
     * @param string $name The name associated with the slug
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the slug of the entity
     *
     * @return string|null The slug of the entity 
     */
    public function getSlug(): ?string
    {
        return $this->slug;
    }

    /**
     * Set the slug of the entity
     *
     * @param string $slug The slug of the entity
     * @return static
     */
    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
}
