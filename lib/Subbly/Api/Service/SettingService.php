<?php

namespace Subbly\Api\Service;

use Carbon\Carbon;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Collection;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

use Subbly\Model\Setting;

class SettingService extends Service
{
    const CACHE_NAME = 'subbly.settings';

    /** @var \Illuminate\Support\Collection $defaults */
    private $defaults = null;

    /**
     * Initialize the service.
     */
    protected function init()
    {
        $this->defaults = new Collection();
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

        $this->defaults = new Collection(
            array_merge($this->defaults->toArray(), $yaml['default_settings'])
        );
    }

    /**
     * Get all Setting
     *
     * @return \Illuminate\Support\Collection
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
        return $this->defaults->offsetExists($key);
    }

    /**
     * Update a setting value
     *
     * @param string  $key   The setting key
     * @param mixed   $value The setting value
     *
     * @return boolean
     *
     * @throws \Subbly\Api\Service\Exception If the setting key does not exists
     * @throws \Subbly\Api\Service\Exception If the setting value has not the good format
     *
     * @api
     */
    public function update($key, $value)
    {
        if ($this->fireEvent('updating', array($key, $value)) === false) return false;

        if (!is_string($key) || !$this->has($key)) {
            throw new Exception(sprintf(Exception::SETTING_KEY_NOT_EXISTS, $key));
        }

        $default = $this->defaults($key);
        if (isset($default['type']) && gettype($value) !== $default['type']) {
            throw new Exception(sprintf(Exception::SETTING_VALUE_WRONG_TYPE,
                json_encode($value),
                $key,
                $default['type']
            ));
        }

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

        return true;
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
            if (!$this->has($key)) {
                throw new Exception(sprintf(Exception::SETTING_KEY_NOT_EXISTS, $key));
            }

            return $this->defaults->offsetGet($key);
        }

        return $this->defaults->toArray();
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
     * @return \Illuminate\Support\Collection
     */
    private function getCachedSettings()
    {
        if (!Cache::has(self::CACHE_NAME))
        {
            $this->initCachedSettings();
        }

        return new Collection(
            Cache::get(self::CACHE_NAME)
        );
    }

    /**
     * Set new settings data to cache
     *
     * @param \Illuminate\Support\Collection  $settings
     */
    private function setCachedSettings(Collection $settings)
    {
        $expiresAt = Carbon::now()->addMinutes(15);
        $settings  = $settings->toArray();

        Cache::put(self::CACHE_NAME, $settings, $expiresAt);

        $this->fireEvent('cache_updated', array($settings));
    }

    /**
     *
     *
     * @return \Illuminate\Support\Collection
     */
    private function initCachedSettings()
    {
        $settings = new Collection;

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
