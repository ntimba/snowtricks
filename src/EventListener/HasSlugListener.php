<?php

namespace App\EventListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Symfony\Component\String\Slugger\SluggerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use App\Model\HasSlugInterface;

/**
 * Listener for automatically generating and setting slugs for entities implementing HasSlugInterface.
 */
#[AsDoctrineListener(event: Events::prePersist, priority: 500, connection: 'default')]
class HasSlugListener
{
    private SluggerInterface $slugger;

    /**
     * Constructs a new instance of HasSlugInterfaceListener.
     *
     * @param SluggerInterface $sluggåer The slugger interface used for generating slugs.
     */
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    /**
     * Callback method executed before an entity is persisted.
     *
     * @param PrePersistEventArgs $args The event arguments containing the entity being persisted.
     * @return void
     */
    public function prePersist(PrePersistEventArgs $args): void
    {
        $entity = $args->getObject();

        // Check if the entity implements HasSlugInterface.
        if (!$entity instanceof HasSlugInterface) {
            return;
        }

        //  Generate and set slug if it doens't exist.
        if (!$entity->getSlug()) {
            // Generate slug entity name and set it in lowercase.
            $entity->setSlug($this->slugger->slug($entity->getName())->lower());
        }
    }
}
