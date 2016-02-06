<?php

require_once(__DIR__.'/PostManager.class.php');
require_once(__DIR__.'/../entities/Post.class.php');

class SimplePostManager implements PostManager
{
    public function __construct()
    {
        if (!isset($_SESSION['posts']) || !isset($_SESSION['counter'])) {
            $_SESSION['posts'] = array();
            $_SESSION['counter'] = 0;
        }
    }

    public function addPost($title, $body, $user)
    {
        $i = $_SESSION['counter']++;
        $post = new Post($i, $title, $body, time(), $user);
        $_SESSION['posts'][$i] = $post;
    }

    public function findAllPosts()
    {
        return $_SESSION['posts'];
    }

    public function findPostById($id)
    {
        return $_SESSION['posts'][$i];
    }

    public function removePost($id)
    {
        unset($_SESSION['posts'][$i]);
    }
}