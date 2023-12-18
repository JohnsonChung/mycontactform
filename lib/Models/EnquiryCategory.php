<?php

namespace JQuest\Models;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 26, 2015
 */
class EnquiryCategory extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'enquiry_categories';

    public function enquiries()
    {
        return $this->hasMany('JQuest\Models\Enquiry', 'category_id');
    }

}
