<?php

namespace JQuest;

use JQuest\Log;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 27, 2015
 */
class ErrorHandler extends \Slim\Handlers\Error
{
    public function __invoke(
        \Psr\Http\Message\ServerRequestInterface $request,
        \Psr\Http\Message\ResponseInterface $response,
        \Exception $exception
    ) {
        $response = parent::__invoke($request, $response, $exception);

        Log::error($exception);

        return $response;
    }
}
