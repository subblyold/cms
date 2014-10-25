<?php

namespace Subbly\Model;

use Cartalyst\Sentry\Users\Eloquent\User as Model;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class User extends Model implements ModelInterface//, UserInterface, RemindableInterface
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

    protected $fillable = array('email', 'password', 'first_name', 'last_name');

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

        self::$hasher = new \Cartalyst\Sentry\Hashing\BcryptHasher;
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

    /**
     *
     */
    protected function performInsert(\Illuminate\Database\Eloquent\Builder $query, array $options)
    {
        $this->attributes['uid'] = md5(uniqid(mt_rand(), true));

        parent::performInsert($query, $options);
    }
}
