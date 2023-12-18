<?php

namespace JQuest;

use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Slim\App;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class DB
{

    /**
     *
     * @return \Illuminate\Database\Capsule\Manager
     */
    public static function init(App $app)
    {
        $capsule = new Capsule();
        $config = $app->database;
        $capsule->addConnection($config);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule;
    }
}
