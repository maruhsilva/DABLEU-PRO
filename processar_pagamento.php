<?php
// Definir o cabeçalho para aceitar JSON
header('Content-Type: application/json');

// Ler os dados recebidos
$dadosRecebidos = file_get_contents("php://input");

// Verificar se há dados recebidos
if (!$dadosRecebidos) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Nenhum dado recebido.']);
    exit;
}

// Exibir os dados recebidos (para depuração)
error_log("Dados recebidos: " . $dadosRecebidos);

// Tentar decodificar o JSON
$dados = json_decode($dadosRecebidos, true);

// Verificar se houve erro de decodificação
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Erro ao decodificar os dados JSON: ' . json_last_error_msg()]);
    exit;
}

// Verificar se o carrinho está presente nos dados
if (!isset($dados['carrinho'])) {
    echo json_encode(['sucesso' => false, 'mensagem' => 'Dados incompletos recebidos.']);
    exit;
}

// Exibir os dados decodificados (para depuração)
error_log("Carrinho decodificado: " . print_r($dados['carrinho'], true));

// Aqui você pode processar os dados do carrinho, por exemplo, salvar no banco de dados ou realizar outras ações
// (Exemplo: loop para salvar os itens no banco de dados)

echo json_encode(['sucesso' => true, 'mensagem' => 'Carrinho processado com sucesso.']);
exit;
?>
