<?php

namespace Subbly\CMS\Controllers\Api;

use Illuminate\Support\Facades\Input;

use Subbly\Subbly;

class UserAddressesController extends BaseController
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
     * Get list of UserAddress for a User
     *
     * @route GET /api/v1/user-addresses/:user_uid
     * @authentication required
     */
    public function index($user_uid)
    {
        list($offset, $limit) = $this->apiOffsetLimit();
        $options = $this->formatOptions(array(
            'offset'   => $offset,
            'limit'    => $limit,
        ));

        $userAddresses = Subbly::api('subbly.user_address')->findByUser($user_uid);

        return $this->jsonCollectionResponse('user_addresses', $userAddresses);
    }
}
