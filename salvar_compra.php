<?php
session_start();
require __DIR__ . '/vendor/autoload.php';

// Configurações do banco de dados
$host = 'localhost';
$dbname = 'login_dableupro';
$user = 'root';
$password = '';

// Conexão com o banco de dados
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Verifica se o pagamento foi aprovado
    if (!isset($_GET['status']) || $_GET['status'] !== 'approved') {
        echo "Pagamento não aprovado ou status inválido.";
        exit;
    }

    // Obtém informações da URL
    $id_usuario = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : null;
    if (!$id_usuario) {
        echo "Usuário não identificado.";
        exit;
    }

    // Obtém dados do pagamento do Mercado Pago
    $payment_id = $_GET['payment_id'];
    $total = $_GET['transaction_amount'];
    $itens = $_SESSION['carrinho'] ?? []; // Carrinho salvo na sessão

    // Inicia transação
    $pdo->beginTransaction();

    // Salva pedido
    $stmt = $pdo->prepare("INSERT INTO pedidos (id_usuario, total) VALUES (:id_usuario, :total)");
    $stmt->execute([':id_usuario' => $id_usuario, ':total' => $total]);
    $id_pedido = $pdo->lastInsertId();

    // Salva itens do pedido
    $stmt = $pdo->prepare("INSERT INTO itens_pedido (id_pedido, nome_produto, quantidade, preco) VALUES (:id_pedido, :nome_produto, :quantidade, :preco)");
    foreach ($itens as $item) {
        $stmt->execute([
            ':id_pedido' => $id_pedido,
            ':nome_produto' => $item['title'],
            ':quantidade' => $item['quantity'],
            ':preco' => $item['unit_price']
        ]);
    }

    // Confirma a transação
    $pdo->commit();

    echo "Pedido salvo com sucesso!";
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "Erro ao salvar pedido: " . $e->getMessage();
}
