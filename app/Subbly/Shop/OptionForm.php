<?php

namespace Subbly\Shop;
use Subbly\Shop as Shop;

#TODO laravel validator for siret/siren
#TODO Form::macro('country')
#TODO country validation rule
#TODO zipcode validation rule
#TODO input type country, zipcode
/**
 * Class OptionForm
 * @package Subbly\Shop
 */
class OptionForm
{
    /**
     * Stores the different option types
     *
     * @var array
     */
    static protected $types;

    /**
     * Registers default input types: text, textarea, email, number, select
     *
     * @throws Exception
     */
    static public function registerDefaultInputTypes()
    {
        self::registerInputType('text', [
           'input' => function($option)
           {
               return \Form::text('options[' . $option . ']', Shop::getOptionOrNull($option));
           }
        ]);

        self::registerInputType('textarea', [
            'input' => function($option)
            {
                return \Form::textarea('options[' . $option . ']', Shop::getOptionOrNull($option));
            }
        ]);

        self::registerInputType('email', [
            'input' => function($option)
            {
                return \Form::email('options[' . $option . ']', Shop::getOptionOrNull($option));
            },
            'validation' => 'email',
        ]);

        self::registerInputType('number', [
            'input' => function($option)
            {
                return \Form::number('options[' . $option . ']', Shop::getOptionOrNull($option));
            },
            'validation' => 'numeric',
        ]);

        self::registerInputType('select', [
           'input' => function($option)
           {
               $config = Shop::getOptionConfig($option);

               if( !isset($config['values']) )
               {
                   throw new Shop\Exception('No values set for option at key ' . $option);
               }

               return \Form::select('options[' . $option . ']', $config['values'], Shop::getOptionOrNull($option));
           },
           'validation' => function($option)
           {
               $config = Shop::getOptionConfig($option);

               if( !isset($config['values']) )
               {
                   throw new Shop\Exception('No values set for option at key ' . $option);
               }

               if( is_object($config['values']) && $config['values'] instanceof Closure )
               {
                   $values = $config['values']();
               }
               else
               {
                   $values = $config['values'];
               }

               return 'in:' . implode(',', array_keys($values));
           }
        ]);
    }

    /**
     * Creates a new type of option
     *
     * @param string $type
     * @param array $config Must contain at least input callback which returns the html for such input type
     * @throws Exception
     */
    static public function registerInputType($type, array $config)
    {
        if( !isset($config['input']) )
        {
            throw new Shop\Exception('No input callback for option type ' . $type);
        }

        self::$types[$type] = $config;
    }

    /**
     * Returns input configuration for a given type
     *
     * @param string $type
     * @return array
     * @throws Exception
     */
    static public function getInputConfig($type)
    {
        if( !isset(self::$types[$type]) )
        {
            throw new Shop\Exception('No input type ' . $type);
        }

        return self::$types[$type];
    }

    /**
     * Returns the corresponding html input for a given configured option
     *
     * @param string $key
     * @return string
     * @throws Exception
     */
    static public function input($key)
    {
        $config = Shop::getOptionConfig($key);
        $type = self::getInputConfig($config['type']);

        return $type['input']($key);
    }

    /**
     * Returns Laravel validation rules for a given configured option
     *
     * @param string $key
     * @return string
     * @throws Exception
     */
    static public function getValidationRules($key)
    {
        $config = Shop::getOptionConfig($key);

        $rules = '';

        if( isset($config['validation']) )
        {
            $rules = $config['validation'];
        }

        $type = self::getInputConfig($config['type']);

        if( isset($type['validation']) )
        {
            if( !empty($rules) )
            {
                $rules = '|' . $rules;
            }

            if( is_object($type['validation']) && $type['validation'] instanceof Closure )
            {
                $rules = $type['validation']($key) . $rules;
            }
            else
            {
                $rules = $type['validation'] . $rules;
            }
        }

        return $rules;
    }

    /**
     * Validates a given set of options against the full validation scheme
     *
     * @param array $data
     * @return \Illuminate\Validation\Validator
     */
    static public function validate($data)
    {
        $rules = array();
        $options = Shop::getFlatOptions();

        foreach($options as $option)
        {
            if( isset($option['validation']) )
            {
                $rules[ $option['name'] ] = self::getValidationRules($option['name']);
            }
        }

        return \Validator::make($data, $rules);
    }
}