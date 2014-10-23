<?php

namespace Subbly\Model;

use Cartalyst\Sentry\Users\Eloquent\User as Model;

use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Model implements ModelInterface
{
    use Concerns\SubblyModel;
    use SoftDeletingTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes visible from the model's JSON form.
     *
     * @var array
     */
    protected $visible = array('uid', 'email', 'first_name', 'last_name', 'addresses', 'orders');

    protected $fillable = array('first_name', 'last_name');

    protected $dates = array('deleted_at');

    /**
     * Validations
     */
    protected $rules = array(
        'first_name' => 'required',
        'last_name'  => 'required',
        'email'      => 'required|email',
    );

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
