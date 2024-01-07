<?php

namespace JQuest\Models;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class Enquiry extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'enquiry';
    protected $fillable = [
        "store_id",
        "opinions_enquiries",
        "name",
        "contact_katakana",
        "postal_code",
        "state",
        "city",
        "building_name",
        "contact_method",
        "contact_requirement",
        "telephone_number",
        "email",
        "receipt_number"
    ];

    const STATUS_NULL = 0;
    const STATUS_IN_PROGRESS = 1;
    const STATUS_DONE = 2;

    public function category()
    {
        return $this->belongsTo('JQuest\Models\EnquiryCategory', 'category_id');
    }

    public function comments()
    {
        return $this->hasMany('JQuest\Models\EnquiryComment', 'enquiry_id');
    }

    public function store()
    {
        return $this->belongsTo('JQuest\Models\Store');
    }

    public function response()
    {
        return $this->hasOne('JQuest\Models\EnquiryResponse', 'enquiry_id');
    }

    public function updateStatus($status)
    {
        $this->status = $status;
        return $this->save();
    }

    public function getResponseStatus()
    {
        switch ($this->status) {
            case self::STATUS_IN_PROGRESS:
                return '対応中';

            case self::STATUS_DONE:
                return '対応済';

            case self::STATUS_NULL:
            default:
                return '未対応';
        }
    }

    public function getStoreName()
    {
        return $this->store->prefecture->name . ' ' . $this->store->name;
    }

    public function getContactRequirement() {
        return intval($this->contact_requirement) === 1 ? "返信を希望する" : "返信は不要";
    }

    public function getContactMethod()
    {
        switch ($this->contact_method) {
            case "telephone":
                return "電話希望";

            case "mail":
            case "email":
                return "メール希望";

            case "NA":
            default:
                return "回答は不要";
        }
    }

    // 檢查多次來信人
    public static function hasPreviousEnquiries($name) {
        return self::where('name', $name)->count() > 1;
    }

    public static function hasPreviousEnquiriesByPhone($telephone) {
        return $telephone && self::where('telephone_number', $telephone)->count() > 1;
    }
    
    public static function hasPreviousEnquiriesByEmail($email) {
        return $email && self::where('email', $email)->count() > 1;
    }    

    public static function DT()
    {
        $data = [];

        $enquiries = self::query()->orderBy('id', 'desc')->get();
        foreach ($enquiries as $e) {
            $data[] = [
                $e->id,
                $e->created_at->format("Y-m-d\nH:i:s"),
                $e->getStoreName(),
                htmlspecialchars($e->name),
                nl2br(htmlspecialchars($e->opinions_enquiries)),
                $e->response ? $e->response->category->name : 'ー',
                $e->response ? $e->response->responsible_party : 'ー',
                $e->getResponseStatus(),
                htmlspecialchars($e->receipt_number),
                htmlspecialchars($e->email),          
                htmlspecialchars($e->telephone_number) 
            ];
        }

        return ['data' => $data];
    }
}
