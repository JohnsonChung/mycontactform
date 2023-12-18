<?php

namespace JQuest\Middlewares;

use JQuest\Auth;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 26, 2015
 */
class AuthMiddleware
{

    public function __invoke(Request $request, Response $response, callable $next)
    {
        global $app;

        if (!Auth::check()) {
            return $response->withStatus(403)->withHeader("Location", $app->rootUri . '/login');
        } else {
            return $next($request, $response);
        }
    }
}
