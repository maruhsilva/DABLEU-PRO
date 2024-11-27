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

    try {
        $pdo = new PDO("mysql:host=$dbhost;dbname=$dbnome", $dbusuario, $dbsenha);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Erro de conexão: " . $e->getMessage());
    }
    
?>