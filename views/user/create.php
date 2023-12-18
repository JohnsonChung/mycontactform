<?php $this->layout('layout') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>ユーザーアカウントを作成する</h1>
        </div>
    </div>

    <div class="row">
        <?php $this->insert('partials/flash') ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="" method="post">
                <div class="form-group">
                    <label>アカウント</label>
                    <input type="text" class="form-control" name="name" value="" maxlength="255" required />
                </div>
                <div class="form-group">
                    <label>表示名</label>
                    <input type="text" class="form-control" name="screen_name" value="" maxlength="255" required />
                </div>
                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" class="form-control" name="password" value="" required />
                </div>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="admin" value="1" /> システム管理者
                    </label>
                </div>
                <button type="submit" class="btn btn-primary">送出</button>
            </form>
        </div>
    </div>

</div>