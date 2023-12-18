<?php

namespace JQuest\Models;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class EnquiryResponse extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'enquiry_responses';
    protected $fillable = ['category_id', 'user_id', 'responsible_party', 'message', 'upload_filename', 'upload_at'];

    public function user()
    {
        return $this->belongsTo('JQuest\Models\User', 'user_id');
    }

    public function enquiry()
    {
        return $this->belongsTo('JQuest\Models\Enquiry', 'enquiry_id');
    }

    public function category()
    {
        return $this->belongsTo('JQuest\Models\EnquiryResponseCategory', 'category_id');
    }
}
