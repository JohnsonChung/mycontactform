<?php

namespace JQuest\Models;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class Prefecture extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'prefectures';

    public function stores()
    {
        return $this->hasMany('JQuest\Models\Store');
    }
}
