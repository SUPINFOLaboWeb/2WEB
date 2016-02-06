<?php

require_once(__DIR__.'/../config/database.php');
require_once(__DIR__.'/PostManager.class.php');
require_once(__DIR__.'/PdoUserManager.class.php');
require_once(__DIR__.'/../entities/Post.class.php');

class PdoPostManager implements PostManager
{
    static public $dtb = null;

    public function __construct()
    {
        if (is_null(self::$dtb)) {
            self::$dtb = new PDO(DB_DSN, DB_USER, DB_PASSWD);
        }
    }

    public function addPost($title, $body, $user)
    {
        self::$dtb->exec('INSERT INTO posts VALUE(NULL, "'.htmlspecialchars($title).'", "'.htmlspecialchars($body).'", '.time().', "'.$user->id.'")');
    }

    public function findAllPosts()
    {
        $stmt = self::$dtb->query('SELECT * FROM posts');

        $buffer = array();
        $um = new PdoUserManager();

        while ($d = $stmt->fetch(PDO::FETCH_OBJ)) {
            $buffer[] = new Post($d->id, $d->title, $d->body, $d->publicationDate, $um->findUserById($d->userID));
        }

        $stmt->closeCursor();

        return $buffer;
    }

    public function findPostById($id)
    {
        $um = new PdoUserManager();

        $stmt = self::$dtb->query('SELECT * FROM posts WHERE id = '.intval($id));
        $data = $stmt->fetch(PDO::FETCH_OBJ);
        $data = new Post($data->id, $data->title, $data->body, $data->publicationDate, $um->findUserById($data->userID));

        $stmt->closeCursor();

        return $data;
    }

    public function removePost($id)
    {
        self::$dtb->exec('DELETE FROM posts WHERE id = '.intval($id));
    }
}