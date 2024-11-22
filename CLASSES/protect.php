<?php

if(!isset($_SESSION)) {
    session_start();
}

if(!isset($_SESSION['nome'])) {
    die(header('Location: user-login.php'));
}

?>