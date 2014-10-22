<?php

namespace Subbly\Api\Service;

use Sentry;

use Subbly\Model\User;

class UserService extends Service
{
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
     * @return Illuminate\Database\Eloquent\Collection
     *
     * @api
     */
    public function all()
    {
        return User::all();
    }

    /**
     * Find a User by $id
     *
     * @example
     *     Subbly::api('subbly.user')->find(1);
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
     *     Subbly::api('subbly.user')->searchBy(array(
     *         'firstname' => 'John',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param integer $id
     *
     * @return User
     *
     * @api
     */
    public function searchBy(array $options)
    {
        $options = array_replace(array(
            'global'    => null,
            'firstname' => null,
            'lastname'  => null,
        ));

        $query = User::query();

        if ($options['firstname']) {
            $query->where('first_name', 'LIKE', "%{$options['firstname']}%");
        }
        if ($options['lastname']) {
            $query->where('last_name', 'LIKE', "%{$options['lastname']}%");
        }

        return $query->get();
    }

    /**
     * Create a new User
     *
     * @example
     *     $user = Subbly\Model\User;
     *     Subbly::api('subbly.user')->create($user);
     *
     *     Subbly::api('subbly.user')->create(array(
     *         'first_name' => 'John',
     *         'last_name'  => 'Snow',
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
        $user = null;

        if (is_array($user)) {
            $user = new User($user);
        }

        $event = $this->fireEvent('creating', array($user));

        if ($user instanceof User) {
            $this->saveModel($user);

            // TODO use Sentry also or instead
            // Sentry::register(array(
            //     'email'    => $user->email,
            //     'password' => $user->password,
            // ));
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
     *         'firstname' => 'John',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param User|integer
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

        $event = $this->fireEvent('updating', array($user));

        if ($user instanceof User)
        {
            $this->saveModel($user);
        }
        else {
            throw new Exception(sprintf(Exception::CANT_UPDATE_MODEL,
                'Subbly\\Model\\User',
                $this->name()
            ));
        }

        $event = $this->fireEvent('updated', array($user));
    }

    /**
     *
     */
    public function delete($user)
    {
        if (is_integer($user)) {
            $user = User::find($user);
        }

        $event = $this->fireEvent('user_deleting', array($user));

        if ($user instanceof User)
        {
            // TODO use soft delete for user
            $this->deleteModel($user);
        }

        $event = $this->fireEvent('user_deleted', array($user));
    }

    /**
     *
     */
    public function name()
    {
        return 'subbly.user';
    }
}
