<?php

require_once(__DIR__.'/UserManager.class.php');
require_once(__DIR__.'/../entities/User.class.php');

class SimpleUserManager implements UserManager
{
    public function authenticate($email, $password)
    {
        var_dump($email, $password);

        if ($email === 'test@test.com' && $password === '1234') {
            return new User(0, 'BURGEAT', 'Gauthier', $email, $password);
        }

        return false;
    }
}