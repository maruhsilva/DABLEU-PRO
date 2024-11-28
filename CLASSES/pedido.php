<?php 

class Pedido {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    // Método para criar um novo pedido
    public function criarPedido($id_usuario, $itens, $total) {
        try {
            // Iniciar uma transação
            $this->pdo->beginTransaction();
    
            // Inserir pedido com data_pedido
            $stmt = $this->pdo->prepare("INSERT INTO pedidos (id_usuario, total, status, data_pedido) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$id_usuario, $total, 'Pendente']);
            $id_pedido = $this->pdo->lastInsertId();
    
            // Inserir itens do pedido
            foreach ($itens as $item) {
                $stmt = $this->pdo->prepare("INSERT INTO itens_pedido (id_pedido, titulo, quantidade, preco_unitario) VALUES (?, ?, ?, ?)");
                $stmt->execute([$id_pedido, $item['nome'], $item['quantidade'], $item['preco']]);
            }
    
            // Commit da transação
            $this->pdo->commit();
    
            return $id_pedido;
        } catch (Exception $e) {
            // Em caso de erro, desfaz a transação
            $this->pdo->rollBack();
            throw new Exception("Erro ao criar o pedido: " . $e->getMessage());
        }
    }

    // Método para obter os pedidos do usuário
   public function obterPedidos($id_usuario) {
    $stmt = $this->pdo->prepare("SELECT * FROM pedidos WHERE id_usuario = :id_usuario ORDER BY data_pedido DESC");
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


    // Método para obter os itens de um pedido específico
    public function obterItensPedido($id_pedido) {
        $stmt = $this->pdo->prepare("SELECT * FROM itens_pedido WHERE id_pedido = ?");
        $stmt->execute([$id_pedido]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>