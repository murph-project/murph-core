<?php

namespace App\Core\Event\EntityManager;

use App\Core\Entity\EntityInterface;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * class EntityManagerEvent.
 *
 * @author Simon Vieille <simon@deblan.fr>
 */
class EntityManagerEvent extends Event
{
    public const CREATE_EVENT = 'entity_manager_event.create';
    public const UPDATE_EVENT = 'entity_manager_event.update';
    public const DELETE_EVENT = 'entity_manager_event.delete';
    public const PRE_CREATE_EVENT = 'entity_manager_event.pre_create';
    public const PRE_UPDATE_EVENT = 'entity_manager_event.pre_update';
    public const PRE_DELETE_EVENT = 'entity_manager_event.pre_delete';

    public function __construct(protected EntityInterface $entity)
    {
    }

    public function getEntity(): EntityInterface
    {
        return $this->entity;
    }
}
