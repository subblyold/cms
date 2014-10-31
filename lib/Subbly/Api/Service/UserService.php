<?php

namespace Subbly\Api\Service;

use Sentry;

use Subbly\Model\Collection;
use Subbly\Model\User;

class UserService extends Service
{
    protected $modelClass = 'Subbly\\Model\\User';

    protected $includableRelationships = array('addresses', 'orders');

    /**
     * Return an empty model
     *
     * @return Subbly\Model\User
     *
     * @api
     */
    public function newUser()
    {
        return new User();
    }

    /**
     * Attempts to authenticate the given user
     * according to the passed credentials.
     *
     * @param  array  $credentials
     * @param  bool   $remember
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     *
     * @throws \Cartalyst\Sentry\Throttling\UserBannedException
     * @throws \Cartalyst\Sentry\Throttling\UserSuspendedException
     * @throws \Cartalyst\Sentry\Users\LoginRequiredException
     * @throws \Cartalyst\Sentry\Users\PasswordRequiredException
     * @throws \Cartalyst\Sentry\Users\UserNotFoundException
     *
     * @api
     */
    public function authenticate(array $credentials, $remember = false)
    {
        User::setCallerForNext($this);
        $user = Sentry::authenticate($credentials, $remember);
        User::removeCaller();

        return $user;
    }

    /**
     * Check to see if the user is logged in and activated,
     * and hasn't been banned or suspended.
     *
     * @return bool
     *
     * @api
     */
    public function check()
    {
        return Sentry::check();
    }

    /**
     * Returns the current user being used by Sentry, if any.
     *
     * @return \Cartalyst\Sentry\Users\UserInterface
     *
     * @api
     */
    public function currentUser()
    {
        return Sentry::getUser();
    }

    /**
     * Get all User
     *
     * @param array $options
     *
     * @return \Subbly\Model\Collection
     *
     * @api
     */
    public function all(array $options = array())
    {
        $query = $this->newCollectionQuery($options);

        return new Collection($query);
    }

    /**
     * Find a User by $id
     *
     * @example
     *     $user = Subbly::api('subbly.user')->find(1);
     *
     * @param integer $id
     *
     * @return User
     *
     * @api
     */
    public function find($uid, array $options = array())
    {
        $options = array_replace(array(
            'includes' => array('addresses', 'orders'),
        ), $options);

        $query = $this->newQuery($options);
        $query->where('uid', '=', $uid);

        return $query->firstOrFail();
    }

    /**
     * Search a User by options
     *
     * @example
     *     $users = Subbly::api('subbly.user')->searchBy(array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *     // OR
     *     $users = Subbly::api('subbly.user')->searchBy('Jon');
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
     * Create a new User
     *
     * @example
     *     $user = Subbly\Model\User;
     *     Subbly::api('subbly.user')->create($user);
     *
     *     Subbly::api('subbly.user')->create(array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param User|array $user
     *
     * @return User
     *
     * @api
     */
    public function create($user)
    {
        if (is_array($user)) {
            $user = new User($user);
        }

        if ($this->fireEvent('creating', array($user)) === false) return false;

        if ($user instanceof User) {
            $user->setCaller($this);
            $user->save();
        }
        else {
            throw new Exception(sprintf(Exception::CANT_CREATE_MODEL,
                'Subbly\\Model\\User',
                $this->name()
            ));
        }

        $event = $this->fireEvent('created', array($user));

        return $user;
    }

    /**
     * Update a User
     *
     * @example
     *     $user = [Subbly\Model\User instance];
     *     Subbly::api('subbly.user')->update($user);
     *
     *     Subbly::api('subbly.user')->update($user_uid, array(
     *         'firstname' => 'Jon',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param User|string
     * @param array|null
     *
     * @return User
     *
     * @api
     */
    public function update()
    {
        $args = func_get_args();
        $user = null;

        if (count($args) == 1 && $args[0] instanceof User) {
            $user = $args[0];
        }
        else if (count($args) == 2 && !empty($args[0]) && is_array($args[1]))
        {
            $user = $this->find($args[0]);
            $user->fill($args[1]);
        }

        if ($this->fireEvent('updating', array($user)) === false) return false;

        if ($user instanceof User)
        {
            $user->setCaller($this);
            $user->save();
        }
        else {
            throw new Exception(sprintf(Exception::CANT_UPDATE_MODEL,
                'Subbly\\Model\\User',
                $this->name()
            ));
        }

        $event = $this->fireEvent('updated', array($user));

        return $user;
    }

    /**
     * Delete a User
     *
     * @param User|string  $user The user_uid or the user model
     *
     * @return User
     *
     * @pi
     */
    public function delete($user)
    {
        if (!is_object($user)) {
            $user = $this->find($user);
        }

        if ($this->fireEvent('deleting', array($user)) === false) return false;

        if ($user instanceof User) {
            $user->delete($this);
        }

        $event = $this->fireEvent('deleted', array($user));
    }

    /**
     * Service name
     */
    public function name()
    {
        return 'subbly.user';
    }
}
