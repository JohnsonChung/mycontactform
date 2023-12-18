<?php

namespace JQuest\Models;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class EnquiryResponseCategory extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'enquiry_response_categories';
    protected $fillable = ['name'];

    public function enquiry_responses()
    {
        return $this->hasMany('JQuest\Models\EnquiryResponse', 'category_id');
    }
}
