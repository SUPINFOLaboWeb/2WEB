<?php

require_once(__DIR__.'/classes/manager/SimplePostManager.class.php');

session_start();

if (!isset($_SESSION['user'])) {
    header('Location: ./login.php');
}

$pm = new SimplePostManager();

// si le champs submit est présent, alors le formulaire a été envoyé
if (isset($_POST['submit'])) {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $body = isset($_POST['body']) ? $_POST['body'] : '';

    $pm->addPost($title, $body, $_SESSION['user']);
    header('Location: ./postList.php');
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Add Post</title>
    </head>
    <body>
        <h1>Add Post Page</h1>
        <form action="#" method="post">
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="" />
            <br />
            <label for="body">Body:</label>
            <textarea id="body" name="body"></textarea>
            <br />
            <br />
            <input type="submit" name="submit" value="Ajouter" />
        </form>
    </body>
</html>