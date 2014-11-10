<?php

namespace Subbly\CMS\Controllers\Api;

class AuthController extends BaseController
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
     * Test user credentials
     *
     * @route GET /api/v1/auth/test-credentials
     * @authentication false
     */
    public function testCredentials()
    {
        return $this->jsonResponse('ok');
    }
}
