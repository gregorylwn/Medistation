<?php
    session_start();
    setcookie("auth", null, -1);
    setcookie("accountId", null, -1);
    setcookie("accountType", null, -1);
    $_SESSION = array();
    session_destroy();
    header("Location: login.php");
    exit;
?>