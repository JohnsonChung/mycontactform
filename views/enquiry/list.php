<?php $this->layout('layout') ?>

<script src="<?= $this->asset('enquiry/list.js') ?>"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table id="enquiry_list" class="table">
                <colgroup>
                    <col width="4%">
                    <col width="9%">
                    <col width="12%">
                    <col width="10%">
                    <col width="20%">
                    <col width="13%">
                    <col width="10%">
                    <col width="12%">
                </colgroup>
                
                <thead>
                    <tr>
                        <th>No</th>
                        <th>日時</th>
                        <th>ご利用店舗</th>
                        <th>お客様名</th>
                        <th>お問合せ内容</th>
                        <th>カテゴリ</th>
                        <th>担当者</th>
                        <th>進捗</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>No</th>
                        <th>日時</th>
                        <th>ご利用店舗</th>
                        <th>お客様名</th>
                        <th>お問合せ内容</th>
                        <th>カテゴリ</th>
                        <th>担当者</th>
                        <th>進捗</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>