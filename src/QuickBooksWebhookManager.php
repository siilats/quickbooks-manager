<?php

namespace Hotrush\QuickBooksManager;

use Illuminate\Support\Carbon;

class QuickBooksWebhookManager
{
    /**
     * Available operations list and events mapping.
     *
     * @var array
     */
    private $operations = [
        'create' => \Hotrush\QuickBooksManager\Events\EntityCreate::class,
        'update' => \Hotrush\QuickBooksManager\Events\EntityUpdate::class,
        'delete' => \Hotrush\QuickBooksManager\Events\EntityDelete::class,
        'merge' => \Hotrush\QuickBooksManager\Events\EntityMerge::class,
        'void' => \Hotrush\QuickBooksManager\Events\EntityVoid::class,
    ];

    /**
     * @param $connection
     * @param $realmId
     * @param $operation
     * @param $entityName
     * @param $entityId
     * @param $lastUpdated
     * @param $deletedId
     * @return \Hotrush\QuickBooksManager\Events\AbstractEntityEvent
     */
    public function createEntityEvent($connection, $realmId, $operation, $entityName, $entityId, $lastUpdated, $deletedId)
    {
        $connection = $connection ?: config('quickbooks_manager.default_connection');
        $lastUpdated = Carbon::createFromFormat(\DateTime::ISO8601, $lastUpdated);
        $eventClass = $this->getEventClass($operation);

        return new $eventClass($connection, $realmId, $entityName, $entityId, $lastUpdated, $deletedId);
    }

    /**
     * @param $operation
     * @return mixed
     */
    private function getEventClass($operation)
    {
        $operation = trim(strtolower($operation));

        if (!isset($this->operations[$operation])) {
            throw new \InvalidArgumentException("Invalid operation name [{$operation}] for webhook");
        }

        return $this->operations[$operation];
    }
}