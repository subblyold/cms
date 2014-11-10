<?php

namespace Subbly\CMS\Controllers\Api;

class StatsController extends BaseController
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
     * Return global stats
     *
     * @route GET /api/v1/stats
     * @authentication required
     */
    public function index()
    {
        // TODO must return
        return $this->jsonResponse('ok');
    }
}
