<?php

namespace JQuest;

class FlashMessage
{

    public $status;
    public $message;

    public function __construct($status, $message)
    {
        $this->status = $status;
        $this->message = $message;
    }
}
