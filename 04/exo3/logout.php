<?php
    session_start();
    session_destroy();
    setcookie('rememberMe', '', 1);
    header('Location: login.php');
