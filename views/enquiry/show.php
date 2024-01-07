<?php $this->layout('layout') ?>
<?php use JQuest\Auth; ?>
<script>
    var ENQUIRY_ID = <?= $enquiry->id ?>;
</script>
<script src="<?= $this->asset('enquiry/show.js') ?>"></script>

<div class="container">
    <a href="<?= $this->uri('/enquiry') ?>" class="btn btn-primary"><span class="glyphicon glyphicon-arrow-left"></span>お問合せ一覧へ</a>
</div>
<!-- /Navbar -->
<!-- 這次新的範圍 -->
<div class="container">

    <?php if (isset($_GET['comment_success'])): ?>
        <div class="alert alert-success" role="alert">
            <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
            コメントが作成されます。
        </div>
    <?php endif; ?>

    <?php if (isset($_GET['comment_error'])): ?>
        <div class="alert alert-danger" role="alert">
            <span class="glyphicon glyphicon-exclamation-sign" aria-hidden="true"></span>
            エラー
        </div>
    <?php endif; ?>

    <h2>No.<?= $enquiry->id ?></h2>
    <table class="table ">
        <thead>
            <tr class="info">
                <th class="col-md-2">エリアマネジャー</th>
                <th class="col-md-2">店担当者</th>
                <th class="col-md-2">進捗</th>
                <th class="col-md-2">報告書アップロード</th>
                <th class="col-md-4">報告書ダウンロード</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td class="col-md-2">
                    <?php if ($enquiry->response): ?>
                        <?= $this->e($enquiry->response->user->screen_name) ?>
                    <?php else: ?>
                        ー
                    <?php endif; ?>
                </td>
                <td class="col-md-2">
                    <?php if ($enquiry->response): ?>
                        <a href="javascript:" data-toggle="modal" data-target="#response_modal"><?= $this->e($enquiry->response->responsible_party) ?></a>
                    <?php else: ?>
                        <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#response_modal">担当者指定する</button>
                    <?php endif; ?>
                </td>
                <td class="col-md-2 warning">
                    <?= $this->e($enquiry->getResponseStatus()) ?>
                    <!-- 担当者未指定　処理中　処理済 -->
                </td>
                <td class="col-md-2">
                    <button class="btn btn-info btn-sm" data-toggle="modal" data-target="#upload_modal">アップロード</button>
                </td>
                <td class="col-md-3">
                    <?php if (isset($upload_uri) && isset($upload_filename)): ?>
                        <a href="./<?=$enquiry->id?>/response/download" target="_blank">
                            <?= $this->e($upload_filename) ?> (<?= $this->e($upload_at) ?>)
                        </a>
                    <?php else: ?>
                        ー
                    <?php endif; ?>
                </td>
            </tr>
        </tbody>
    </table>

    <!-- お問合せDL Modal -->
    <div class="modal fade" id="response_modal" tabindex="-1" role="dialog" aria-labelledby="response_modal">
        <form id="response_form" action="<?= $this->uri("/enquiry/{$enquiry->id}/response") ?>" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">担当者指定</h4>
            </div>
            <div class="modal-body">

                <div class="form-group">
                    <label>カテゴリ</label>
                    <select name="response_category" class="form-control">
                        <?php foreach ($response_categories as $category): ?>
                            <option value="<?= $category->id ?>" <?= $enquiry->response && $enquiry->response->category_id === $category->id ? 'selected' : '' ?>><?= $category->name ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label>担当者 （本案件を対応する担当者を入力して下さい）</label>
                    <input type="text" name="responsible_party" class="form-control" placeholder="名前" required maxlength="128" value="<?= $enquiry->response ? $this->e($enquiry->response->responsible_party) : '' ?>" />
                </div>
                <div class="form-group">
                    <label>メッセージ</label>
                    <textarea class="form-control" name="message" rows="10" placeholder="内容" required><?= $enquiry->response ? $this->e($enquiry->response->message) : '' ?></textarea>
                </div>

            </div>
            <div class="modal-footer">
                <div class="row">
                    <div class="col-md-7 text-left">
                        上記の内容は、全JQコンタクト配信者に送信されます。
                    </div>
                    <div class="col-md-5">
                        <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                        <input type="submit" class="btn btn-primary" value="送信" />
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--/ end お問合せDL Modal -->

    <!-- Upload Modal -->
    <div class="modal fade" id="upload_modal" tabindex="-1" role="dialog" aria-labelledby="upload_modal">
        <form id="response_form" enctype="multipart/form-data" action="<?= $this->uri("/enquiry/{$enquiry->id}/response/upload") ?>" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">報告書アップロード</h4>
            </div>
            <div class="modal-body">
                <input type="file" name="upload" />
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
                <input type="submit" class="btn btn-primary" value="送信" />
            </div>
        </form>
    </div>
    
    <div class="modal fade" id="confirm_download_modal" role="dialog" aria-labelledby="confirm_download_modal">
        <div class="modal-body">
            「ブランク報告書DL」より報告書をダウンロードして、担当者に本件の対応と対応内容を記載した報告書の提出を依頼して下さい。
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">キャンセル</button>
            <a class="btn btn-primary pull-right download">
                <i class="glyphicon glyphicon-download-alt"></i>
                ブランク報告書DL
            </a>
        </div>
    </div>
    <!-- /end Upload Modal -->
</div>
<!--/ end 這次新的範圍 -->

<!-- 原有的範圍 -->
<div class="container">
    <table class="table table-bordered table-striped">
        <tbody>
            <tr>
                <th class="col-md-3">
                    問合せ日時
                </th>
                <td class="col-md-9"><?= $enquiry->created_at->format('Y-m-d H:i:s') ?></td>
            </tr>
            <tr>
                <th class="col-md-3">ご利用店舗</th>
                <td class="col-md-9"><?= $this->e($enquiry->store->name) ?></td>
            </tr>            
            <tr>
                <th class="col-md-3">レシート番号</th>
                <td class="col-md-9"><?= $this->e($enquiry->receipt_number) ?></td>
            </tr>
            <tr>
                <th class="col-md-3">お問合せ内容</th>
                <td class="col-md-9"><?= nl2br($this->e($enquiry->opinions_enquiries)) ?></td>
            </tr>
            <tr>
                <th class="col-md-3">お名前</th>
                <td class="col-md-9">
                    <?= $this->e($enquiry->name) ?>
                    <?php if ($has_previous_enquiries): ?>
                        <a href="<?=$this->uri('/enquiry')?>?filter=<?= urlencode($enquiry->name) ?>" class="previous-postings-link btn btn-warning">過去に投稿あり</a>           
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th class="col-md-3">カタガナ</th>
                <td class="col-md-9"><?= $this->e($enquiry->contact_katakana) ?></td>
            </tr>
            <tr>
                <th class="col-md-3">郵便番号</th>
                <td class="col-md-9"><?= $this->e($enquiry->postal_code) ?></td>
            </tr>
            <tr>
                <th class="col-md-3">都道府県</th>
                <td class="col-md-9"><?= $this->e($enquiry->state) ?></td>
            </tr>
            <tr>
                <th class="col-md-3">市区郡町番地</th>
                <td class="col-md-9"><?= $this->e($enquiry->city) ?></td>
            </tr>
            <tr>
                <th class="col-md-3">建物名</th>
                <td class="col-md-9"><?= $this->e($enquiry->building_name) ?></td>
            </tr>
            <tr>
            <th class="col-md-3">電話番号</th>
                <td class="col-md-9">
                    <?= $this->e($enquiry->telephone_number) ?>
                    <?php if (!empty($enquiry->telephone_number) && $has_previous_enquiries_by_phone): ?>                        
                        <a href="<?=$this->uri('/enquiry')?>?filter=<?= urlencode($enquiry->telephone_number) ?>" class="previous-postings-link btn btn-warning">過去に投稿あり</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th class="col-md-3">メールアドレス</th>
                <td class="col-md-9">
                    <?= $this->e($enquiry->email) ?>
                    <?php if (!empty($enquiry->email) && $has_previous_enquiries_by_email): ?>
                        <a href="<?=$this->uri('/enquiry')?>?filter=<?= urlencode($enquiry->email) ?>" class="previous-postings-link btn btn-warning">過去に投稿あり</a>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <th class="col-md-3">弊社からのご回答</th>
                <td class="col-md-9"><?= $this->e($enquiry->getContactRequirement()) ?></td>
            </tr>
            <tr>
                <th class="col-md-3">ご希望の回答方法</th>
                <td class="col-md-9"><?= $this->e($enquiry->getContactMethod()) ?></td>
            </tr>
            <tr>
                <td class="col-md-3"></td>
                <td class="col-md-9"><a class="btn btn-primary pull-right download"><i class="glyphicon glyphicon-download-alt"></i>ブランク報告書DL</a></td>
            </tr>
        </tbody>
    </table>
</div>
<!--/ end 原有的範圍 -->
<div class="container">

    <!-- comments -->
    <ul class="media-list">
        <?php foreach ($enquiry->comments()->with('user')->get() as $comment): ?>
            <li class="media <?=isset($comment->user) && (int)$comment->user->id === (int)Auth::user()->id ? "media-user" : ""?>">
                <div class="media-left">
                    <a>
                        <img class="media-object user-icon">
                    </a>
                </div>
                <div class="media-body">
                    <h4 class="media-heading"><?= isset($comment->user) ? $this->e($comment->user->screen_name) : "" ?></h4>
                    <?= nl2br($this->e($comment->comment)) ?>
                    <span class="media-time"><?= $comment->created_at->format('Y-m-d H:i:s') ?></span>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
    <form id="comment_create" action="<?= $this->uri("/enquiry/{$enquiry->id}/comment") ?>" method="post">
        <div class="form-group">
            <textarea class="form-control" name="comment" id="" rows="3" required maxlength="500"></textarea>
        </div>
        <div class="form-group">
            <input class="btn btn-success" type="submit" value="メッセージ送信" />
        </div>
    </form>
</div>