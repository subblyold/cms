<?php

use Subbly\Subbly;
use Subbly\Api\Api;
use Subbly\Api\Service\UserAddressService;
use Subbly\Core\Container;
use Subbly\Tests\Support\TestCase;

class UserAddressServiceTest extends TestCase
{
    private function getService()
    {
        return Subbly::api('subbly.user_address');
    }

    public function testConstruct()
    {
        $api = new Api(new Container(), array());
        $s   = new UserAddressService($api);

        $this->assertNotNull($s);
    }

    public function testNewUserAddress()
    {
        $instance = $this->getService()->newUserAddress();
        $this->assertInstanceOf('Subbly\\Model\\UserAddress', $instance);
    }

    public function testFind()
    {
        $fixture     = TestCase::getFixture('user_addresses.user_address_4');
        $uid         = $fixture->uid;
        $userAddress = $this->getService()->find($uid);

        $this->assertInstanceOf('Subbly\\Model\\UserAddress', $userAddress);
        $this->assertEquals($fixture->name, $userAddress->name);
        $this->assertEquals($fixture->firstname, $userAddress->firstname);
        $this->assertEquals($fixture->lastname, $userAddress->lastname);
    }

    public function testFindByUser()
    {
        // TODO
    }

    public function testCreate()
    {
        // TODO
    }

    public function testUpdate()
    {
        // TODO
    }

    public function testDelete()
    {
        // TODO
    }

    public function testName()
    {
        $this->assertEquals($this->getService()->name(), 'subbly.user_address');
    }
}
