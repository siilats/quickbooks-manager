<?php

namespace Hotrush\QuickBooksManager\Http\Controllers;

use Hotrush\QuickBooksManager\QuickBooksWebhookManager;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WebhookController extends Controller
{
    /**
     * Handle incoming webhook requests.
     * Spawn an event per each entity.
     *
     * @param QuickBooksWebhookManager $manager
     * @param Request $request
     * @param null $connection
     */
    public function handle(QuickBooksWebhookManager $manager, Request $request, $connection = null)
    {
        foreach ($request->input('eventNotifications') as $realm) {
            foreach ($realm['dataChangeEvent']['entities'] as $entity) {
                event($manager->createEntityEvent(
                    $connection,
                    $realm['realmId'],
                    $entity['operation'],
                    $entity['name'],
                    $entity['id'],
                    $entity['lastUpdated'],
                    $entity['deletedId'] ?? null
                ));
            }
        }
    }
}