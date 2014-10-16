<?php

namespace Subbly\Api;

class User extends Service
{
    public function create($user)
    {
        if ($user instanceof User) {
            $this->save($user)
        }
        else if (is_array($user)) {
            // TODO
        }
    }

    public function update()
    {
        $args = func_get_args();

        if (count($args) == 1 && $args[0] instanceof User) {
            $this->save($user);
        }
        // else if 2 args. $id, $array
    }
}
