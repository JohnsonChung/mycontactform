<?php $this->layout('layout') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>メール編集</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="">
                <div class="form-group">
                    <label>メール</label>
                    <input class="form-control" type="email" name="email" value="<?=$mailer->email ? $this->e($mailer->email) : ""?>" required />
                </div>

                <input class="btn btn-primary" type="submit" />
            </form>
        </div>
    </div>
</div>