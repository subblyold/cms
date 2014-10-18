<?php

namespace Subbly\Tests\Api\Service;

use Subbly\Api\Api;
use Subbly\Api\Service\UserService;
use Subbly\Core\Container;

class UserServiceTest extends \PHPUnit_Framework_TestCase
{
    private function getApi()
    {
        return new Api(new Container(), array());
    }

    public function testConstruct()
    {
        $s = new UserService($this->getApi());

        $this->assertNotNull($s);
    }
}
