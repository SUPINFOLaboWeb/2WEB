<?php

interface CommentManager
{
    public function addComment($body, $postId, $user);
    public function findAllComments();
    public function findAllCommentsForPostId($id);
    public function findCommentById($id);
    public function removeComment($id);
}