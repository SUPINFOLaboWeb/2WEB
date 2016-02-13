<?php
    session_start();
    if (isset($_COOKIE['rememberMe'])) {
        $_SESSION['username'] = $_COOKIE['rememberMe'];
        header('Location: translator.php');
    }
    if (isset($_POST['username']) && isset($_POST['password'])) {
        if ($_POST['username'] === 'Plop' && $_POST['password'] === '1234') {
            $_SESSION['username'] = $_POST['username'];
            if (isset($_POST['rememberMe'])) {
                setcookie('rememberMe', $_POST['username'], time() + 3600 * 24 * 365);
            }
            header('Location: translator.php');
        } else {
            $error = 'Bad credentials';
        }
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Connection</title>
    </head>

    <body>
        <p>
            <?php if (isset($error)) { echo '<span style=\'color:red;\'>'. $error .'</span>'; } ?>
            <form action="" method="POST">
                <label for="username">Username :</label>
                <input type="text" id="username" name="username" />
                <label for="password">Password :</label>
                <input type="password" id="password" name="password" />
                <label for="rememberMe">Remember Me</label>
                <input type="checkbox" id="rememberMe" name="rememberMe" />
                <input type="submit" id="submit" name="submit" value="Envoyer" />
            </form>
        </p>
    </body>
</html>
