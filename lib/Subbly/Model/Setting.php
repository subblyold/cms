<?php

namespace Subbly\Model;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model implements ModelInterface
{
    use Concerns\SubblyModel;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'settings';

    protected $primaryKey = 'identifier';

    protected $fillable = array('identifier', 'plugin_identifier', 'value');

    /**
     *
     */
    public function setValueAttribute($value)
    {
        $this->attributes['type']  = gettype($value);

        switch ($this->attributes['type'])
        {
            case 'array':
                $this->attributes['value'] = json_encode($value);
                break;

            case 'null':
            case 'NULL':
                $this->attributes['value'] = 'null';
                break;

            default:
                $this->attributes['value'] = $value;
                break;
        }
    }

    /**
     *
     */
    public function getValueAttribute()
    {
        switch ($this->attributes['type'])
        {
            case 'string':
                return (string) $this->attributes['value'];

            case 'integer':
                return (int) $this->attributes['value'];

            case 'double':
                return (double) $this->attributes['value'];

            case 'boolean':
                return $this->attributes['value'] == '1' ? true : false;

            case 'array':
                return json_decode($this->attributes['value'], true);

            case 'NULL':
                return NULL;

            default:
                return;
        }
    }
}
