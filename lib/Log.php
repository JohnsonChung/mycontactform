<?php

namespace JQuest;

use JQuest\MyApp;
use Monolog\Handler\SyslogHandler;
use Monolog\Logger;
use Webthink\MonologSlack\Handler\SlackWebhookHandler;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 27, 2015
 */
class Log
{

    /**
     *
     * @var Logger
     */
    private static $logger;

    /**
     *
     * @var MyApp
     */
    private static $app;

    /**
     *
     * @var array
     */
    private static $config;

    public static function init(MyApp $app)
    {
        self::$app = $app;
        self::$config = $app->slack;

        if (!isset(self::$logger)) {
            self::$logger = new Logger('jquest');
            self::$logger->pushHandler(new SyslogHandler('jquest'));

            $client = new \GuzzleHttp\Client();
            $requestFactory = new \GuzzleHttp\Psr7\HttpFactory();
            self::$logger->pushHandler(new SlackWebhookHandler($client, $requestFactory, 'https://hooks.slack.com/services/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'));
        }
    }

    /**
     *
     * @return Logger
     */
    public static function logger()
    {
        return self::$logger;
    }

    public static function info($message)
    {
        return self::logger()->addInfo($message);
    }

    public static function error($message)
    {
        if (is_object($message) && $message instanceof \Exception) {
            $e = $message;
            return self::logger()->addError($e);
        } else {
            return self::logger()->addError($message);
        }
    }

    private static function renderException(\Exception $exception)
    {
        $code = $exception->getCode();
        $message = $exception->getMessage();
        $file = $exception->getFile();
        $line = $exception->getLine();
        $trace = str_replace(['#', '\n'], ['<pre>#', '</pre>'], $exception->getTraceAsString());

        $html = sprintf('<pre><strong>Type:</strong> %s</pre>', get_class($exception));
        if ($code) {
            $html .= sprintf('<pre><strong>Code:</strong> %s</pre>', $code);
        }
        if ($message) {
            $html .= sprintf('<pre><strong>Message:</strong> %s</pre>', $message);
        }
        if ($file) {
            $html .= sprintf('<pre><strong>File:</strong> %s</pre>', $file);
        }
        if ($line) {
            $html .= sprintf('<pre><strong>Line:</strong> %s</pre>', $line);
        }
        if ($trace) {
            $html .= '<b>Trace</b>';
            $html .= sprintf('<pre>%s</pre>', $trace);
        }
        return $html;
    }
}
