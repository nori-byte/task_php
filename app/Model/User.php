<?php

namespace Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Src\Auth\IdentityInterface;

class User extends Model implements IdentityInterface
{
    protected $with = ['role'];
    use HasFactory;

    public $timestamps = false;
    protected $fillable = [
        'name',
        'login',
        'password',
        'id_role',
    ];


    //Возврат первичного ключа
    public function getId(): int
    {
        return $this->id;
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'id_role');
    }

// Model/User.php
    public static function getAdminRoleId(): int
    {
        return Role::where('name', 'admin')->value('id') ?? 3; // fallback
    }

    public static function getHrStaffRoleId(): int
    {
        return Role::where('name', 'hr_staff')->value('id') ?? 6;
    }

    public function findIdentity(int $id)
    {
        return self::with('role')->where('id', $id)->first();
    }

// Возврат аутентифицированного пользователя
    public function attemptIdentity(array $credentials)
    {
        return self::with('role')->where([
            'login' => $credentials['login'],
            'password' => md5($credentials['password'])
        ])->first();
    }

}