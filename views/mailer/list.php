<?php $this->layout('layout') ?>

<script src="<?= $this->asset('mailer/list.js') ?>"></script>
<script id="template-actions" type="x-template">
    <a class="btn btn-info" href="<?=$this->uri('/mailer/')?><%= id %>">編集</a>

    <button type="submit" class="btn btn-danger" onclick="deleteMailer(<%=id%>,'<%=mail%>')">削除</button>
    <form class="delete-form hide" data-id="<%=id%>" method="post" action="<?= $this->uri('/mailer/') ?><%= id %>/delete" class="hide">
    </form>
</script>

<div class="container">
    <div class="row">
        <?php $this->insert('partials/flash') ?>
    </div>

    <div class="row">
        <a class="btn btn-primary" href="<?= $this->uri('/mailer/create') ?>">メールを作成する</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="mailer_list" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>メール</th>
                        <th>アクション</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>メール</th>
                        <th>アクション</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>