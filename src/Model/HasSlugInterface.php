<?php

namespace App\Model;

/**
 * Interface for entities that have a slug
 */
interface HasSlugInterface
{

    /**
     * Get the name associated with the entity
     *
     * @return string|null The name of the entity
     */
    public function getName(): ?string;


    /**
     * Get the slug associated with the entity.
     *
     * @return string|null The slug of entity
     */
    public function getSlug(): ?string;

    /**
     * Set the slug for the entity
     *
     * @param string $slug The slug to set
     * @return self|null
     */
    public function setSlug(string $slug): ?self;
}
