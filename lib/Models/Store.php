<?php

namespace JQuest\Models;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class Store extends \Illuminate\Database\Eloquent\Model
{

    protected $table = 'stores';
    protected $fillable = ['name'];
    public $timestamps = false;

    public function prefecture()
    {
        return $this->belongsTo('JQuest\Models\Prefecture');
    }

    public static function group()
    {
        $prefectures = Prefecture::with(array('stores' => function ($query) {
            $query->where('disabled', '=', 0)->orderBy('order', 'asc')->orderBy('name', 'asc');
        }))->get();
        $result = $prefectures->toArray();
        $valid = [];
        foreach ($result as $i => $row) {
            if (sizeof($row['stores']) > 0) {
                $valid[] = $row;
            }
        }
        return $valid;
    }
}
