<?php

require 'vendor/autoload.php';
$config = include 'config/config.php';

use JQuest\Auth;
use JQuest\DB;
use JQuest\Flash;
use JQuest\Log;
use JQuest\Models\Enquiry;
use JQuest\Models\EnquiryResponseCategory;
use JQuest\Models\Mailer;
use JQuest\Models\Prefecture;
use JQuest\Models\Store;
use JQuest\Models\User;
use JQuest\MyApp;
use JQuest\Upload;
use Slim\Http\Request;
use Slim\Http\Response;

// Setup session
ini_set('session.auto_start', 0);
ini_set('session.use_only_cookies', 1);
ini_set('session.use_cookies', 1);

// Create Slim\App
$app = new MyApp($config);
DB::init($app);
Log::init($app);

$auth_middleware = new \JQuest\Middlewares\AuthMiddleware();
$admin_middleware = new \JQuest\Middlewares\AuthAdminMiddleware();

$app->get('/', function (Request $request, Response $response, array $args) use ($app) {
    return $response->withRedirect($app->uri('/enquiry'));
})->add($auth_middleware);

$app->get('/login', function (Request $request, Response $response, array $args) use ($app) {
    return $response->write($app->views->render('login', [
        'failed' => isset($_GET['failed']) ? 'ログインに失敗しました' : null
    ]));
});
$app->post('/login', function (Request $request, Response $response, array $args) use ($app) {
    $name = $request->getParam('name');
    $password = $request->getParam('password');
    if (Auth::login($name, $password)) {
        return $response->withRedirect($app->uri('/'));
    } else {
        return $response->withRedirect($app->uri('/login?failed'));
    }
});
$app->get('/logout', function (Request $request, Response $response, array $args) use ($app) {
    Auth::logout();
    return $response->withRedirect($app->uri('/login'));
});

/** Users * */
$app->get('/user', function (Request $request, Response $response, array $args) use ($app) {
    if ($request->isXhr()) {
        return $response->withJson(User::DT());
    } else {
        return $response->write($app->views->render('user/list'));
    }
})->add($admin_middleware);
$app->get('/user/create', function (Request $request, Response $response, array $args) use ($app) {
    return $response->write($app->views->render('user/create'));
})->add($admin_middleware);
$app->post('/user/create', function (Request $request, Response $response, array $args) use ($app) {
    try {
        $user = new User;

        foreach (['name', 'screen_name', 'password'] as $field) {
            $user->$field = $request->getParam($field, '');

            if (!$user->$field || strlen($user->$field) <= 0) {
                throw new \RuntimeException("$field is empty");
            }
        }

        $user->password = md5($user->password);
        $user->role = $request->getParam('admin', '') == '1' ? User::ROLE_ADMIN : User::ROLE_DEFAULT;

        if (!$user->save()) {
            throw new \RuntimeException('User creation failed');
        }

        Flash::setSuccess("User $user->name is created");
        return $response->withRedirect($this->uri('/user'));
    } catch (\Exception $ex) {
        Flash::setError($ex->getMessage());
        return $response->withRedirect($this->uri('/user/create'));
    }
})->add($admin_middleware);

$app->get('/user/{id:\d+}', function (Request $request, Response $response, array $args) use ($app) {
    $user = User::find($args['id']);

    if (!$user) {
        return $app->show404($request, $response);
    }

    return $response->write($app->views->render('user/show', [
        'user' => $user
    ]));
})->add($admin_middleware);
$app->get('/user/me', function (Request $request, Response $response, array $args) use ($app) {
    return $response->write($app->views->render('user/show', [
        'user' => Auth::user()
    ]));
})->add($auth_middleware);
$app->post('/user/me', function (Request $request, Response $response, array $args) use ($app) {
    if (Auth::user()->updateByRequest($request, false)) {
        Flash::setSuccess("ユーザー情報が変更されました。");
    } else {
        Flash::setError("システムエラー");
    }

    return $response->write($app->views->render('user/show', [
        'user' => Auth::user(),
        'me' => true
    ]));
})->add($auth_middleware);
$app->post('/user', function (Request $request, Response $response, array $args) {

})->add($admin_middleware);
$app->post('/user/{id:\d+}', function (Request $request, Response $response, array $args) use ($app) {
    $user = User::find($args['id']);
    if (!$user) {
        return $app->show404($request, $response);
    }

    if ($user->updateByRequest($request, true)) {
        Flash::setSuccess("ユーザー情報が変更されました。");
    } else {
        Flash::setError("システムエラー");
    }

    return $response->withRedirect($app->uri('/user/' . $args['id']));
})->add($admin_middleware);
$app->post('/user/{id:\d+}/delete', function (Request $request, Response $response, array $args) use ($app) {
    $user = User::find($args['id']);
    if (!$user) {
        return $app->show404($request, $response);
    }

    if ($user->id === 1) {
        return $app->show403($request, $response);
    }

    if ($user->delete()) {
        Flash::setSuccess("ユーザーが削除されました。");
    } else {
        Flash::setError("システムエラー");
    }

    return $response->withRedirect($app->uri('/user'));
})->add($admin_middleware);

/** Mailers * */
$app->get('/mailer', function (Request $request, Response $response, array $args) use ($app) {
    if ($request->isXhr()) {
        return $response->withJson(Mailer::DT());
    } else {
        return $response->write($app->views->render('mailer/list'));
    }
})->add($admin_middleware);
$app->get('/mailer/create', function (Request $request, Response $response, array $args) use ($app) {
    return $response->write($app->views->render('mailer/create'));
})->add($admin_middleware);
$app->post('/mailer', function (Request $request, Response $response, array $args) use ($app) {
    $email = $request->getParam('email', '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        Flash::setError("Invalid email");
        return $response->withRedirect($app->uri('/mailer/create'));
    }

    $mailer = Mailer::create(['email' => $email]);
    Flash::setSuccess("Mailer is created");
    return $response->withRedirect($app->uri('/mailer'));
})->add($admin_middleware);
$app->get('/mailer/{id:\d+}', function (Request $request, Response $response, array $args) use ($app) {
    $mailer = Mailer::find($args['id']);
    if (!$mailer) {
        return $app->show404($request, $response);
    }

    return $response->write($app->views->render('mailer/show', [
        'mailer' => $mailer
    ]));
})->add($admin_middleware);
$app->post('/mailer/{id:\d+}', function (Request $request, Response $response, array $args) use ($app) {
    $mailer = Mailer::find($args['id']);
    if (!$mailer) {
        return $app->show404($request, $response);
    }

    $email = $request->getParam('email', '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
        Flash::setError("Invalid email");
        return $response->withRedirect($app->uri('/mailer/' . $mailer->id));
    }

    $mailer->update(['email' => $email]);
    Flash::setSuccess("Mailer is updated");
    return $response->withRedirect($app->uri('/mailer'));
})->add($admin_middleware);
$app->post('/mailer/{id:\d+}/delete', function (Request $request, Response $response, array $args) use ($app) {
    $mailer = Mailer::find($args['id']);
    if (!$mailer) {
        return $app->show404($request, $response);
    }

    $mailer->delete();

    Flash::setSuccess("Mail is deleted");
    return $response->withRedirect($app->uri('/mailer'));
})->add($admin_middleware);

/** Enquiries * */
// Get Enquiry List
$app->get('/enquiry', function (Request $request, Response $response, array $args) use ($app) {
    if ($request->isXhr()) {
        return $response->withJson(Enquiry::DT());
    } else {
        return $response->write($app->views->render('enquiry/list'));
    }
})->add($auth_middleware);

// Get Enquiry
$app->get('/enquiry/{id:\d+}', function (Request $request, Response $response, array $args) use ($app) {
    $enquiry = Enquiry::find($args['id']);
    if ($enquiry) {
        $result = [
            'enquiry' => $enquiry,
            'response_categories' => EnquiryResponseCategory::all()
        ];

        $upload_uri = ($filename = Upload::check($enquiry)) ? $app->uri('/assets/upload/' . $filename) : null;
        if ($upload_uri) {
            $result['upload_uri'] = $upload_uri;
            $result['upload_filename'] = $filename;
            $result['upload_at'] = $enquiry->response->upload_at;
        }

        return $response->write($app->views->render('enquiry/show', $result));
    } else {
        return $app->show404($request, $response);
    }
})->add($auth_middleware);

// Create Enquiry
$app->post('/enquiry', function (Request $request, Response $response, array $args) {
    \JQuest\Mail::submitEnquiry($request->getParams(), 'HPからお問合せがありました（JQcontact)');
    return $response->withRedirect('/subpage.php?p=contact2&submit_success=1&agree=y');
});

$app->post('/enquiry-corp', function (Request $request, Response $response, array $args) {
  \JQuest\Mail::submitCorpEnquiry($request->getParams());
  return $response->withRedirect('/subpage.php?p=contact2-corp&submit_success=1&agree=y');
});


$app->post('/consumer', function (Request $request, Response $response, array $args) {
    \JQuest\Mail::submitEnquiry($request->getParams(), 'HPからお問合せがありました（JQcontact)');
    return $response->withRedirect('/contact.php?p=consumer&submit_success=1&agree=y');
});

$app->post('/company', function (Request $request, Response $response, array $args) {
  \JQuest\Mail::submitCorpEnquiry($request->getParams());
  return $response->withRedirect('/contact.php?p=company&submit_success=1&agree=y');
});

// Create Event Enquiry
$app->post('/event-enquiry', function (Request $request, Response $response, array $args) {
    $params = $request->getParams();

    $opinions_enquiries = $request->getParam('opinions_enquiries', '');

    $info = "HPからコーディングの申し込みがありました。\n";

    $date = $request->getParam('date', '');
    $time = $request->getParam('time', '');
    if ($date || $time) {
        $info .= sprintf("ご希望日時: %s %s\n", $date, $time);
    }

    $coating = $request->getParam('coating');
    if ($coating) {
        $info .= sprintf("ご希望コース: %s\n", $coating);
    }

    // set default values for required columns
    $params = array_merge($params, [
        'opinions_enquiries' => $info . $opinions_enquiries,
        'contact_method' => 'telephone'
    ]);

    \JQuest\Mail::submitEnquiry($params, 'HPからコーディングの申し込みがありました。（JQcontact)');
    return $response->withRedirect('/subpage.php?p=contactKeeperCampaign&submit_success=1');
});

// Create EnquiryComment
$app->post('/enquiry/{id:\d+}/comment', function (Request $request, Response $response, array $args) use ($app) {
    $redirect_uri = $app->uri("/enquiry/{$args['id']}");

    try {
        // Check Enquiry
        $enquiry_id = (int)$args['id'];
        $enquiry = Enquiry::find($enquiry_id);
        if (!$enquiry) {
            throw new \RuntimeException("Enquiry $enquiry_id Not Found.");
        }

        // Check Comment
        $comment = trim((string)$request->getParam('comment'));
        if (strlen($comment) === 0) {
            throw new \RuntimeException("Comment not filled.");
        }

        $enquiry_comment = $enquiry->comments()->create([
            'user_id' => Auth::user()->id,
            'comment' => $comment
        ]);

        if (!$enquiry_comment) {
            throw new \RuntimeException("Comment creation failed.");
        }

        return $request->isXhr() ? $response->withJson([
            'status' => 'success',
            'comment' => $enquiry_comment->toArray()
        ], 201) : $response->withRedirect($redirect_uri . '?comment_success');
    } catch (\RuntimeException $ex) {
        return $request->isXhr() ? $response->withJson([
            'status' => 'error',
            'message' => $ex->getMessage()
        ], 400) : $response->withRedirect($redirect_uri . '?comment_error');
    }
})->add($auth_middleware);

// Create EnquiryResponse
$app->post('/enquiry/{id:\d+}/response', function (Request $request, Response $response, array $args) use ($app) {
    $redirect_uri = $app->uri("/enquiry/{$args['id']}");

    try {
        // Check Enquiry
        $enquiry_id = (int)$args['id'];
        $enquiry = Enquiry::find($enquiry_id);
        if (!$enquiry) {
            throw new \RuntimeException("Enquiry $enquiry_id Not Found.");
        }

        // Check category
        $category_id = (int)$request->getParam('response_category');
        $category = EnquiryResponseCategory::find($category_id);
        if (!$category) {
            throw new \RuntimeException("カテゴリ $category_id Not Found.");
        }

        // Check message
        $message = trim((string)$request->getParam('message'));
        if (strlen($message) === 0) {
            throw new \RuntimeException("メッセージ is not filled.");
        }

        // Check responsible party
        $responsible_party = trim((string)$request->getParam('responsible_party'));
        if (strlen($responsible_party) === 0) {
            throw new \RuntimeException("担当者 is not filled.");
        }

        $data = [
            'user_id' => (int)Auth::user()->id,
            'category_id' => $category_id,
            'responsible_party' => $responsible_party,
            'message' => $message
        ];

        if ($enquiry->response) {
            $enquiry_response = $enquiry->response;
            $enquiry_response->update($data);
            $status = 200;
        } else {
            $enquiry_response = $enquiry->response()->create($data);
            $enquiry->updateStatus(Enquiry::STATUS_IN_PROGRESS);
            $status = 201;
        }

        if (!$enquiry_response) {
            throw new \RuntimeException("Response creation failed.");
        }

        \JQuest\Mail::submitEnquiryResponse($enquiry, $enquiry_response);

        $enquiry->comments()->create([
            'user_id' => Auth::user()->id,
            'comment' => sprintf(
                \JQuest\Mail::ENQUIRY_RESPONSE_SUBJECT,
                $enquiry->id,
                $enquiry_response->responsible_party
            )
        ]);

        return $request->isXhr() ? $response->withJson([
            'status' => 'success'
        ], $status) : $response->withRedirect($redirect_uri . '?response_success');
    } catch (\RuntimeException $ex) {
        return $request->isXhr() ? $response->withJson([
            'status' => 'error',
            'message' => $ex->getMessage()
        ], 400) : $response->withRedirect($redirect_uri . '?response_error');
    }
})->add($auth_middleware);

$app->get('/enquiry/{id:\d+}/download', function (Request $request, Response $response, array $args) use ($app) {
    $enquiry_id = (int)$args['id'];
    $enquiry = Enquiry::find($enquiry_id);
    if (!$enquiry) {
        return $app->show404($request, $response);
    } else {

        $template_path = __DIR__ . '/doc/template.xlsx';
        $uri = '/assets/doc/JQコンタクト対応報告書_' . $enquiry->id . '.xlsx';
        $save_path = __DIR__ . $uri;
        JQuest\EnquiryExport::generateSheet($template_path, $save_path, $enquiry);


        /*
        // Generate Doc
            $template_path = __DIR__ . '/doc/template.docx';
            $uri = '/assets/doc/JQコンタクト対応報告書_' . $enquiry->id . '.doc';
            $save_path = __DIR__ . $uri;
            JQuest\EnquiryExport::generateDoc($template_path, $save_path, $enquiry);

            return $uri;
        */


        return $response->withRedirect($app->uri($uri));
    }
})->add($auth_middleware);

$app->get('/enquiry/{id:\d+}/response/download',
    function (Request $request, Response $response, array $args) use ($app) {
        $enquiry = Enquiry::find($args['id']);
        if (!$enquiry) {
            return $app->show404($request, $response);
        }

        if ($filepath = Upload::check($enquiry, true)) {
            $lazyOpenStream = new \GuzzleHttp\Psr7\LazyOpenStream($filepath, 'r');

            return $app->response->withHeader('Content-Type', 'application/octet-stream')
            ->withHeader('Content-Transfer-Encoding', 'binary')
            ->withHeader('Expires', '0')
            ->withHeader('Cache-Control', 'must-revalidate')
            ->withHeader('Pragma', 'public')
            ->withHeader('Content-Length', filesize($filepath))
            ->withHeader('Content-Disposition', "attachment; filename={$enquiry->response()->first()->upload_filename}")
            ->withBody($lazyOpenStream);
        } else {
            return $app->show404($request, $response);
        }
    })->add($auth_middleware);

$app->post('/enquiry/{id:\d+}/response/upload',
    function (Request $request, Response $response, array $args) use ($app) {
        try {
            $enquiry = Enquiry::find($args['id']);
            if (!$enquiry) {
                return $app->show404($request, $response);
            }

            if (!is_array($_FILES) || !isset($_FILES['upload'])) {
                throw new \RuntimeException('No Upload');
            }

            $upload = $_FILES['upload'];

            if ($upload['error'] !== UPLOAD_ERR_OK) {
                throw new \RuntimeException('Upload Error ' . $upload['error']);
            }

            $filepath = $upload['tmp_name'];
            if (!file_exists($filepath) || !is_uploaded_file($filepath)) {
                throw new \RuntimeException('Upload failed');
            }

            $pathinfo = pathinfo($upload['name']);
            $extension = 'doc';
            if (isset($pathinfo['extension']) && in_array($pathinfo['extension'],
                    ['doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'pdf'])
            ) {
                $extension = $pathinfo['extension'];
            } else {
                throw new \RuntimeException('Please upload Microsoft Office or PDF files only');
            }

            $upload_filename = $args['id'] . '.' . $extension;

            move_uploaded_file($filepath, __DIR__ . '/assets/upload/' . $upload_filename);

            chmod($filepath, 664);

            $enquiry->response->update([
                'upload_filename' => $upload_filename,
                'upload_at' => date('Y-m-d H:i:s')
            ]);

            $enquiry->updateStatus(Enquiry::STATUS_DONE);

            $enquiry->comments()->create([
                'user_id' => Auth::user()->id,
                'comment' => sprintf(
                    \JQuest\Mail::ENQUIRY_RESPONSE_UPLOADED_SUBJECT,
                    $enquiry->id,
                    $enquiry->response->responsible_party
                )
            ]);

            \JQuest\Mail::submitEnquiryResponseUploaded($enquiry, $enquiry->response);

            return $response->withRedirect($app->uri("/enquiry/{$args['id']}"));
        } catch (\RuntimeException $ex) {
            Log::error($ex);
            return $response->write($ex->getMessage())->withStatus(400);
        }
    })->add($auth_middleware);

// List stores
$app->get('/stores', function (Request $request, Response $response, array $args) {
    return $response->withHeader('Access-Control-Allow-Origin', '*')->withJson(Store::group());
});

// List prefectures
$app->get('/prefectures', function (Request $request, Response $response, array $args) {
    return $response->withJson(Prefecture::all()->toArray());
});

$app->run();
