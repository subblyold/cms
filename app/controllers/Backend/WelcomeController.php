<?php

namespace Backend;

class WelcomeController extends BaseController
{
    /**
     * Return stats for the dashboard
     *
     * @route GET /backend/welcome
     * @authentication optional
     */
    public function index()
    {
        return $this->jsonResponse('ok');
    }
}
