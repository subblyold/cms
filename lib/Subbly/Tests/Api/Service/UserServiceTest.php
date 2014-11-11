<?php

use Subbly\Subbly;
use Subbly\Api\Api;
use Subbly\Api\Service\UserService;
use Subbly\Core\Container;
use Subbly\Tests\Support\TestCase;

class UserServiceTest extends TestCase
{
    private function getService()
    {
        return Subbly::api('subbly.user');
    }

    public function testConstruct()
    {
        $api = new Api(new Container(), array());
        $s   = new UserService($api);

        $this->assertNotNull($s);
    }

    public function testNewUser()
    {
        $instance = $this->getService()->newUser();
        $this->assertInstanceOf('Subbly\\Model\\User', $instance);
    }

    public function testAll()
    {
        $all = $this->getService()->all();

        $this->assertInstanceOf('Illuminate\\Database\\Eloquent\\Collection', $all);

        // TODO test pagination
    }

    public function testFind()
    {
        $fixture = TestCase::getFixture('users.jon_snow');
        $uid     = $fixture->uid;
        $user    = $this->getService()->find($uid);

        $this->assertInstanceOf('Subbly\\Model\\User', $user);
        $this->assertEquals($fixture->firstname, $user->firstname);
        $this->assertEquals($fixture->lastname, $user->lastname);
    }

    public function testSearchBy()
    {
        $fixture = TestCase::getFixture('users.user_2');
        $uid     = $fixture->uid;
        $user    = $this->getService()->searchBy(array(
            'firstname' => $fixture->lastname,
        ));

        $this->assertInstanceOf('Illuminate\\Database\\Eloquent\\Collection', $user);

        // TODO test pagination
    }

    public function testCreate()
    {
        $email = 'jon.snow2@test.subbly.com';

        // Events
        Subbly::events()->listen($this->getService()->name() . ':creating', function($user) use ($email)
        {
            $this->assertEquals($email, $user->email);
            Subbly::events()->forget($this->getService()->name() . ':creating');
        });
        Subbly::events()->listen($this->getService()->name() . ':created', function($user) use ($email)
        {
            $this->assertEquals($email, $user->email);
            Subbly::events()->forget($this->getService()->name() . ':created');
        });

        $user = $this->getService()->newUser();
        $user->email     = $email;
        $user->firstname = 'Jon';
        $user->lastname  = 'Snow';
        $user->password  = uniqid();

        $returnedUser = $this->getService()->create($user);

        $this->assertInstanceOf('Subbly\\Model\\User', $user);
        $this->assertEquals($email, $user->email);

        $this->assertInstanceOf('Subbly\\Model\\User', $returnedUser);
        $this->assertEquals($email, $returnedUser->email);
    }

    public function testUpdate()
    {
        $faker = TestCase::faker();
        $user  = TestCase::getFixture('users.user_1');

        // Events
        Subbly::events()->listen($this->getService()->name() . ':updating', function($model) use ($user)
        {
            $this->assertEquals($user->id, $model->id);
            Subbly::events()->forget($this->getService()->name() . ':updating');
        });
        Subbly::events()->listen($this->getService()->name() . ':updated', function($model) use ($user)
        {
            $this->assertEquals($user->id, $model->id);
            Subbly::events()->forget($this->getService()->name() . ':updated');
        });

        $user->firstname = $faker->firstname;
        $user->lastname  = $faker->lastname;

        $returnedUser = $this->getService()->update($user);

        $this->assertInstanceOf('Subbly\\Model\\User', $user);
        $this->assertInstanceOf('Subbly\\Model\\User', $returnedUser);
        $this->assertEquals($user->id, $returnedUser->id);
    }

    public function testDelete()
    {
        // TODO
    }

    public function testName()
    {
        $this->assertEquals($this->getService()->name(), 'subbly.user');
    }
}
