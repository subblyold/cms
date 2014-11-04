<?php

namespace Subbly\Tests\Support;

trait ApplicationTrait
{
    /** @var \Faker\Generator  $faker */
    private static $faker;

    /**
     * Get the faker generator
     *
     * @return \Faker\Generator
     */
    public static function faker()
    {
        if (!isset(self::$faker)) {
            self::$faker = \Faker\Factory::create();
        }

        return self::$faker;
    }

    /**
     * Get the decode JSON content response
     *
     * @return \StdClass
     */
    public function getJSONContent()
    {
        $response = $this->client->getResponse();

        return json_decode($response->getContent());
    }
}
