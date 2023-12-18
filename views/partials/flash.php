<?php use JQuest\Flash; ?>
<?php foreach (Flash::get() as $message): ?>
    <?php if ($message->status === Flash::SUCCESS): ?>
        <div class="alert alert-success" role="alert">
            <?= $message->message ?>
        </div>
    <?php elseif ($message->status === Flash::ERROR): ?>
        <div class="alert alert-danger" role="alert">
            <?= $message->message ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>