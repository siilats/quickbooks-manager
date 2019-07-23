<?php

namespace Hotrush\QuickBooksManager\Http\Controllers;

use Hotrush\QuickBooksManager\Http\Requests\AuthCallbackRequest;
use Hotrush\QuickBooksManager\QuickBooksManager;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function callback($connection, AuthCallbackRequest $request, QuickBooksManager $manager)
    {
        $manager->connection($connection)->handleAuthorizationCallback($request);

        return redirect(
            Session::has(config('quickbooks_manager.session_redirect_key'))
                ? Session::get(config('quickbooks_manager.session_redirect_key'))
                : route(config('quickbooks_manager.default_redirect_route'))
        );
    }
}