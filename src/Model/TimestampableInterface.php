<?php

namespace App\Model;

interface TimestampableInterface
{
    /**
     * Get the timestamp whe the entity was created.
     *
     * @return \DateTimeImmutable|null The creation timestamp.
     */
    public function getCreatedAt(): ?\DateTimeImmutable;

    /**
     * Set the timestamp when the entity was created.
     *
     * @param \DateTimeImmutable $createdAt The creation timestamp
     * @return self
     */
    public function setCreatedAt(\DateTimeImmutable $createdAt): self;


    /**
     * Get the timestampe of the last update
     *
     * @return \DateTimeImmutable|null The timestamp of the last update
     */
    public function getUpdatedAt(): ?\DateTimeImmutable;


    /**
     * Set the timestamp of the last update.
     *
     * @param \DateTimeImmutable|null $updatedAt The timestamp of the last update
     * @return self
     */
    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self;
}
