<?php

require_once(__DIR__.'/../config/database.php');

class AbstractPdoManager
{
    protected static $dtblist = array();
    protected $dtb;

    public function __construct($dsn = null, $username = null, $password = null, $prefix='')
    {
        $dsn = is_null($dsn) ? DB_DSN : $dsn;
        $username = is_null($username) ? DB_USER : $username;
        $password = is_null($password) ? DB_PASSWD : $password;
        $hash = $prefix.md5($dsn.$username.$password);

        if (!isset(self::$dtblist[$hash])) {
            // http://php.net/manual/fr/pdo.setattribute.php
            $options = array(
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                PDO::ATTR_EMULATE_PREPARES => false
            );

            self::$dtblist[$hash] = new PDO($dsn, $username, $password, $options);
        }

        $this->dtb = self::$dtblist[$hash];
    }
}