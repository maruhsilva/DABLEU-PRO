<?php
session_start();
ob_start();
header('Content-Type: application/json');

require __DIR__ . '/vendor/autoload.php';
MercadoPago\SDK::setAccessToken('TEST-7557293504970150-111823-b70f77389318ae03320e08bd19dd8afa-50073279');

// Configuração do banco de dados
$host = 'localhost';
$db = 'login_dableupro';
$user = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao conectar ao banco de dados: ' . $e->getMessage()]);
    ob_end_flush();
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['itens']) || !is_array($data['itens']) || empty($data['itens'])) {
    echo json_encode(['success' => false, 'message' => 'Itens não enviados ou inválidos.']);
    ob_end_flush();
    exit;
}

$id_usuario = isset($_SESSION['id_usuario']) ? $_SESSION['id_usuario'] : null;
if (!$id_usuario) {
    echo json_encode(['success' => false, 'message' => 'Usuário não autenticado.']);
    ob_end_flush();
    exit;
}

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
    // Inserir o pedido na tabela "pedidos" com o status "pendente" e as informações de pagamento
    $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, total, data_pedido, status, metodo_pagamento, email_payer) 
                           VALUES (:id_usuario, :total, NOW(), 'pendente', 'pendente', 'pendente@example.com')");
    $stmt->execute([
        'id_usuario' => $id_usuario,
        'total' => $total
    ]);
    $id_pedido = $pdo->lastInsertId();

    // Inserir os itens na tabela "itens_pedido"
    $stmt_item = $pdo->prepare("INSERT INTO itens_pedido (id_pedido, nome_produto, quantidade, preco) 
                                VALUES (:id_pedido, :nome_produto, :quantidade, :preco)");
    foreach ($data['itens'] as $item) {
        $stmt_item->execute([
            'id_pedido' => $id_pedido,
            'nome_produto' => $item['title'],
            'quantidade' => $item['quantity'],
            'preco' => $item['unit_price']
        ]);
    }

    // Atualizar o pedido com os dados de pagamento antes de gerar a preferência
    $stmt_update = $pdo->prepare("UPDATE pedidos SET id_pagamento = :id_pagamento, token_pagamento = :token_pagamento, metodo_pagamento = :metodo_pagamento, email_payer = :email_payer WHERE id_pedido = :id_pedido");
    $stmt_update->execute([
        'id_pagamento' => null, // Inicialmente sem ID de pagamento
        'token_pagamento' => null, // Inicialmente sem token de pagamento
        'metodo_pagamento' => 'pendente', // O status inicial é pendente
        'email_payer' => 'pendente@example.com', // O email do pagador pode ser atualizado após o pagamento
        'id_pedido' => $id_pedido // Usando o nome correto da coluna (id_pedido)
    ]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erro ao salvar o pedido: ' . $e->getMessage()]);
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

$preference->back_urls = [
    "success" => "http://localhost/DABLEU-PRO/retorno-pagamento.php?id_usuario={$id_usuario}&id_pedido={$id_pedido}",
    "failure" => "http://localhost/DABLEU-PRO/retorno-pagamento.php",
    "pending" => "http://localhost/DABLEU-PRO/retorno-pagamento.php"
];

$preference->auto_return = "approved";
$preference->payment_methods = array(
    'installments' => 10,
    'excluded_payment_types' => array(
        array("id" => "atm")
    ),
    'default_installments' => 3
);

try {
    $preference->save();

    // Atualizar o pedido com o ID de pagamento do Mercado Pago
    $stmt_update = $pdo->prepare("UPDATE pedidos SET id_pagamento = :id_pagamento WHERE id_pedido = :id_pedido");
    $stmt_update->execute([
        'id_pagamento' => $preference->id, // Agora temos o ID de pagamento gerado pelo Mercado Pago
        'id_pedido' => $id_pedido
    ]);
    
    $response = [
        'success' => true,
        'redirect_url' => $preference->init_point
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
