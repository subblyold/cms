<?php

namespace Subbly\Shop;

# TODO investigate event mgmt for options (multiple inserts with event support?)

/**
 * Class OptionModel
 * @package Subbly\Shop
 */
class OptionModel extends \Eloquent
{
    protected $table = 'shop_options';
    public $timestamps = false;
    public $incrementing = false;

    # TODO move base work to base model
    /**
     * Replace option values
     *
     * @param array $options
     * @return bool
     */
    static public function replaceValues($options)
    {
        $names = array_keys($options);
        self::query()->whereIn('name',$names)->delete();
        $rows = array();

        foreach($options as $k => $v)
        {
            $rows[] = array('name' => $k, 'value' => $v);
        }

        return self::createMany($rows);
    }

    # TODO move this to a base model
    /**
     * Create many rows at once
     *
     * @param array $rows
     * @return bool
     */
    static public function createMany($rows)
    {
        return self::query()->insert($rows);
    }
}