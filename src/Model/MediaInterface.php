<?php

namespace App\Model;

use App\Entity\Trick;

/**
 * Interface for entities representing miedia associated with a trick
 */
interface MediaInterface
{

    /**
     * Get the trick associated wwith the media
     *
     * @return Trick|null The trick associated with the media
     */
    public function getTrick(): ?Trick;


    /**
     * Set the trick associated with the media
     *
     * @param Trick|null $trick The trick to associate with the media
     * @return self
     */
    public function setTrick(?Trick $trick): self;
}
