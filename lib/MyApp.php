<?php

namespace JQuest;

use JQuest\Models\User;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 26, 2015
 */
class MyApp extends \Slim\App
{

    public function __construct($container = array())
    {
        parent::__construct($container);

        // Generate root uri
        $this->rootUri = str_replace("/index.php", "", $_SERVER['PHP_SELF']);

        // Register Plates Engine
        $this->views = new \League\Plates\Engine(DIR . '/views');
        $this->views->loadExtension(new ViewExtension($this));

        // 404, 403
        $that = $this;
        $this->getContainer()['notFoundHandler'] = function ($c) use ($that) {
            return [$that, 'show404'];
        };
        $this->getContainer()['errorHandler'] = function ($c) use ($that) {
            return new ErrorHandler();
        };
    }

    public function uri($uri = '')
    {
        return $this->rootUri . $uri;
    }

    /**
     *
     * @return User
     */
    public function user()
    {
        return Auth::user();
    }

    public function getMenu()
    {
        switch ($this->user()->role) {
            case 'admin':
                return [
                    [
                        'uri' => $this->uri('/enquiry'),
                        'id' => 'enquiry',
                        'name' => 'お問合せ一覧'
                    ],
                    [
                        'uri' => $this->uri('/user'),
                        'id' => 'user',
                        'name' => 'ユーザー管理'
                    ],
                    [
                        'uri' => $this->uri('/mailer'),
                        'id' => 'mailer',
                        'name' => '配信先リスト'
                    ]
                ];

            default:
                return [
                    [
                        'uri' => $this->uri('/enquiry'),
                        'id' => 'enquiry',
                        'name' => 'お問合せ一覧'
                    ]
                ];
        }
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function show403(Request $request, Response $response)
    {
        return $response->write($this->views->render('403'));
    }

    /**
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function show404(Request $request, Response $response)
    {
        return $response->write($this->views->render('404'));
    }
}
