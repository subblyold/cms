<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model implements ModelInterface
{
    use Concerns\SubblyModel;
    use Concerns\AddressTrait;

    protected $table = 'user_addresses';

    /**
     * Fields
     */
    protected $visible = array('uid', 'name', 'firstname', 'lastname', 'address1', 'address2', 'zipcode', 'city', 'country', 'phone_work', 'phone_home', 'phone_mobile', 'created_at', 'updated_at');

    protected $fillable = array('name', 'firstname', 'lastname', 'address1', 'address2', 'zipcode', 'city', 'country', 'phone_work', 'phone_home', 'phone_mobile');

    /**
     * Validations
     */
    protected $rules = array(
        'name'      => 'required',
        'firstname' => 'required',
        'lastname'  => 'required',
        'address1'  => 'required',
        'zipcode'   => 'required',
        'city'      => 'required',
        'country'   => 'required', // 'required|country',
    );

    /**
     *
     */
    protected function performInsert(\Illuminate\Database\Eloquent\Builder $query, array $options)
    {
        $this->attributes['uid'] = md5(uniqid(mt_rand(), true));

        parent::performInsert($query, $options);
    }

    /**
     * Relationships
     */
    public function user()
    {
        return $this->belongsTo('Subbly\\Model\\User');
    }
}
