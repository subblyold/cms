<?php

namespace Subbly\Tests\Support;

trait AssertionsTrait
{
    private static $formats = array(
        'boolean',
        'integer',
        'double',
        'string',
        'array',
        'object',
        'null',
    );

    /**
     *
     */
    public function assertDateTimeString($date, $format)
    {
        $d = \DateTime::createFromFormat($format, $date);
        $this->assertEquals($d->format($format), $date);
    }

    /**
     * Assert that the client content-type response is an application/json.
     */
    public function assertResponseJSONValid()
    {
        $response = $this->client->getResponse();

        $actual = $response->headers->get('content-type');
        $this->assertEquals('application/json', $actual, 'Expected content-type header application/json, got ' . $actual);

        /**
         * Test JSON content
         */
        $json = json_decode($response->getContent());

        // Headers
        $this->assertObjectHasAttribute('headers', $json);
        $this->assertObjectHasAttribute('status', $json->headers);
        $this->assertEquals($response->getStatusCode(), $json->headers->status->code);
        $this->assertObjectHasAttribute('version', $json->headers);

        // Content
        $this->assertObjectHasAttribute('response', $json);
    }

    /**
     *
     *
     * @param stdClass  $response JSON reponse section
     */
    public function assertJSONCollectionResponse($response)
    {
        $this->assertObjectHasAttribute('offset', $response);
        $this->assertObjectHasAttribute('limit', $response);
        $this->assertObjectHasAttribute('total', $response);

        $this->assertJSONTypes(array(
            'offset' => 'integer',
            'limit'  => 'integer',
            'total'  => 'integer',
        ));
    }

    /**
     *
     */
    public function assertJSONEquals($key, $data)
    {
        $response = $this->client->getResponse();
        $json     = json_decode($response->getContent(), true);
        // TODO Use symfony property accessor instead??????????????????????????????????????
        $content  = $this->accessArrayFromString($key, $json['response']);

        foreach ($content as $k=>$v)
        {
            // var_dump((string)$data->{$k}, $v);
            // // TODO Use symfony property accessor
            // $this->assertEquals($data->{$k}, $v);
        }
    }

    // $this->assertJSONTypes('user', array(
    //     'firstname'  => 'string',
    //     'lasttname'  => 'string',
    //     'email'      => 'string',
    //     'created_at' => 'DateTime',
    //     'updated_at' => 'DateTime',
    // ));
    //
    // $this->assertJSONTypes(array(
    //     'users'  => 'array',
    //     'offset' => 'integer',
    //     'limit'  => 'integer',
    //     'total'  => 'integer',
    // ));

    /**
     *
     */
    public function assertJSONTypes()
    {
        // Formats function args
        $args   = func_get_args();
        $params = array(
            'key'         => null,
            'field_types' => array(),
        );

        if (count($args) === 0) {
            // TODO
            throw new Exception('');
        }

        if (count($args) === 1 && is_array($args[0]))
        {
            $params['field_types'] = $args[0];
        }
        else if (
            count($args) === 2
            && (is_string($args[0]) || is_null($args[0]))
            && is_array($args[1])
        ) {
            $params['key']         = $args[0];
            $params['field_types'] = $args[1];
        }
        else {
            // TODO
            throw new Exception('');
        }

        // Get JSON content
        $response = $this->client->getResponse();
        $json     = json_decode($response->getContent());
        // $json     = json_decode($response->getContent(), true);
        // TODO use array instead of StdClass!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $content  = $json->response;

        if ($params['key'] !== null) {
            $content = $this->accessArrayFromString($params['key'], $content);
        }

        // Check assertions
        foreach ($params['field_types'] as $fieldName=>$format)
        {
            if (!property_exists($content, $fieldName)) {
                // TODO
                throw new Exception('');
            }

            $value = $content->{$fieldName};

            if (is_array($format) && $this->isKeyValueArray($format))
            {
                // TODO nested func
                // $this->assertJSONTypes($fieldName, $format, $value)
            }
            else if (is_array($format))
            {
                // TODO do check for each formats
            }
            if (in_array($format, self::$formats)) {
                $this->assertInternalType($format, $value);
            }
            else if ($format === 'datetime') {
                $this->assertDateTimeString($value, \DateTime::ISO8601);
            }
            else if (class_exists($format)) {
                $this->assertInstanceOf($format, $value);
            }
            else {
                // throw new Exception('');
            }
        }
    }

    /**
     *
     */
    private function accessArrayFromString($key, $array)
    {
        if (strpos($key, '.') === false) {
            if ($array instanceof \StdClass) return $array->{$key};
            if (is_array($array))            return $array[$key];

            return null;
        }

        $pieces = explode('.', $name);
        $result = $array;

        foreach ($pieces as $piece)
        {
            if (is_array($result) && array_key_exists($piece, $result)) {
                $result = $result[$piece];
            }
            else if ($result instanceof \StdClass && property_exists($result, $piece)) {
                $result = $result->{$piece};
            }
            else {
                // TODO throw an exception?
                return null;
            }
        }

        return $result;
    }

    /**
     *
     */
    private function isKeyValueArray($array)
    {
        if (!is_array($array)) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }
}
