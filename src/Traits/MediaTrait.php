<?php

namespace App\Traits;

use App\Entity\Trick;
use Doctrine\ORM\Mapping as ORM;


trait MediaTrait
{
    /**
     * Get the trick associated with the media
     *
     * @return Trick|null The trick associated with the media
     */
    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    /**
     * Set the trick associated with the media
     *
     * @param Trick|null $trick The trick associated with the media
     * @return static
     */
    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }
}
