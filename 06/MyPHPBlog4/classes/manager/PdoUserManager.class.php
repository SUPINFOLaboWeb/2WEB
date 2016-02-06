<?php

require_once(__DIR__.'/AbstractPdoManager.class.php');
require_once(__DIR__.'/UserManager.class.php');
require_once(__DIR__.'/../entities/User.class.php');

class PdoUserManager extends AbstractPdoManager implements UserManager
{
    public function authenticate($email, $password)
    {
        $stmt = $this->dtb->prepare('SELECT * FROM users WHERE email = :email AND password = :password');
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->bindValue(':password', $password, PDO::PARAM_STR);

        $stmt->execute();

        $data = $stmt->fetch();
        $stmt->closeCursor();

        if ($data !== false) {
            return new User($data['id'], $data['lastname'], $data['firstname'], $email, $password);
        }

        return false;
    }

    public function findUserById($id)
    {
        $stmt = $this->dtb->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE, 'User', array('id', 'lastname', 'firstname', 'email', 'password'));
        $stmt->closeCursor();

        return $data[0];
    }
}