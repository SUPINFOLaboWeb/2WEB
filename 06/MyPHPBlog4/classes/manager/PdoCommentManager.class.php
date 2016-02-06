<?php

require_once(__DIR__.'/AbstractPdoManager.class.php');
require_once(__DIR__.'/CommentManager.class.php');
require_once(__DIR__.'/PdoUserManager.class.php');
require_once(__DIR__.'/PdoCommentManager.class.php');
require_once(__DIR__.'/../entities/Post.class.php');
require_once(__DIR__.'/../entities/User.class.php');
require_once(__DIR__.'/../entities/Comment.class.php');

class PdoCommentManager extends AbstractPdoManager implements CommentManager
{
    public function addComment($body, $postId, $user)
    {
        $stmt = $this->dtb->prepare('INSERT INTO comments VALUES(NULL, :body, :postId, :uid, :time)');
        $stmt->bindValue(':body', $body, PDO::PARAM_STR);
        $stmt->bindValue(':postId', $postId, PDO::PARAM_INT);
        $stmt->bindValue(':uid', $user->id, PDO::PARAM_INT);
        $stmt->bindValue(':time', time(), PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function findAllComments()
    {
        $stmt = $this->dtb->prepare('SELECT * FROM comments');
        $stmt->execute();

        $data = array();
        $um = new PdoUserManager();
        $pm = new PdoPostManager();

        while ($d = $stmt->fetch(PDO::FETCH_OBJ)) {
            $c = new Comment();
            $c->setId($d->id);
            $c->setBody($d->body);
            $c->setPost($pm->findPostById($d->postID));
            $c->setUser($um->findUserById($d->userID));
            $c->setPublicationDate($d->publicationDate);

            $data[] = $c;
        }

        $stmt->closeCursor();

        return $data;
    }

    public function findAllCommentsForPostId($pid)
    {
        $stmt = $this->dtb->prepare('SELECT * FROM comments WHERE postID = :pid');
        $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
        $stmt->execute();

        $data = array();
        $um = new PdoUserManager();
        $pm = new PdoPostManager();

        while ($d = $stmt->fetch(PDO::FETCH_OBJ)) {
            $c = new Comment();
            $c->setId($d->id);
            $c->setBody($d->body);
            $c->setPost($pm->findPostById($d->postID));
            $c->setUser($um->findUserById($d->userID));
            $c->setPublicationDate($d->publicationDate);

            $data[] = $c;
        }

        $stmt->closeCursor();

        return $data;
    }

    public function findCommentById($id)
    {
        $stmt = $this->dtb->prepare('SELECT * FROM comments WHERE postID = :pid');
        $stmt->bindValue(':pid', $pid, PDO::PARAM_INT);
        $stmt->execute();

        $data = array();
        $um = new PdoUserManager();
        $pm = new PdoPostManager();

        $d = $stmt->fetch(PDO::FETCH_OBJ);

        $c = new Comment();
        $c->setId($d->id);
        $c->setBody($d->body);
        //$c->setPost($pm->findPostById($d->postID));
        $c->setUser($um->findUserById($d->userID));
        $c->setPublicationDate($d->publicationDate);

        $data[] = $c;

        $stmt->closeCursor();

        return $data;
    }

    public function removeComment($id)
    {
        $this->dtb->prepare('DELETE FROM comments WHERE id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}