<?php

namespace Subbly\Api\Service;

use Subbly\Model;

class UserService extends Service
{
    /**
     * Return an empty model
     *
     * @return Subbly\Model\User
     *
     * @api
     */
    public function init()
    {
        return new Model\User();
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
        return Model\User::all();
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
    public function find($id)
    {
        return Model\User::find($id);
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

        $query = Model\User::query();

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
     *     $user = Subbly\Core\Model\User;
     *     Subbly::api('subbly.user')->create($user);
     *
     *     Subbly::api('subbly.user')->create(array(
     *         'firstname' => 'John',
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
        if ($user instanceof User) {
            // TODO use Sentry instead
            $this->save($user);
        }
        else if (is_array($user)) {
            // TODO
        }

        return $user;
    }

    /**
     * Update a User
     *
     * @example
     *     $user = [Subbly\Core\Model\User instance];
     *     Subbly::api('subbly.user')->update($user);
     *
     *     Subbly::api('subbly.user')->update($user_id, array(
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

        if (count($args) == 1 && $args[0] instanceof User) {
            $this->save($user);
        }
        // else if 2 args. $id, $array
    }

    /**
     *
     */
    public function name()
    {
        return 'subbly.user';
    }
}
