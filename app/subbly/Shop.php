<?php

namespace Subbly;

/**
 * Class Shop
 * @package Subbly
 */

class Shop
{
    static protected $options;
    static protected $optionValues;
    static protected $optionDefaults;
    static protected $groupedOptions;

    static public function initialize()
    {
        Shop\OptionForm::registerDefaultInputTypes();

        self::$optionValues = Shop\OptionModel::lists('value', 'name');
        self::$groupedOptions = array();
    }

    static public function registerOption($option, array $config = array())
    {
        if( isset(self::$options[$option]) )
        {
            throw new Shop\Exception('Option key ' . $option . ' is already defined.');
        }

        $config['name'] = $option;

        self::$options[$option] = $config;

        if( isset($config['default']) )
        {
            self::$optionDefaults = $config['default'];
        }

        if( isset($config['group']) )
        {
            if( !isset($config['subgroup']) )
            {
                throw new Shop\Exception('Option key ' . $option . ' has a group but no subgroup.');
            }

            self::$groupedOptions[ $config['group'] ][ $config['subgroup'] ][] = $config;
        }
        else if( isset($config['subgroup']) )
        {
            throw new Shop\Exception('Option key ' . $option . ' has a subgroup but no group.');
        }
    }

    static public function hasOption($option)
    {
        return isset(self::$optionDefaults[$option]) || isset(self::$optionValues[$option]);
    }

    static public function getOptionOrNull($option)
    {
        if( self::hasOption($option) )
        {
            return self::getOption($option);
        }

        return null;
    }

    static public function getOption($option)
    {
        if( !self::hasOption($option) )
        {
            throw new Shop\Exception('No option at key ' . $option);
        }

        if( isset(self::$optionValues[$option]) )
        {
            return self::$optionValues[$option];
        }
        else if( isset(self::$optionDefaults[$option]) )
        {
            return self::$optionDefaults[$option];
        }
    }

    static public function setOption($option, $value)
    {
        Shop\OptionModel::replaceValues([$option => $value]);
        self::$optionValues[ $option ] = $value;
    }

    static public function getOptionConfig($option)
    {
        if( isset(self::$options[$option]) )
        {
            return self::$options[$option];
        }
        else
        {
            throw new Shop\Exception('Option ' . $option . ' has no configuration.');
        }
    }

    static public function getFlatOptions()
    {
        return self::$options;
    }

    static public function getGroupedOptions()
    {
        return self::$groupedOptions;
    }

    static public function setOptions($options)
    {
        Shop\OptionModel::replaceValues($options);
        self::$optionValues = array_merge(self::$optionValues, $options);
    }
}