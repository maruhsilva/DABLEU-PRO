<?php

$dbhost = 'Localhost';
$dbusuario = 'root';
$dbsenha = '';
$dbnome = 'login_dableupro';

    
    $mysqli = new mysqli($dbhost, $dbusuario, $dbsenha, $dbnome);

    if ($mysqli->connect_errno) 
    { 
        echo "Não Conectado: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error; 
    }
?>