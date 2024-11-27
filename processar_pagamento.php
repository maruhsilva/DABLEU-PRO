<?php
// Iniciar a sessão no início do script
session_start();

// Desativar qualquer saída anterior (importante para evitar caracteres inesperados)
ob_start();

// Definir cabeçalho para resposta JSON
header('Content-Type: application/json');

// Configuração da API do Mercado Pago
require __DIR__ . '/vendor/autoload.php';
MercadoPago\SDK::setAccessToken('TEST-7557293504970150-111823-b70f77389318ae03320e08bd19dd8afa-50073279');

// Coletar dados enviados pelo frontend
$data = json_decode(file_get_contents('php://input'), true);

// Verificar se os dados dos itens foram enviados corretamente
if (!isset($data['itens']) || !is_array($data['itens']) || empty($data['itens'])) {
    echo json_encode(['success' => false, 'message' => 'Itens não enviados ou inválidos.']);
    ob_end_flush(); // Limpar o buffer e enviar a resposta
    exit;
}

// Criar a preferência
$preference = new MercadoPago\Preference();
$items = [];

// Adicionar os itens à preferência
foreach ($data['itens'] as $item) {
    // Verificar se os dados do item estão completos
    if (!isset($item['title'], $item['quantity'], $item['unit_price']) || $item['quantity'] <= 0 || $item['unit_price'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos para um ou mais itens.']);
        ob_end_flush(); // Limpar o buffer e enviar a resposta
        exit;
    }

    // Criar item e adicionar ao array
    $produto = new MercadoPago\Item();
    $produto->title = $item['title'];
    $produto->quantity = $item['quantity'];
    $produto->unit_price = $item['unit_price'];
    $items[] = $produto;
}

// Adicionar itens à preferência
$preference->items = $items;

// Definir URLs de retorno
$preference->back_urls = [
    "success" => "http://localhost/DABLEU-PRO/retorno-pagamento.php",
    "failure" => "http://localhost/DABLEU-PRO/retorno-pagamento.php",
    "pending" => "http://localhost/DABLEU-PRO/retorno-pagamento.php"
];

// Definir a URL de redirecionamento
$preference->auto_return = "approved";

// Configurar as opções de parcelamento
$preference->payment_methods = array(
    'installments' => 10,  // Máximo de 10 parcelas
    'excluded_payment_types' => array(  // Excluir tipos de pagamento se necessário
        array("id" => "atm")  // Exemplo de exclusão (opcional)
    ),
    'default_installments' => 3 // Define a quantidade de parcelas até 3x sem juros
);

// Salvar a preferência e gerar a URL de redirecionamento
try {
    $preference->save();
    
    // Resposta com a URL para redirecionamento para o Mercado Pago
    $response = [
        'success' => true,
        'redirect_url' => $preference->init_point
    ];

    // Enviar a resposta JSON para o frontend
    echo json_encode($response);

    // Finaliza o buffer e envia a resposta para o navegador
    ob_end_flush(); 

} catch (Exception $e) {
    // Captura qualquer erro ao salvar a preferência e retorna a mensagem de erro
    $response = [
        'success' => false,
        'message' => 'Erro ao processar a compra: ' . $e->getMessage()
    ];

    // Enviar resposta de erro em JSON
    echo json_encode($response);
    ob_end_flush(); // Limpar e enviar a resposta
}
?>
