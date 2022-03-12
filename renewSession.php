<?php
    session_start();
    if (isset($_COOKIE['auth']) && $_COOKIE['auth'] == 1){
        $AccountId = $_COOKIE['accountId'];
        $accountType = $_COOKIE['accountType'];
        setcookie("auth", true, time() + (60 * 5));
        setcookie("accountId", $AccountId, time() + (60 * 5));
        setcookie("accountType", $accountType, time() + (60 * 5));
    } else {
        header("location: login.php");
    }
?>