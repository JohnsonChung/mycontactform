<?php $this->layout('layout') ?>

<script src="<?= $this->asset('enquiry/list.js') ?>"></script>

<div class="container">
    <div class="row">
        <div class="col-md-12">
            <table id="enquiry_list" class="table">
                <thead>
                    <tr>
                        <th>問合せ No</th>
                        <th>日時</th>
                        <th>ご利用店舗</th>
                        <th>お客様名</th>
                        <th>お問合せ内容</th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <th>問合せ No</th>
                        <th>日時</th>
                        <th>ご利用店舗</th>
                        <th>お客様名</th>
                        <th>お問合せ内容</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</div>