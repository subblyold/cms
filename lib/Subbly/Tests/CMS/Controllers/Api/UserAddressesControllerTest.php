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
}
