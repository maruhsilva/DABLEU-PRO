<?php

    if(!isset($_SESSION)) {
        session_start();
    }

    session_destroy();
    header("Location: /dableu-pro/user-login.php");

?>