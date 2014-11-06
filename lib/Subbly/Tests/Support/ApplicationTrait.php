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
     * @param boolean  $assoc When TRUE, returned objects will be converted into associative arrays.
     *
     * @return mixed
     */
    public function getJSONContent($assoc = false)
    {
        $response = $this->client->getResponse();

        return json_decode($response->getContent(), $assoc);
    }

    /**
     * Call the given URI with JSON format and return the JSON Response.
     *
     * @param string  $method
     * @param string  $uri
     * @param array   $params
     * @param array   $headers
     * @param array   $files
     *
     * @return \Illuminate\Http\Response
     */
    public function callJSON($method, $uri, array $params = array(), array $headers = array(), array $files = array())
    {
        $headers = array_replace(array(
            'Content-Type' => 'application/json',
            'Accept'       => 'application/json',
        ), $headers);

        foreach ($headers as $k=>$v) {
            $headers['HTTP_' . $k] = $v;
        }

        // if (in_array(strtoupper($method), array('POST', 'PUT', 'PATCH'))) {
        if (in_array(strtoupper($method), array('PUT', 'PATCH'))) {
            return $this->call($method, $uri, array(), $files, $headers, json_encode($params));
        }
        else {
            return $this->call($method, $uri, $params, $files, $headers);
        }
    }
}
