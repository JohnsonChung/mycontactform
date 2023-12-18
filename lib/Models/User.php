<?php

namespace JQuest\Models;

use JQuest\Auth;
use Slim\Http\Request;

/**
 * @author Peter Chung <touhonoob@gmail.com>
 * @date Jul 12, 2015
 */
class User extends \Illuminate\Database\Eloquent\Model
{

    const ROLE_DEFAULT = '';
    const ROLE_ADMIN = 'admin';

    protected $table = 'users';
    protected $fillable = ['name', 'screen_name', 'role'];

    public function comments()
    {
        return $this->hasMany('JQuest\Models\Comment', 'user_id', 'id');
    }

    /**
     *
     * @return boolean
     */
    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }

    /**
     * Get role by input value
     * @param string $value
     * @return string
     */
    public static function getRole($value)
    {
        switch ($value) {
            case self::ROLE_ADMIN:
                return self::ROLE_ADMIN;
            case self::ROLE_DEFAULT:
            default:
                return self::ROLE_DEFAULT;
        }
    }

    /**
     *
     * @param string $name
     * @param string $password
     * @return null|User
     */
    public static function getByNameAndPassword($name, $password)
    {
        return User::where("name", "=", $name)
            ->where("password", "=", md5($password))
            ->get()->first();
    }

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
                $e->name,
                $e->screen_name,
                $e->isAdmin() ? "Admin" : "User",
                ""
            ];
        }

        return ['data' => $data];
    }

    public function updateByRequest(Request $request, $is_admin = false)
    {
        $this->name = $request->getParam('name', '');
        $this->screen_name = $request->getParam('screen_name', '');

        $password = $request->getParam('password', '');
        if (isset($password) && strlen($password) > 0) {
            $this->password = md5($password);
        }

        if ($is_admin && $this->shouldShowAdminCheckbox()) {
            $admin = $request->getParam('admin', false);
            $this->role = $admin ? static::ROLE_ADMIN : static::ROLE_DEFAULT;
        }

        return $this->save();
    }

    public function shouldShowAdminCheckbox()
    {
        global $app;

        if (Auth::user()->isAdmin()) {
            return (int)$this->id !== $app->getContainer()->get('superadmin');
        } else {
            return false;
        }
    }

    public function isMe()
    {
        return (int)Auth::user()->id === (int)$this->id;
    }
}
