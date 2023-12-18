<?php

namespace JQuest;

use League\Plates\Engine;
use League\Plates\Extension\ExtensionInterface;
use Slim\App;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 22, 2015
 */
class ViewExtension implements ExtensionInterface
{

    /**
     *
     * @var App
     */
    private $app;

    public function __construct(App $app)
    {
        $this->app = $app;
    }

    public function register(Engine $engine)
    {
        $engine->registerFunction('uri', [$this->app, 'uri']);
        $engine->registerFunction('asset', [$this, 'asset']);
        $engine->registerFunction('user', [$this->app, 'user']);
        $engine->registerFunction('menu', [$this->app, 'getMenu']);
    }

    public function asset($uri)
    {
        return $this->app->uri('/assets/' . $uri);
    }
}
