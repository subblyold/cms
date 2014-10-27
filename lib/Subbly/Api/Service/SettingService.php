<?php

namespace Subbly\Api\Service;

use Carbon\Carbon;

use Illuminate\Support\Facades\Cache;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

use Subbly\Model\Setting;

class SettingService extends Service
{
    const CACHE_NAME = 'subbly.settings';

    /** @var \ArrayObject $defaults */
    private $defaults = null;

    /**
     * Initialize the service.
     */
    protected function init()
    {
        $this->defaults = new \ArrayObject();
    }

    /**
     * Register a new default settings file
     *
     * @param string  $filename Name of the file
     *
     * @throws Subbly\Api\Service\Exception
     * @throws Subbly\Api\Service\Exception
     *
     * @api
     */
    public function registerDefaultSettings($filename)
    {
        // TODO Just register the filename and load the content after?

        if (!file_exists($filename)) {
            throw new Exception(sprintf('File for defaults settings with filename "%s" is not found', $filename));
        }

        try {
            $yaml = Yaml::parse(file_get_contents($filename));
        }
        catch (ParseException $e) {
            throw new Exception(sprintf('Unable to parse the YAML string: %s', $e->getMessage()));
        }

        if (!isset($yaml['default_settings']) && !is_array($yaml['default_settings'])) {
            throw new Exception(sprintf('The defaults settings file "%s" must have "%s" key as root',
                $filename,
                'default_settings'
            ));
        }

        $this->defaults = new \ArrayObject(
            array_merge_recursive($this->defaults->getArrayCopy(), $yaml['default_settings'])
        );
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
     * @throws \Subbly\Api\Service\Exception
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
     * Ask if a setting key exists or not
     *
     * @param string  $key  The setting key
     *
     * @return boolean
     *
     * @api
     */
    public function has($key)
    {
        // TODO check the defaults key instead?
        $exists = $this->getCachedSettings()->offsetExists($key);

        /**
         * Refresh cache if
         */
        if (!$exists)
        {
            $this->refresh();

            $exists = $this->getCachedSettings()->offsetExists($key);
        }

        return $exists;
    }

    /**
     * Update a setting value
     *
     * @param string  $key   The setting key
     * @param mixed   $value The setting value
     *
     * @api
     */
    public function update($key, $value)
    {
        if ($this->fireEvent('updating', array($key, $value)) === false) return false;
        // TODO check that identifier is defined into default_settings.yml

        // TODO check the $value type in function of the default setting informations

        $settings = $this->getCachedSettings();
        // TODO identifier = PACKAGE_NAME.KEY_NAME
        // Example subbly.shop_name

        $setting = Setting::firstOrNew(array(
            'identifier'        => $key,
            'plugin_identifier' => '',
        ));

        $setting->value = $value;

        $setting->setCaller($this);
        $setting->save();

        $settings->offsetSet($key, $value);

        $this->setCachedSettings($settings);

        $this->fireEvent('updated', array($key, $value));
    }

    /**
     * Get defaults settings
     *
     * @param string|null  $key A setting key (optional)
     *
     * @return array
     *
     * @throws \Subbly\Api\Service\Exception
     *
     * @api
     */
    public function defaults($key = null)
    {
        if (is_string($key))
        {
            if (!$this->defaults->offsetExists($key)) {
                throw new Exception(sprintf(Exception::SETTING_KEY_NOT_EXISTS, $key));
            }

            return $this->defaults->offsetGet($key);
        }

        return $this->defaults->getArrayCopy();
    }

    /**
     * Refresh the settings
     *
     * @param boolean  $force Force the refresh
     *
     * @api
     */
    public function refresh($force = false)
    {
        $this->initCachedSettings();
    }

    /**
     * Get the cached settings data
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
     * Set new settings data to cache
     *
     * @param \ArrayObject  $settings
     */
    private function setCachedSettings(\ArrayObject $settings)
    {
        $expiresAt = Carbon::now()->addMinutes(15);
        $settings  = $settings->getArrayCopy();

        Cache::put(self::CACHE_NAME, $settings, $expiresAt);

        $this->fireEvent('cache_updated', array($settings));
    }

    /**
     *
     *
     * @return \ArrayObject
     */
    private function initCachedSettings()
    {
        $settings = new \ArrayObject;

        // Defaults settings
        foreach ($this->defaults as $k=>$v)
        {
            if (isset($v['value'])) {
                $settings->offsetSet($k, $v['value']);
            }
        }

        // Database settings
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
