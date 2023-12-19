<?php

namespace JQuest\Models;

class FilterWords extends \Illuminate\Database\Eloquent\Model

{
    protected $table = 'name_filters'; // 与您的数据库表名称相匹配

    protected $fillable = ['word']; // 允许批量赋值的字段

    protected $dates = ['created_at']; // 如果您想在 Eloquent 中使用日期字段

    /**
     * 获取所有过滤字词的数据，用于 DataTables 显示
     * 
     * @return array
     */
    public static function DT()
    {
        $data = [];

        $words = self::query()->orderBy('id', 'desc')->get();
        foreach ($words as $word) {
            $data[] = [
                $word->id,
                $word->word,
                $word->created_at // 显示创建时间，如果需要
            ];
        }

        return ['data' => $data];
    }
}
