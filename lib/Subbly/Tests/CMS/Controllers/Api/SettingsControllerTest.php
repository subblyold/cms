<?php

use Subbly\Subbly;
use Subbly\Tests\Support\TestCase;

class SettingsControllerTest extends TestCase
{
    public function testIndex()
    {
        $response = $this->callJSON('GET', '/api/v1/settings');

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent(true);
        $this->assertArrayHasKey('settings', $json['response']);
        $this->assertCount(Subbly::api('subbly.setting')->all()->count(), $json['response']['settings']);
    }

    public function testUpdate()
    {
        $setting      = Subbly::api('subbly.setting')->all()->take(1)->toArray();
        $settingValue = reset($setting);
        $settingKey   = key($setting);

        /**
         * NOT OK
         */
        // "setting" not defined
        $response = $this->callJSON('PATCH', "/api/v1/settings/{$settingKey}");

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        /**
         * OK
         */
        $settingKey = 'test.subbly.string_setting';

        // Test with string setting and with wrong array value
        $response = $this->callJSON('PATCH', "/api/v1/settings/{$settingKey}", array('value' => array()));

        $this->assertResponseStatus(400);
        $this->assertResponseJSONValid();

        $json = $this->getJSONContent();
        $this->assertObjectHasAttribute('error', $json->response);

        // "settings" with datas
        $value = TestCase::faker()->word;
        $response = $this->callJSON('PATCH', "/api/v1/settings/{$settingKey}", array('value' => $value));

        $this->assertResponseOk();
        $this->assertResponseJSONValid();

        // $json = $this->getJSONContent();
    }
}
