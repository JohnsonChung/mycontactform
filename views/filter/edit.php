<?php $this->layout('layout') ?>

<script src="<?= $this->asset('filter/list.js') ?>"></script>

<div class="container">
    <h2>名前フィルター</h2>
    <form id="edit-filter-form">
        <div class="form-group">
            <textarea class="form-control" id="filter-words" name="filter_words" rows="10"></textarea>
        </div>
        <div class="alert alert-info" role="alert">
            半角、全角特殊記号(!@#$%^&*,.?)は既に禁止されていますので、リストに特殊記号を追加する必要はありません。
        </div>
        <button type="submit" class="btn btn-primary">保存</button>
    </form>
</div>
