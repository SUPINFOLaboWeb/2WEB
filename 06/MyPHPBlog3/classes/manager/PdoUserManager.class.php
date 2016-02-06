<?php

require_once(__DIR__.'/../config/database.php');
require_once(__DIR__.'/UserManager.class.php');
require_once(__DIR__.'/../entities/User.class.php');

class PdoUserManager implements UserManager
{
    static public $dtb = null;

    public function __construct()
    {
        if (is_null(self::$dtb)) {
            self::$dtb = new PDO(DB_DSN, DB_USER, DB_PASSWD);
        }
    }

    public function authenticate($email, $password)
    {
        $stmt = self::$dtb->query('SELECT * FROM users WHERE email = "'.htmlspecialchars($email).'" AND password = "'.htmlspecialchars($password).'"');
        $data = $stmt->fetch();
        $stmt->closeCursor();

        if ($data !== false) {
            return new User($data['id'], $data['lastname'], $data['firstname'], $email, $password);
        }

        return false;
    }

    public function findUserById($id)
    {
        $stmt = self::$dtb->query('SELECT * FROM users WHERE id = '.intval($id));
        $data = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User', array('id', 'lastname', 'firstname', 'email', 'password'));
        $stmt->closeCursor();

        return $data[0];
    }
}