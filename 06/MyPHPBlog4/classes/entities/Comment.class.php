<?php

class Comment
{
    private $id;
    private $body;
    private $post;
    private $user;
    private $publicationDate;

    // http://php.net/manual/fr/language.oop5.overloading.php#object.call
    public function __call($name, $args)
    {
        $varname = lcfirst(substr($name, 3));

        if (strncasecmp($name, "get", 3) === 0) {
            return $this->$varname;
        } else if (strncasecmp($name, "set", 3) === 0) {
            return $this->$varname = $args[0];
        }
    }
}