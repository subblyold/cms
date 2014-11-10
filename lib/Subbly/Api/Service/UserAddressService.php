<?php

namespace Subbly\Api\Service;

use Sentry;

use Subbly\Model\Collection;
use Subbly\Model\UserAddress;

class UserAddressService extends Service
{
    protected $modelClass = 'Subbly\\Model\\UserAddress';

    /**
     * Return an empty model
     *
     * @return Subbly\Model\UserAddress
     *
     * @api
     */
    public function newUserAddress()
    {
        return new UserAddress();
    }

    /**
     * Find a UserAddress by User
     *
     * @example
     *     $user = Subbly::api('subbly.user_address_address')->findByUser($user);
     *
     * @param string|\Subbly\Model\User  $user    The User model or the User uid
     * @param array                      $options Some options
     *
     * @return UserAddress
     *
     * @api
     */
    public function findByUser($user, array $options = array())
    {
        $options = array_replace(array(), $options);

        if ($user instanceof User) {
            $user_uid = $user->uid;
        }
        else if (is_string($user)) {
            $user_uid = $user;
        }
        else {
            // TODO throw an exception
        }

        $query = $this->newCollectionQuery($options);
        $query
            ->with('user')
            ->where('users.uid', '=', $user_uid)
        ;
        echo($query->toSql()); exit;

        return new Collection($query);
    }

    /**
     * Search a UserAddress by options
     *
     * @example
     *     $users = Subbly::api('subbly.user_address')->searchBy(array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *     // OR
     *     $users = Subbly::api('subbly.user_address')->searchBy('some words');
     *
     * @param array|string  $searchQuery    Search params
     * @param array         $options        Query options
     * @param string        $statementsType Type of statement null|or|and (default is null)
     *
     * @return \Subbly\Model\Collection
     *
     * @api
     */
    public function searchBy($searchQuery, array $options = array(), $statementsType = null)
    {
        $query = $this->newSearchQuery($searchQuery, array(
            'first_name',
            'last_name',
            'email',
        ), $statementsType, $options);

        return new Collection($query);
    }

    /**
     * Create a new UserAddress
     *
     * @example
     *     $user = Subbly\Model\UserAddress;
     *     Subbly::api('subbly.user_address')->create($user);
     *
     *     Subbly::api('subbly.user_address')->create(array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param UserAddress|array $user
     *
     * @return UserAddress
     *
     * @throws \Subbly\Api\Service\Exception
     *
     * @api
     */
    public function create($user)
    {
        if (is_array($user)) {
            $user = new UserAddress($user);
        }

        if ($user instanceof UserAddress)
        {
            if ($this->fireEvent('creating', array($user)) === false) return false;

            $user->setCaller($this);
            $user->save();

            $this->fireEvent('created', array($user));

            $user = $this->find($user->uid);

            return $user;
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
     *     $user = [Subbly\Model\UserAddress instance];
     *     Subbly::api('subbly.user_address')->update($user);
     *
     *     Subbly::api('subbly.user_address')->update($user_uid, array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @return UserAddress
     *
     * @api
     */
    public function update()
    {
        $args = func_get_args();
        $user = null;

        if (count($args) == 1 && $args[0] instanceof UserAddress) {
            $user = $args[0];
        }
        else if (count($args) == 2 && !empty($args[0]) && is_array($args[1]))
        {
            $user = $this->find($args[0]);
            $user->fill($args[1]);
        }

        if ($user instanceof UserAddress)
        {
            if ($this->fireEvent('updating', array($user)) === false) return false;

            $user->setCaller($this);
            $user->save();

            $this->fireEvent('updated', array($user));

            return $user;
        }

        throw new Exception(sprintf(Exception::CANT_UPDATE_MODEL,
            'Subbly\\Model\\UserAddress',
            $this->name()
        ));
    }

    /**
     * Delete a UserAddress
     *
     * @param UserAddress|string  $user The user_uid or the user model
     *
     * @return UserAddress
     *
     * @pi
     */
    public function delete($user)
    {
        if (!is_object($user)) {
            $user = $this->find($user);
        }

        if ($user instanceof UserAddress)
        {
            if ($this->fireEvent('deleting', array($user)) === false) return false;

            $user->delete($this);

            $this->fireEvent('deleted', array($user));
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
