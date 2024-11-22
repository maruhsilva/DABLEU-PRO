<?php
session_start();

if (isset($_POST['submit']) && !empty($_POST['email']) && !empty($_POST['senha'])) {
    require_once 'config.php';

    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Consulta segura com prepared statements
    $stmt = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        if (password_verify($senha, $user['senha'])) {
            $_SESSION['user_id'] = $user['id_usuario'];
            $_SESSION['user_name'] = $user['nome'];
            $_SESSION['logged_in'] = true;

            // Redireciona para a página de origem ou página padrão
            $redirect_to = $_SESSION['redirect_to'] ?? 'user-logado.php';
            unset($_SESSION['redirect_to']); // Limpa a URL de origem da sessão
            header("Location: $redirect_to");
            exit();
        } else {
            $_SESSION['login_error'] = "Senha incorreta.";
            header("Location: user-login.php");
            exit();
        }
    } else {
        $_SESSION['login_error'] = "E-mail não cadastrado.";
        header("Location: user-login.php");
        exit();
    }
} else {
    $_SESSION['login_error'] = "Preencha todos os campos.";
    header("Location: user-login.php");
    exit();
}
