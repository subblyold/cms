<?php

namespace Backend;

use Input;

use Subbly\Subbly;

class UsersController extends BaseController
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
     * Get User list
     *
     * @route GET /backend/users
     * @authentication required
     */
    public function index()
    {
        return $this->jsonResponse(array(
            'users' => Subbly::api('subbly.user')->all(),
        ));
    }

    /**
     * Get User datas
     *
     * @route GET /backend/users/:uid
     * @authentication required
     */
    public function show($uid)
    {
        return $this->jsonResponse(array(
            'user' => Subbly::api('subbly.user')->find($uid, array(
                'with_addresses' => true,
                'with_orders'    => true,
            )),
        ));
    }

    /**
     * Create a new User
     *
     * @route POST /backend/users/
     * @authentication required
     */
    public function store()
    {
        if (!Input::has('user')) {
            return $this->jsonErrorResponse('"user" is required.');
        }

        $user = Subbly::api('subbly.user')->create(Input::get('user'));

        return $this->jsonResponse(array(
            'user' => $user,
        ));
    }

    /**
     * Update a User
     *
     * @route POST /backend/users/
     * @authentication required
     */
    public function update()
    {
        if (!Input::has('user_uid')) {
            return $this->jsonErrorResponse('"user_uid" is required.');
        }
        if (!Input::has('user')) {
            return $this->jsonErrorResponse('"user" is required.');
        }

        $user = Subbly::api('subbly.user')->update(Input::get('user_uid'), Input::get('user'));

        return $this->jsonResponse(array(
            'user' => $user,
        ));
    }
}
