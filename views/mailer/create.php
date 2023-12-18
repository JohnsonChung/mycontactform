<?php $this->layout('layout') ?>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>メールを作成する</h1>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <form method="post" action="<?=$this->uri('/mailer')?>">
                <div class="form-group">
                    <label>メール</label>
                    <input class="form-control" name="email" required />
                </div>

                <input class="btn btn-primary" type="submit" />
            </form>
        </div>
    </div>
</div>