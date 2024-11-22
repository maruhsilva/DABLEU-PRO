<?php
session_start();

// Verifica se o usuário está logado
if (isset($_SESSION['id_usuario'])) {
    // Usuário está logado, redireciona para carrinho-logado.php
    header('Location: carrinho-logado.php');
    exit;
} else {
    // Usuário não está logado, salva o destino e redireciona para user-login.php
    $_SESSION['redirect_to'] = 'carrinho-logado.php';
    header('Location: user-login.php');
    exit;
}
?>
