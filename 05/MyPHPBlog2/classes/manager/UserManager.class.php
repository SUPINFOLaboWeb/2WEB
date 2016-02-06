<?php

interface UserManager
{
    public function authenticate($email, $password);
}