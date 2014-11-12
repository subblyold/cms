<?php

namespace Subbly\Api\Service;

use Sentry;

use Subbly\Model\Collection;
use Subbly\Model\UserAddress;
use Subbly\Model\User;

class UserAddressService extends Service
{
    protected $modelClass = 'Subbly\\Model\\UserAddress';

    protected $includableRelationships = array('user');

    /**
     * Return an empty model
     *
     * @return \Subbly\Model\UserAddress
     *
     * @api
     */
    public function newUserAddress()
    {
        return new UserAddress();
    }

    /**
     * Find a UserAddress by $uid
     *
     * @example
     *     $userAddress = Subbly::api('subbly.user_address')->find($uid);
     *
     * @param string  $uid
     * @param array   $options
     *
     * @return \Subbly\Model\UserAddress
     *
     * @api
     */
    public function find($uid, array $options = array())
    {
        $query = $this->newQuery($options);
        $query->where('uid', '=', $uid);

        return $query->firstOrFail();
    }

    /**
     * Find a UserAddress by User
     *
     * @example
     *     $userAddress = Subbly::api('subbly.user_address')->findByUser($user);
     *
     * @param string|\Subbly\Model\User  $user    The User model or the User uid
     * @param array                      $options Some options
     *
     * @return \Subbly\Model\Collection
     *
     * @api
     */
    public function findByUser($user, array $options = array())
    {
        if (!$user instanceof User) {
            $user = $this->api('subbly.user')->find($user);
        }

        $query = $this->newCollectionQuery($options);
        $query->with(array('user' => function($query) use ($user) {
            $query->where('uid', '=', $user->uid);
        }));

        return new Collection($query);
    }

    /**
     * Create a new UserAddress
     *
     * @example
     *     $user = Subbly\Model\UserAddress;
     *     Subbly::api('subbly.user_address')->create($userAddress);
     *
     *     Subbly::api('subbly.user_address')->create(array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param \Subbly\Model\UserAddress|array  $userAddress
     * @param \Subbly\Model\User|null          $user
     *
     * @return \Subbly\Model\UserAddress
     *
     * @throws \Subbly\Api\Service\Exception
     *
     * @api
     */
    public function create($userAddress, $user)
    {
        if (!$user instanceof User) {
            $user = $this->api('subbly.user')->find($user);
        }

        if (is_array($userAddress)) {
            $userAddress = new UserAddress($userAddress);
        }

        if ($userAddress instanceof UserAddress)
        {
            if ($this->fireEvent('creating', array($userAddress)) === false) return false;

            $userAddress->user()->associate($user);

            $userAddress->setCaller($this);
            $userAddress->save();

            $this->fireEvent('created', array($userAddress));

            $userAddress = $this->find($userAddress->uid);

            return $userAddress;
        }

        throw new Exception(sprintf(Exception::CANT_CREATE_MODEL,
            $this->modelClass,
            $this->name()
        ));
    }

    /**
     * Update a UserAddress
     *
     * @example
     *     $userAddress = [Subbly\Model\UserAddress instance];
     *     Subbly::api('subbly.user_address')->update($userAddress);
     *
     *     Subbly::api('subbly.user_address')->update($user_uid, array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @return \Subbly\Model\UserAddress
     *
     * @api
     */
    public function update()
    {
        $args        = func_get_args();
        $userAddress = null;

        if (count($args) == 1 && $args[0] instanceof UserAddress) {
            $userAddress = $args[0];
        }
        else if (count($args) == 2 && !empty($args[0]) && is_array($args[1]))
        {
            $userAddress = $this->find($args[0]);
            $userAddress->fill($args[1]);
        }

        if ($userAddress instanceof UserAddress)
        {
            if ($this->fireEvent('updating', array($userAddress)) === false) return false;

            $userAddress->setCaller($this);
            $userAddress->save();

            $this->fireEvent('updated', array($userAddress));

            return $userAddress;
        }

        throw new Exception(sprintf(Exception::CANT_UPDATE_MODEL,
            'Subbly\\Model\\UserAddress',
            $this->name()
        ));
    }

    /**
     * Delete a UserAddress
     *
     * @param \Subbly\Model\UserAddress|string  $userAddress The userAddress_uid or the UserAddress model
     *
     * @return \Subbly\Model\UserAddress
     *
     * @pi
     */
    public function delete($userAddress)
    {
        if (!is_object($userAddress)) {
            $userAddress = $this->find($userAddress);
        }

        if ($userAddress instanceof UserAddress)
        {
            if ($this->fireEvent('deleting', array($userAddress)) === false) return false;

            $userAddress->delete($this);

            $this->fireEvent('deleted', array($userAddress));
        }
    }

    /**
     * Service name
     */
    public function name()
    {
        return 'subbly.user_address';
    }
}
