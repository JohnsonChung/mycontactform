<?php $this->layout('layout') ?>

<script src="<?= $this->asset('user/show.js') ?>"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>ユーザー編集</h1>
        </div>
    </div>

    <div class="row">
        <?php $this->insert('partials/flash') ?>
    </div>

    <div class="row">
        <div class="col-md-12">
            <form action="<?= $user->isMe() ? $this->uri('/user/me') : $this->uri("/user/$user->id") ?>" method="post">
                <div class="form-group">
                    <label>アカウント</label>
                    <input type="text" class="form-control" name="name" value="<?= isset($user->name) ? $this->e($user->name) : "" ?>" maxlength="255" required />
                </div>
                <div class="form-group">
                    <label>表示名</label>
                    <input type="text" class="form-control" name="screen_name" value="<?= isset($user->screen_name) ? $this->e($user->screen_name) : "" ?>" maxlength="255" required />
                </div>
                <div class="form-group">
                    <label>パスワード</label>
                    <input type="password" class="form-control" name="password" value="" />
                </div>
                <?php if($user->shouldShowAdminCheckbox()): ?>
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="admin" value="1" <?= $user->isAdmin() ? "checked" : "" ?> /> システム管理者
                    </label>
                </div>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary">送出</button>
            </form>
        </div>
    </div>
</div>