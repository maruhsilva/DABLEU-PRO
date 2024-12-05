<?php
session_start();
ob_start();
header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php';
MercadoPago\SDK::setAccessToken('TEST-7557293504970150-111823-b70f77389318ae03320e08bd19dd8afa-50073279');

// Configuração do banco de dados
$host = 'login_dableu.mysql.dbaas.com.br';
$db = 'login_dableu';
$user = 'login_dableu';
$password = 'Marua3902@';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()]);
    ob_end_flush();
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Verifica se os itens foram enviados e se são válidos
if (!isset($data['itens']) || !is_array($data['itens']) || empty($data['itens'])) {
    echo json_encode(['success' => false, 'message' => 'Itens não enviados ou inválidos.']);
    ob_end_flush();
    exit;
}

// Verifica se o usuário está autenticado
$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    ob_end_flush();
    exit;
}

// Verifica o método de pagamento selecionado (Crédito/Boleto ou Pix)
$metodo_pagamento = isset($data['metodo_pagamento']) ? $data['metodo_pagamento'] : 'credito';

// Calcular o total do pedido
$total = 0;
foreach ($data['itens'] as $item) {
    if (!isset($item['title'], $item['quantity'], $item['unit_price']) || $item['quantity'] <= 0 || $item['unit_price'] <= 0) {
        echo json_encode(['success' => false, 'message' => 'Dados inválidos para um ou mais itens.']);
        ob_end_flush();
        exit;
    }
    $total += $item['quantity'] * $item['unit_price'];
}

try {
    // Inserir o pedido temporário na tabela "pedidos_temporarios"
    $stmt = $pdo->prepare("INSERT INTO pedidos_temporarios (id_usuario, total, metodo_pagamento, email_payer) 
                           VALUES (:id_usuario, :total, :metodo_pagamento, 'pendente@example.com')");
    $stmt->execute([ 'id_usuario' => $id_usuario, 'total' => $total, 'metodo_pagamento' => $metodo_pagamento ]);
    $id_pedido_temp = $pdo->lastInsertId();  // ID do pedido temporário

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar o pedido temporário: ' . $e->getMessage()]);
    ob_end_flush();
    exit;
}

// Criar a preferência de pagamento com Mercado Pago
$preference = new MercadoPago\Preference();
$items = [];

foreach ($data['itens'] as $item) {
    $produto = new MercadoPago\Item();
    $produto->title = $item['title'];
    $produto->quantity = $item['quantity'];
    $produto->unit_price = $item['unit_price'];
    $items[] = $produto;
}

$preference->items = $items;

// URLs de retorno com base no status do pagamento
$preference->back_urls = [
    "success" => "https://dableupro.com.br/retorno-pagamento.php?id_usuario={$id_usuario}&id_pedido={$id_pedido_temp}",
    "failure" => "https://dableupro.com.br/retorno-intermediario.php?status=failure",
    "pending" => "https://dableupro.com.br/retorno-intermediario.php?status=pending"
];

$preference->auto_return = "approved";

// Configurar os métodos de pagamento permitidos
if ($metodo_pagamento == 'credito') {
    $preference->payment_methods = [
        'excluded_payment_types' => [
            ['id' => 'pix'],         // Exclui Pix
            ['id' => 'ticket'],      // Exclui Boleto
        ],
        'default_payment_method' => 'credit_card',  // Define Cartão de Crédito como padrão
        'installments' => 12,                      // Permite até 12 parcelas
        'default_installments' => 3                // Configura 3 parcelas sem juros como padrão
    ];
} elseif ($metodo_pagamento == 'pix') {
    $preference->payment_methods = [
        'excluded_payment_types' => [
            ['id' => 'credit_card'], // Exclui Cartão de Crédito
        ],
        'default_payment_method' => 'pix',  // Define Pix como padrão
    ];
}

try {
    $preference->save();

    // Atualizar o pedido temporário com o ID de pagamento do Mercado Pago
    $stmt_update = $pdo->prepare("UPDATE pedidos_temporarios SET id_pagamento = :id_pagamento WHERE id_pedido = :id_pedido");
    $stmt_update->execute([
        'id_pagamento' => $preference->id, // ID de pagamento gerado pelo Mercado Pago
        'id_pedido' => $id_pedido_temp
    ]);
    
    $response = [
        'success' => true,
        'redirect_url' => $preference->init_point  // URL de redirecionamento do Mercado Pago
    ];
    echo json_encode($response);
    ob_end_flush();
} catch (Exception $e) {
    $response = [
        'success' => false,
        'message' => 'Erro ao processar a compra: ' . $e->getMessage()
    ];
    echo json_encode($response);
    ob_end_flush();
}

// Limpeza de pedidos expirados (1 hora)
$pdo->exec("DELETE FROM pedidos_temporarios WHERE status_temp = 1 AND data_criacao < NOW() - INTERVAL 1 HOUR");
?>
