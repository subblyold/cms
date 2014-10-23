<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model implements ModelInterface
{
    use Concerns\SubblyModel;
    use Concerns\AddressTrait;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'user_addresses';

    public function user()
    {
        return $this->belongsTo('Subbly\\Model\\User');
    }
}
