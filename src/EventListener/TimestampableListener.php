<?php

namespace App\EventListener;

use App\Model\TimestampableInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;


#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
#[AsDoctrineListener(event: Events::preUpdate, priority: 500, connection: 'default')]

/**
 * Listener for automatically updating timestamps (createdAt and updatedAt) of entities.
 */
class TimestampableListener
{
    /**
     * Callback method executed before an entity is persisted.
     *
     * @param PrePersistEventArgs $args
     * @return void
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        // Check if the entity implements TimestampableInterface
        if (!$entity instanceof TimestampableInterface) {
            return;
        }
        // Set createdAt timestamp to current data and time
        $entity->setCreatedAt(new \DateTimeImmutable());
    }



    /**
     * Callback method executred before an entity is updated
     *
     * @param PreUpdateEventArgs $args The event arguments containing the entity being updated.
     * @return void
     */
    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $entity = $args->getObject();

        // Check if the entity implements TimestampableInterface
        if (!$entity instanceof TimestampableInterface) {
            return;
        }

        // Set updatedAt timestamp to current date and time
        $entity->setUpdatedAt(new \DateTimeImmutable());
    }
}
