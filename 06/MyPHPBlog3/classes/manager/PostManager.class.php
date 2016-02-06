<?php

interface PostManager
{
    public function addPost($title, $body, $user);
    public function findAllPosts();
    public function findPostById($id);
    public function removePost($id);
}