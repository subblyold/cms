<?php

namespace Subbly\Model;

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Model implements UserInterface, RemindableInterface
{
    use UserTrait, RemindableTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $visible = array('uid', 'first_name', 'last_name', 'addresses', 'orders');

    /**
     * Boot
     */
    public static function boot()
    {
        parent::boot();

        static::creating(function($user)
        {
            $user->uid = md5(uniqid(mt_rand(), true));
        });
    }

    /**
     * Relashionship
     */
    public function orders()
    {
        return $this->hasMany('Subbly\\Model\\Order');
    }

    public function addresses()
    {
        return $this->hasMany('Subbly\\Model\\UserAddress');
    }
}
