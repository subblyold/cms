<?php

use Subbly\Model\User;
use Subbly\Tests\Support\TestCase;

class UsersControllerTest extends TestCase
{
    private $userJSONFormat = array(
        'firstname'  => 'string',
        'lastname'   => 'string',
        'email'      => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    );


    public function testIndex()
    {
        $response = $this->callJSON('GET', '/api/v1/users');

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONCollectionResponse('users');
        $this->assertJSONTypes('users[0]', $this->userJSONFormat);
        $this->assertEquals(0, $json->response->offset);
        $this->assertEquals(User::count(), $json->response->total);
    }

    public function testSearch()
    {
        /**
         * NOT OK
         */
        $response = $this->callJSON('GET', '/api/v1/users/search');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        /**
         * OK
         */
        $searchQuery = 'jo';
        $response    = $this->callJSON('GET', '/api/v1/users/search', array('q' => $searchQuery));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONCollectionResponse('users');
        $this->assertJSONTypes('users[0]', $this->userJSONFormat);
        $this->assertEquals($searchQuery, $json->response->query);
    }

    public function testShow()
    {
        // TODO test 404

        $user = TestCase::getFixture('users.user_1');

        $response = $this->callJSON('GET', "/api/v1/users/{$user->uid}");

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('user', $this->userJSONFormat);
        $this->assertJSONEquals('user', $user);
    }

    public function testStore()
    {
        /**
         * NOT OK
         */
        // "user" not defined
        $response = $this->callJSON('POST', '/api/v1/users');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        // "user" defined but empty
        $response = $this->callJSON('POST', '/api/v1/users', array('user' => array()));

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        $data = array(
            'firstname' => TestCase::faker()->firstName,
            'lastname'  => TestCase::faker()->lastName,
            'email'     => TestCase::faker()->email,
            'password'  => TestCase::faker()->password,
        );
        $response = $this->callJSON('POST', '/api/v1/users', array('user' => $data));

        $this->assertResponseStatus(201);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('user', $this->userJSONFormat);
        $this->assertJSONEquals('user', $data);
    }

    public function testUpdate()
    {
        $user = TestCase::getFixture('users.user_8');

        /**
         * NOT OK
         */
        // "user" not defined
        $response = $this->callJSON('PATCH', "/api/v1/users/{$user->uid}");

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        // "user" defined but empty
        $response = $this->callJSON('PATCH', "/api/v1/users/{$user->uid}", array('user' => array()));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('user', $this->userJSONFormat);
        $this->assertJSONEquals('user', $user);

        // "users" with datas
        $data = array(
            'firstname' => TestCase::faker()->firstName,
        );
        $response = $this->callJSON('PATCH', "/api/v1/users/{$user->uid}", array('user' => $data));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('user', $this->userJSONFormat);
        $this->assertJSONEquals('user', $data);
    }
}
