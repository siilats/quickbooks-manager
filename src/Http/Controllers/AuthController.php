<?php

namespace Hotrush\QuickBooksManager\Http\Controllers;

use Hotrush\QuickBooksManager\Http\Requests\AuthCallbackRequest;
use Hotrush\QuickBooksManager\QuickBooksManager;
use Illuminate\Routing\Controller;

class AuthController extends Controller
{
    /**
     * @var QuickBooksManager
     */
    protected $manager;

    /**
     * AuthController constructor.
     *
     * @param QuickBooksManager $manager
     */
    public function __construct(QuickBooksManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Redirect to OAuth authorization page.
     *
     * @param null $connection
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirect($connection = null)
    {
        return redirect($this->manager->connection($connection)->getAuthorizationRedirectUrl());
    }

    /**
     * Authorization callback handle.
     *
     * @param AuthCallbackRequest $request
     * @param null $connection
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \QuickBooksOnline\API\Exception\SdkException
     * @throws \QuickBooksOnline\API\Exception\ServiceException
     */
    public function callback(AuthCallbackRequest $request, $connection = null)
    {
        $this->manager->connection($connection)->handleAuthorizationCallback($request);

        return redirect(route(config('quickbooks_manager.redirect_route')));
    }
}