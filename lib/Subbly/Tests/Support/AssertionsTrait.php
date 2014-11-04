<?php

namespace Subbly\Tests\Support;

trait AssertionsTrait
{
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
    }

}
