<?php

namespace Subbly\Api\Service;

use Cache, Carbon\Carbon;

use Subbly\Model\Setting;

class SettingService extends Service
{
    const CACHE_NAME = 'subbly.settings';

    /**
     *
     */
    protected function init()
    {

    }

    /**
     *
     */
    public function registerDefaultSettings($filename)
    {
        // TODO check if file exists
        // TODO load yaml


    }

    /**
     *
     */
    public function all()
    {
        return $this->getCachedSettings();
    }

    /**
     *
     */
    public function get($key)
    {
        if (!$this->has($key)) {
            throw new Exception(sprintf(Exception::SETTING_KEY_NOT_EXISTS, $key));
        }

        return $this->getCachedSettings()->offsetGet($key);
    }

    /**
     *
     */
    public function has($key)
    {
        return $this->getCachedSettings()->offsetExists($key);
    }

    /**
     *
     */
    public function add($key, $value)
    {
        // TODO check that identifier is defined into default_settings.yml

        $settings = $this->getCachedSettings();
        // TODO identifier = PACKAGE_NAME.KEY_NAME
        // Example subbly.shop_name

        $setting = Setting::firstOrNew(array(
            'identifier'        => $key,
            'plugin_identifier' => '',
        ));

        $setting->value = $value;
        $this->saveModel($setting);

        $settings->offsetSet($key, $value);

        $this->setCachedSettings($settings);
    }

    /**
     *
     * @return \ArrayObject
     */
    private function getCachedSettings()
    {
        if (! Cache::has(self::CACHE_NAME))
        {
            $this->initCachedSettings();
        }

        return new \ArrayObject(
            Cache::get(self::CACHE_NAME)
        );
    }

    /**
     *
     * @param \ArrayObject  $settings
     */
    private function setCachedSettings(\ArrayObject $settings)
    {
        $expiresAt = Carbon::now()->addMinutes(15);

        Cache::put(self::CACHE_NAME, $settings->getArrayCopy(), $expiresAt);

        $event = $this->fireEvent('cache_updated');
    }

    /**
     *
     */
    private function initCachedSettings()
    {
        $settings = new \ArrayObject;

        foreach (Setting::all() as $s) {
            $settings->offsetSet($s->identifier, $s->value);
        }

        $this->setCachedSettings($settings);

        return $settings;
    }

    /**
     *
     */
    public function name()
    {
        return 'subbly.setting';
    }
}
