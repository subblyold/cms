<?php

use Subbly\Model\User;
use Subbly\Tests\Support\TestCase;

class UsersControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->call('GET', '/api/v1/users');

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('users', $json->response);
        $this->assertJSONCollectionResponse($json->response);
        $this->assertEquals(0, $json->response->offset);
        $this->assertEquals(User::count(), $json->response->total);
    }

    public function testSearch()
    {
        /**
         * NOT OK
         */
        $response = $this->call('GET', '/api/v1/users/search');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        /**
         * OK
         */
        $searchQuery = 'jo';
        $response    = $this->call('GET', '/api/v1/users/search', array('q' => $searchQuery));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('users', $json->response);
        $this->assertJSONCollectionResponse($json->response);
        $this->assertEquals($searchQuery, $json->response->query);
    }

    public function testShow()
    {
        $user = TestCase::getFixture('users.user_1');

        $response = $this->call('GET', "/api/v1/users/{$user->uid}");

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();

        $this->assertJSONTypes('user', array(
            'firstname'  => 'string',
            'lastname'   => 'string',
            'email'      => 'string',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ));

        $this->assertJSONEquals('user', $user);
    }

    public function testStore()
    {
        /**
         * NOT OK
         */
        // "user" not defined
        $response = $this->call('POST', '/api/v1/users');

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        // "user" defined but empty
        $response = $this->call('POST', '/api/v1/users', array('user' => array()));

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
        $response = $this->call('POST', '/api/v1/users', array('user' => $data));

        $this->assertResponseStatus(201);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('user', $json->response);
        $this->assertObjectHasAttribute('uid', $json->response->user);
        $this->assertEquals($data['email'], $json->response->user->email);
        $this->assertEquals($data['firstname'], $json->response->user->firstname);
        $this->assertEquals($data['lastname'], $json->response->user->lastname);
        $this->assertEquals($data['email'], $json->response->user->email);
    }

    public function testUpdate()
    {
        $user = TestCase::getFixture('users.user_8');

        /**
         * NOT OK
         */
        // "user" not defined
        $response = $this->call('PATCH', "/api/v1/users/{$user->uid}");

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        // "user" defined but empty
        $response = $this->call('PATCH', "/api/v1/users/{$user->uid}", array('user' => array()));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('user', $json->response);
        $this->assertObjectHasAttribute('uid', $json->response->user);
        $this->assertEquals($user->email, $json->response->user->email);
        $this->assertEquals($user->firstname, $json->response->user->firstname);
        $this->assertEquals($user->lastname, $json->response->user->lastname);
        $this->assertEquals($user->email, $json->response->user->email);

        // "users" with datas
        $data = array(
            'firstname' => TestCase::faker()->firstName,
        );
        $response = $this->call('PATCH', "/api/v1/users/{$user->uid}", array('user' => $data));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('user', $json->response);
        $this->assertObjectHasAttribute('uid', $json->response->user);
        $this->assertEquals($user->email, $json->response->user->email);
        $this->assertEquals($data['firstname'], $json->response->user->firstname);
        $this->assertEquals($user->lastname, $json->response->user->lastname);
        $this->assertEquals($user->email, $json->response->user->email);
    }
}
