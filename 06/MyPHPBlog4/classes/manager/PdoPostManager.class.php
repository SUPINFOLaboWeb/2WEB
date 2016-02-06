<?php

require_once(__DIR__.'/AbstractPdoManager.class.php');
require_once(__DIR__.'/PostManager.class.php');
require_once(__DIR__.'/PdoUserManager.class.php');
require_once(__DIR__.'/PdoCommentManager.class.php');
require_once(__DIR__.'/../entities/Post.class.php');
require_once(__DIR__.'/../entities/User.class.php');
require_once(__DIR__.'/../entities/Comment.class.php');

class PdoPostManager extends AbstractPdoManager implements PostManager
{
    public function addPost($title, $body, $user)
    {
        $stmt = $this->dtb->prepare('INSERT INTO posts VALUES(NULL, :title, :body, :time, :uid)');
        $stmt->bindValue(':title', $title, PDO::PARAM_STR);
        $stmt->bindValue(':body', $body, PDO::PARAM_STR);
        $stmt->bindValue(':time', time(), PDO::PARAM_INT);
        $stmt->bindValue(':uid', $user->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function findAllPosts()
    {
        $stmt = $this->dtb->prepare('SELECT * FROM posts');
        $stmt->execute();

        $data = array();
        $um = new PdoUserManager();
        $cm = new PdoCommentManager();

        while ($d = $stmt->fetch(PDO::FETCH_OBJ)) {
            $data[] = new Post($d->id, $d->title, $d->body, $d->publicationDate, $um->findUserById($d->userID), $cm->findAllCommentsForPostId($d->id));
        }

        $stmt->closeCursor();

        return $data;
    }

    public function findPostById($id)
    {
        $stmt = $this->dtb->prepare('SELECT * FROM posts WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->execute();

        $um = new PdoUserManager();
        $cm = new PdoCommentManager();

        $d = $stmt->fetch(PDO::FETCH_OBJ);
        $data =  new Post($d->id, $d->title, $d->body, $d->publicationDate, $um->findUserById($d->userID), $cm->findAllCommentsForPostId($d->id));

        $stmt->closeCursor();

        return $data;
    }

    public function removePost($id)
    {
        $this->dtb->prepare('DELETE FROM posts WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}