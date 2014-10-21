<?php

namespace Backend;

class AuthController extends BaseController
{
    /**
     * The constructor.
     */
    public function __construct()
    {
        parent::__construct();

        // $this->beforeFilter('@processAuthentication');
    }

    /**
     * Test user credentials
     *
     * @route GET /backend/auth/test-credentials
     * @authentication false
     */
    public function testCredentials()
    {
        return $this->jsonResponse('ok');
    }
}
