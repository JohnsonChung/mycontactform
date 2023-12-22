<?php

namespace JQuest\Models;

use Illuminate\Database\Eloquent\Model;

class FilterWords extends Model
{
    protected $table = 'filter_words'; // 你的表名

    protected $fillable = ['word']; // 允许批量赋值的字段

    public $timestamps = false; // 不自动管理 created_at 和 updated_at 时间戳


    // 現有的 getAllWords 方法應該已經能正確工作
    public static function getAllWords()
    {
        return self::all()->pluck('word'); // pluck只獲取word列的值
    }
}
