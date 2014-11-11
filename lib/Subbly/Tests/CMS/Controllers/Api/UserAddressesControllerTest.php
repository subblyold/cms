<?php

use Subbly\Model\User;
use Subbly\Model\UserAddress;
use Subbly\Tests\Support\TestCase;

class UserAddressesControllerTest extends TestCase
{
    private $userAddressJSONFormat = array(
        'name'         => 'string',
        'firstname'    => 'string',
        'lastname'     => 'string',
        'lastname'     => 'string',
        'address1'     => 'string',
        'address2'     => array('string', 'null'),
        'zipcode'      => 'string',
        'city'         => 'string',
        'country'      => 'string',
        'phone_work'   => array('string', 'null'),
        'phone_home'   => array('string', 'null'),
        'phone_mobile' => array('string', 'null'),
        'created_at'   => 'datetime',
        'updated_at'   => 'datetime',
    );


    public function testIndex()
    {
        $user = TestCase::getFixture('users.jon_snow');

        $response = $this->callJSON('GET', "/api/v1/users/{$user->uid}/user-addresses");

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONCollectionResponse('user_addresses');
        $this->assertJSONTypes('user_addresses[0]', $this->userAddressJSONFormat);
        $this->assertEquals(0, $json->response->offset);
        $this->assertEquals(UserAddress::where('user_id', '=', $user->id)->count(), $json->response->total);
    }

    public function testStore()
    {
        $faker = TestCase::faker();
        $user  = TestCase::getFixture('users.jon_snow');

        /**
         * NOT OK
         */
        // "user_address" not defined
        $response = $this->callJSON('POST', "/api/v1/users/{$user->uid}/user-addresses");

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        // :user_uid not found
        $response = $this->callJSON('POST', "/api/v1/users/b42fdf18bbd6291136f3b48b9ab378dd/user-addresses");

        $this->assertResponseStatus(404);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        // "user" defined but empty
        $response = $this->callJSON('POST', "/api/v1/users/{$user->uid}/user-addresses", array('user_address' => array()));

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        $data = array(
            'name'      => $faker->name,
            'firstname' => $faker->firstName,
            'lastname'  => $faker->lastName,
            'address1'  => $faker->streetAddress,
            'zipcode'   => $faker->postcode,
            'city'      => $faker->city,
            'country'   => $faker->country,
        );
        $response = $this->callJSON('POST', "/api/v1/users/{$user->uid}/user-addresses", array('user_address' => $data));

        $this->assertResponseStatus(201);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertJSONTypes('user_address', $this->userAddressJSONFormat);
        $this->assertJSONEquals('user_address', $data);
    }
}
