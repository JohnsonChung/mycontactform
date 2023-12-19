<?php $this->layout('layout') ?>

<script src="<?= $this->asset('filter/list.js') ?>"></script>
<script id="template-actions" type="x-template">
    <!-- 删除按钮的模板 -->
    <button type="button" class="btn btn-danger delete-word" data-id="<%= id %>">削除</button>
</script>

<div class="container">
    <div class="row">
        <?php $this->insert('partials/flash') ?>
    </div>

    <div class="row">
        <!-- 新增过滤词的表单 -->
        <form id="add-filter-form" class="form-inline" method="post" action="<?= $this->uri('/add-filter') ?>">
            <div class="form-group mb-2">
                <input type="text" class="form-control" id="new-filter-word" name="filter_word" placeholder="新しい過濾字詞">
            </div>
            <button type="submit" class="btn btn-primary mb-2">追加</button>
        </form>
    </div>

    <div class="row">
        <div class="col-md-12">
            <table id="word_check_list" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>過濾字詞</th>
                        <th>操作</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>ID</th>
                        <th>過濾字詞</th>
                        <th>操作</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>