<?php

namespace Subbly\CMS\Controllers\Api;

use Input;

use Subbly\Subbly;

class SettingsController extends BaseController
{
    /**
     * The constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->beforeFilter('@processAuthentication');
    }


    /**
     * Get Setting list
     *
     * @route GET /api/settings
     * @authentication required
     */
    public function index()
    {
        $settings = Subbly::api('subbly.setting')->all();

        return $this->jsonResponse(array(
            'settings'  => $settings,
        ));
    }

    /**
     * Update a Setting
     *
     * @route PUT|PATCH /api/settings/{setting_key}
     * @authentication required
     */
    public function update($setting_key)
    {
        if (!Input::has('value')) {
            return $this->jsonErrorResponse('"value" is required.');
        }

        $user = Subbly::api('subbly.setting')->update($setting_key, Input::get('value'));

        return $this->jsonResponse(array(), array(
            'status' => array(
                'code'    => 200,
                'message' => 'Setting updated',
            ),
        ));
    }
}
