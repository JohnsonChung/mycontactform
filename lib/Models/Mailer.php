<?php

namespace JQuest\Models;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class Mailer extends \Illuminate\Database\Eloquent\Model
{
    protected $table = 'mailer';

    protected $fillable = ['email'];

    /**
     *
     * @return string
     */
    public static function DT()
    {
        $data = [];

        $users = self::query()->orderBy('id', 'desc')->get();
        foreach ($users as $e) {
            $data[] = [
                $e->id,
                $e->email,
                ""
            ];
        }

        return ['data' => $data];
    }
}
