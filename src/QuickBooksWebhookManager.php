<?php

namespace Hotrush\QuickBooksManager;

use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

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
     * @param Request $request
     * @param $connection
     */
    public function logWebhook(Request $request, $connection)
    {
        if (!$this->loggingEnabled()) {
            return;
        }

        Log::channel($this->getLogChannel())->debug(
            'Webhook received',
            [
                'connection' => $connection,
                'payload' => $request->getContent()
            ]
        );
    }

    /**
     * @param Request $request
     * @param $connection
     * @return bool
     */
    public function validateWebhook(Request $request, $connection)
    {
        $connection = $connection ?: $this->getDefaultConnection();
        $verifierToken = $this->getVerifierToken($connection);

        if (!$verifierToken || !$request->hasHeader('intuit-signature')) {
            return true;
        }

        $hash = hash_hmac('sha256', $request->getContent(), $verifierToken);
        $signature = base64_decode($request->header('intuit-signature'));

        $valid = $signature === $hash;

        if (!$valid && $this->loggingEnabled()) {
            Log::channel($this->getLogChannel())->warning(
                'Webhook verification failed',
                [
                    'connection' => $connection,
                    'payload' => $request->getContent(),
                    'signature' => $signature,
                    'hash' => $hash,

                ]
            );
        }

        return $valid;
    }

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
        $connection = $connection ?: $this->getDefaultConnection();
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

    /**
     * @return string
     */
    private function getDefaultConnection()
    {
        return config('quickbooks_manager.default_connection');
    }

    /**
     * @param $connection
     * @return string
     */
    private function getVerifierToken($connection)
    {
        return config("quickbooks_manager.connections.{$connection}.verifier_token");
    }

    /**
     * @return bool
     */
    private function loggingEnabled()
    {
        return (bool) config('quickbooks_manager.webhook_logs_enabled');
    }

    /**
     * @return string
     */
    private function getLogChannel()
    {
        return config('quickbooks_manager.webhook_logs_channel');
    }
}