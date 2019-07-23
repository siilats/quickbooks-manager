<?php

namespace Hotrush\QuickBooksManager;

class QuickBooksManager
{
    /**
     * The application instance.
     *
     * @var \Illuminate\Contracts\Foundation\Application
     */
    protected $app;

    /**
     * @var QuickBooksConnection[]
     */
    protected $connections = [];

    /**
     * Create a new Cache manager instance.
     *
     * @param  \Illuminate\Contracts\Foundation\Application  $app
     * @return void
     */
    public function __construct($app)
    {
        $this->app = $app;
    }

    /**
     * Get a connection by name.
     *
     * @param null $name
     * @return QuickBooksConnection
     * @throws Exception\AuthorizationRequired
     */
    public function connection($name = null)
    {
        $name = $name ?: $this->getDefaultConnection();

        return $this->connections[$name] = $this->get($name);
    }

    /**
     * @param $name
     * @return QuickBooksConnection
     * @throws Exception\AuthorizationRequired
     */
    protected function get($name)
    {
        return $this->connections[$name] ?? $this->resolve($name);
    }

    /**
     * Resolve the given connection.
     *
     * @param $name
     * @return QuickBooksConnection
     * @throws Exception\AuthorizationRequired
     */
    protected function resolve($name)
    {
        $config = $this->getConfig($name);

        if (is_null($config)) {
            throw new \InvalidArgumentException("QuickBooks connection [{$name}] is not defined.");
        }

        return new QuickBooksConnection($name, $config);
    }

    /**
     * Get default connection name.
     *
     * @return string
     */
    protected function getDefaultConnection()
    {
        return $this->app['config']['quickbooks_manager.default_connection'];
    }

    /**
     * Get connection configuration.
     *
     * @param  string  $name
     * @return array
     */
    protected function getConfig($name)
    {
        return $this->app['config']["quickbooks_manager.connections.{$name}"];
    }
}