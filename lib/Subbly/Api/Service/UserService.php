<?php

namespace Subbly\Api\Service;

use Sentry;

use Subbly\Model\Collection;
use Subbly\Model\User;

class UserService extends Service
{
    protected $modelClass = 'Subbly\\Model\\User';

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
        return Sentry::authenticate($credentials, $remember);
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
        $query = $this->newQuery($options);

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
            'with_addresses' => false,
            'with_orders'    => false,
        ), $options);

        $query = User::query();
        $query->where('uid', '=', $uid);

        if ($options['with_addresses'] === true) {
            $query->with('addresses');
        }

        if ($options['with_orders'] === true) {
            $query->with('orders');
        }

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
     *
     * @param array $options
     *
     * @return \Subbly\Model\Collection
     *
     * @api
     */
    public function searchBy(array $options)
    {
        $options = array_replace(array(
            'global'    => null,
            'firstname' => null,
            'lastname'  => null,
            'email'     => null,
        ));

        $query = User::query();

        if ($options['firstname']) {
            $query->where('firstname', 'LIKE', "%{$options['firstname']}%");
        }
        if ($options['lastname']) {
            $query->where('lastname', 'LIKE', "%{$options['lastname']}%");
        }
        if ($options['email']) {
            $query->where('email', 'LIKE', "%{$options['email']}%");
        }

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
