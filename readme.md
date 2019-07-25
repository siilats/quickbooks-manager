# QuickBooks Online manager for Laravel

- Manage different connections (credentials)
- Store tokens in the database
- Refresh token automatically
- Functionality for tokens acquiring
- Webhooks management
- Api SDK included
- Logging

## Installation

```bash
composer require hotrush/quickbooks-manager
```

You have to migrate the database

```bash
php artisan migrate
``` 

Also you can publish config file

```bash
php artisan vendor:publish --provider="Hotrush\QuickBooksManager\QuickBooksManagerServiceProvider" --tag="config"
```

## Authorization

To redirect to OAuth authorization page use `qbm.redirect` route e.g.:

```php
redirect(route('qbm.redirect', ['connection' => 'default']));
```

On successful auth token will be stored in the database and used automatically for API requests. You can configure redirect-back route for success authorization in config file changing `redirect_route` option.

## Api requests

When token received you can use connection manager to send api requests. To get manager instance you can use laravel's container [resolving stuff](https://laravel.com/docs/5.8/container#resolving) and resolve for `Hotrush\QuickBooksManager\QuickBooksManager` class.

Then just get needed connection and send request.

```php
$invoiceToCreate = Invoice::create([...]);
$manager = resolve(\Hotrush\QuickBooksManager\QuickBooksManager::class);
$manager->connection('default')->Add($invoiceToCreate);
```

## Webhooks

Each connection has it's own webhook endpoint e.g.

```php
/qbm/webhook/{connection?}
```

You can also define `verifier_token` for each connection to verify data received by webhook ([read more](https://developer.intuit.com/app/developer/qbo/docs/develop/webhooks/managing-webhooks-notifications#validating-the-notification)).

Each webhook's notification will spawn a new laravel's event. You can easily create your own [event listener](https://laravel.com/docs/5.8/events#defining-listeners) to handle it.

Events list:

- `Hotrush\QuickBooksManager\Events\EntityCreate`
- `Hotrush\QuickBooksManager\Events\EntityUpdate`
- `Hotrush\QuickBooksManager\Events\EntityDelete`
- `Hotrush\QuickBooksManager\Events\EntityMerge`
- `Hotrush\QuickBooksManager\Events\EntityVoid`

Each event has next arguments:

- `connection` name
- `realmId`
- `entityName` - the name of the entity type that changed (Customer, Invoice, etc.)
- `entityId` - changed entity id
- `lastUpdated` - carbon-parsed date object
- `deletedId` - the ID of the entity that was deleted and merged (only for Merge events)