<?php

namespace Api;

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
     * @route GET /api/users
     * @authentication required
     */
    public function index()
    {
        $users = Subbly::api('subbly.user')->all(array(
            'offset' => $this->offset(),
            'limit'  => $this->limit(),
        ));

        return $this->jsonCollectionResponse('users', $users);
    }

    /**
     * Search one or many User
     *
     * @route GET /api/users/search/?q=
     * @authentication required
     */
    public function search()
    {
        $users = Subbly::api('subbly.user')->search(array(
            'global' => Input::get('q'),
            'offset' => $this->offset(),
            'limit'  => $this->limit(),
        ));

        return $this->jsonCollectionResponse('users', $users);
    }

    /**
     * Get User datas
     *
     * @route GET /api/users/{{uid}}
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
     * @route POST /api/users/
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
        ),
        array(
            'status' => array(
                'code'    => 201,
                'message' => 'User created',
            ),
        ));
    }

    /**
     * Update a User
     *
     * @route POST /api/users/
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
        ),
        array(
            'status' => array(
                'code'    => 200,
                'message' => 'User updated',
            ),
        ));
    }
}
