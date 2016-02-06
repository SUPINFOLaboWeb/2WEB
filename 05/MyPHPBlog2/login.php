<?php

require(__DIR__.'/classes/manager/SimpleUserManager.class.php');

session_start();

if (isset($_POST['login']) && isset($_POST['password'])) {
    $um = new SimpleUserManager();
    $user = $um->authenticate($_POST['login'], $_POST['password']);

    if ($user !== false) {
        $_SESSION['user'] = $user;
        header('Location: ./postList.php');
    }
}

?>

<!DOCTYPE html>
<html>
    <head>
        <title>Login</title>
    </head>
    <body>
        <h1>Login Page</h1>
        <form action="#" method="post">
            <label for="login">Login:</label>
            <input type="text" id="login" name="login" value="" />
            <br />
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" value="" />
            <br />
            <br />
            <input type="submit" value="Se connecter" />
        </form>
    </body>
</html>