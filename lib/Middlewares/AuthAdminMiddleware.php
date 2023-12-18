<?php

namespace JQuest\Middlewares;

use JQuest\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 26, 2015
 */
class AuthAdminMiddleware
{

    public function __invoke(Request $request, Response $response, callable $next)
    {
        global $app;

        $user = Auth::user();

        if (!$user) {
            return $response->withStatus(403)->withHeader("Location", $app->rootUri . '/login');
        } elseif (!$user->isAdmin()) {
            return $app->show403($request, $response);
        } else {
            return $next($request, $response);
        }
    }
}
