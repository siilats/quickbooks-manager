<?php

namespace Hotrush\QuickBooksManager\Events;

use Carbon\Carbon;

abstract class AbstractEntityEvent
{
    /**
     * @var string
     */
    public $connection;

    /**
     * @var string
     */
    public $realmId;

    /**
     * The name of the entity type that changed (Customer, Invoice, etc.).
     *
     * @var string
     */
    public $entityName;

    /**
     * The changed entity’s ID.
     *
     * @var string
     */
    public $entityId;

    /**
     * The latest timestamp.
     *
     * @var Carbon
     */
    public $lastUpdated;

    /**
     * The ID of the entity that was deleted and merged.
     * (only for Merge events)
     *
     * @var string
     */
    public $deletedId;

    /**
     * AbstractEntityEvent constructor.
     *
     * @param $connection
     * @param $realmId
     * @param $entityName
     * @param $entityId
     * @param $lastUpdated
     * @param $deletedId
     */
    public function __construct($connection, $realmId, $entityName, $entityId, $lastUpdated, $deletedId)
    {
        $this->connection = $connection;
        $this->realmId = $realmId;
        $this->entityName = $entityName;
        $this->entityId = $entityId;
        $this->lastUpdated = $lastUpdated;
        $this->deletedId = $deletedId;
    }
}