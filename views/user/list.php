<?php $this->layout('layout') ?>

<script src="<?= $this->asset('user/list.js') ?>"></script>
<script id="template-actions" type="x-template">
    <a class="btn btn-info" href="<?= $this->uri('/user/') ?><%= id %>">編集</a>
    <% if(isAdmin !== 'Admin') { %>
    <button type="submit" class="btn btn-danger" onclick="deleteUser(<%=id%>,'<%=name%>')">削除</button>
    <form class="delete-form hide" data-id="<%=id%>" method="post" action="<?= $this->uri('/user/') ?><%= id %>/delete" class="hide">
    </form>
    <% } %>
</script>

<div class="container">
    <div class="row">
        <?php $this->insert('partials/flash') ?>
    </div>

    <div class="row">
        <a class="btn btn-primary" href="<?=$this->uri('/user/create')?>">ユーザーアカウントを作成する</a>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="user_list" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>ユーザアカウント</th>
                        <th>表示名</th>
                        <th>アカウントタイプ</th>
                        <th>アクション</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>ユーザアカウント</th>
                        <th>表示名</th>
                        <th>アカウントタイプ</th>
                        <th>アクション</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>