<?php

namespace Subbly\Model;

class UserAddress extends Model
{
    use Trait\AddressTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_addresses';

    public function user()
    {
        return $this->belongsTo('User');
    }
}
