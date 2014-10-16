<?php

namespace Subbly\Core;

use Pimple;

class Container extends Pimple\Container
{
//     private static $_instance;

//     private function __construct () {}
//     private function __clone () {}

//     public static function access() {
//         if (!(self::$_instance instanceof self)) {
//             self::$_instance = new self();
//         }

//         return self::$_instance;
//     }
// Subbly\Core\Container::->get('api');
    /**
     * Construct
     */
    public function __construct()
    {
        parent::__construct();

        $this->load();
    }

    /**
     * Load services
     */
    public function load()
    {
        $c = $this;

        $this['api'] = $this->share(function () use ($c) {
            return new \Subbly\Api($c);
        });
    }

    /**
     * Ask if named service exists
     *
     * @param string  $name  Name of the service to check
     *
     * @return boolean
     */
    public function has($name)
    {
        return $this->offsetExists($name);
    }

    /**
     * Get a service from is name
     *
     * @param string  $name  Name of the service to called
     *
     * @return mixed
     */
    public function get($name)
    {
        return $this->offsetGet($name);
    }
}
