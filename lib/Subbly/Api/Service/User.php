<?php

namespace Subbly\Api;

class User extends Service
{
    /**
     * Create a new User
     *
     * @example
     *     $user = Subbly\Core\Model\User;
     *     Api::get('subbly.user')->create($user);
     *
     *     Api::get('subbly.user')->create(array(
     *         'firstname' => 'John',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param User|array $user
     *
     * @return User
     */
    public function create($user)
    {
        if ($user instanceof User) {
            $this->save($user)
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
     *     Api::get('subbly.user')->update($user);
     *
     *     Api::get('subbly.user')->update($user_id, array(
     *         'firstname' => 'John',
     *         'lastname'  => 'Snow',
     *     ));
     *
     * @param User|integer
     * @param array|null
     *
     * @return User
     */
    public function update()
    {
        $args = func_get_args();

        if (count($args) == 1 && $args[0] instanceof User) {
            $this->save($user);
        }
        // else if 2 args. $id, $array
    }
}
