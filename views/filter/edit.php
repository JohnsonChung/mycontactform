<?php $this->layout('layout') ?>

<script src="<?= $this->asset('filter/list.js') ?>"></script>

<div class="container">
    <h2>編輯過濾詞</h2>
    <form id="edit-filter-form">
        <div class="form-group">
            <textarea class="form-control" id="filter-words" name="filter_words" rows="10"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">保存過濾詞</button>
    </form>
</div>
