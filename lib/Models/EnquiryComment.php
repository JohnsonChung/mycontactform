<?php

namespace JQuest\Models;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class EnquiryComment extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'enquiry_comments';
    protected $fillable = ['user_id', 'comment'];

    public function user()
    {
        return $this->belongsTo('JQuest\Models\User', 'user_id');
    }

    public function enquiry()
    {
        return $this->belongsTo('JQuest\Models\Enquiry', 'enquiry_id');
    }
}
