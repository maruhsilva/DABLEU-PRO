<?php
session_start();

// Conectar ao banco de dados
$host = 'localhost';
$db = 'login_dableupro';
$user = 'root';
$password = '';
try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "<h1>Erro ao conectar ao banco de dados: " . $e->getMessage() . "</h1>";
    exit;
}

// Capturar o ID do pedido da URL
$id_pedido = isset($_GET['id']) ? $_GET['id'] : null;
if (!$id_pedido) {
    echo "<h1>Erro: ID do pedido não encontrado.</h1>";
    exit;
}

try {
    // Buscar os detalhes do pedido
    $stmt = $pdo->prepare("SELECT * FROM pedidos WHERE id_pedido = :id_pedido");
    $stmt->execute(['id_pedido' => $id_pedido]);
    $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$pedido) {
        echo "<h1>Erro: Pedido não encontrado.</h1>";
        exit;
    }

    // Buscar os itens do pedido
    $stmt_items = $pdo->prepare("SELECT * FROM itens_pedido WHERE id_pedido = :id_pedido");
    $stmt_items->execute(['id_pedido' => $id_pedido]);
    $itens = $stmt_items->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "<h1>Erro ao buscar dados do pedido: " . $e->getMessage() . "</h1>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Detalhes do Pedido</title>
    <!-- Adicione seus estilos CSS aqui -->
</head>
<body>
    <h1>Detalhes do Pedido #<?= isset($pedido['id_pedido']) ? $pedido['id_pedido'] : 'N/A' ?></h1>

    <table border="1">
        <tr>
            <th>Data do Pedido</th>
            <td><?= isset($pedido['data_pedido']) ? date('d/m/Y H:i:s', strtotime($pedido['data_pedido'])) : 'N/A' ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= isset($pedido['status']) ? ucfirst($pedido['status']) : 'N/A' ?></td>
        </tr>
        <tr>
            <th>Total</th>
            <td>R$ <?= isset($pedido['total']) ? number_format($pedido['total'], 2, ',', '.') : '0,00' ?></td>
        </tr>
        <tr>
            <th>Forma de Pagamento</th>
            <td><?= isset($pedido['metodo_pagamento']) ? ucfirst($pedido['metodo_pagamento']) : 'N/A' ?></td>
        </tr>
        <tr>
            <th>E-mail do Comprador</th>
            <td><?= isset($pedido['email_payer']) ? $pedido['email_payer'] : 'N/A' ?></td>
        </tr>
    </table>

    <h2>Itens do Pedido</h2>
    <table border="1">
        <thead>
            <tr>
                <th>Nome do Produto</th>
                <th>Quantidade</th>
                <th>Preço Unitário</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($itens)): ?>
                <?php foreach ($itens as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['nome_produto']) ?></td>
                        <td><?= $item['quantidade'] ?></td>
                        <td>R$ <?= number_format($item['preco'], 2, ',', '.') ?></td>
                        <td>R$ <?= number_format($item['quantidade'] * $item['preco'], 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4">Nenhum item encontrado para este pedido.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="meus_pedidos.php">Voltar para Meus Pedidos</a>
</body>
</html>
