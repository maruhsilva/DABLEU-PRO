<?php
$status = isset($_GET['status']) ? $_GET['status'] : 'unknown';

// Redirecionar com base no status do pagamento
switch ($status) {
    case 'failure':
        // Redireciona para a página de erro de pagamento
        header("Location: https://dableupro.com.br/carrinho-logado.php");
        exit;

    case 'pending':
        // Redireciona o usuário de volta ao carrinho para tentar novamente
        header("Location: https://dableupro.com.br/retorno-pagamento.php");
        exit;

    default:
        // Caso o status seja desconhecido, redireciona para o carrinho como fallback
        header("Location: https://dableupro.com.br/carrinho-logado.php");
        exit;
}
?>
