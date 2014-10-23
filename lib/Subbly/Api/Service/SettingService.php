<?php

namespace Subbly\Api\Service;

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;

use Subbly\Model\Setting;

class SettingService extends Service
{
    const CACHE_NAME = 'subbly.settings';

    /**
     * Initialize the service
     */
    protected function init()
    {
    }

    /**
     * Register a new default settings file
     *
     * @param string  $filename Name of the file
     *
     * @api
     */
    public function registerDefaultSettings($filename)
    {
        // TODO check if file exists
        // TODO load yaml


    }

    /**
     * Get all Setting
     *
     * @return \ArrayObject
     *
     * @api
     */
    public function all()
    {
        return $this->getCachedSettings();
    }

    /**
     * Get a Setting value
     *
     * @param string  $key  The setting key
     *
     * @return mixed
     *
     * @api
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
        $this->fireEvent('adding', array($key, $value));
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

        $this->fireEvent('added', array($key, $value));
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
        $settings  = $settings->getArrayCopy();

        Cache::put(self::CACHE_NAME, $settings, $expiresAt);

        $this->fireEvent('updated', array($settings));
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
     * Service name
     */
    public function name()
    {
        return 'subbly.setting';
    }
}
