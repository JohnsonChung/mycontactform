<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">

        <title>J-Quest | お問合せ管理システム</title>

        <!-- Bootstrap core CSS -->
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.5/css/bootstrap.min.css">

        <!-- Custom styles for this template -->
        <link href="<?= $this->e($this->asset('login.css')) ?>" rel="stylesheet">

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

        <?php $this->insert('partials/shim') ?>
    </head>

    <body>
        <div class="container">
            <div class="card card-container">
                <img id="profile-img" class="profile-img-card" src="https://www.j-quest.jp/images/logo.png" />
                <p id="profile-name" class="profile-name-card"></p>
                <form class="form-signin" action="<?= $this->e($this->uri('/login')) ?>" method="post">
                    <?php if($this->e($failed)): ?>
                    <div class="alert alert-danger" role="alert">
                        <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
                        <?= $this->e($failed) ?>
                    </div>
                    <?php endif; ?>
                    <label>ユーザアカウント</label> <input type="text" id="inputEmail" name="name" class="form-control"  required autofocus>
                    <label>パスワード </label> <input type="password" id="inputPassword" name="password" class="form-control"  required>
                    <!--
                    <div id="remember" class="checkbox">
                        <label>
                            <input type="checkbox" value="remember-me"> Remember me
                        </label>
                    </div>
                    -->
                    <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">ログイン</button>
                </form><!-- /form -->
                <!--
                <a href="#" class="forgot-password">
                    Forgot the password?
                </a>
                -->
            </div><!-- /card-container -->
        </div><!-- /container -->
    </body>
</html>
