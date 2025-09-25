<?php
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/Cupom.php';

class Pedido {
    public static function criar($subtotal, $frete, $total, $cep, $endereco, $itens, $cupom, $desconto) {
        $pdo = Database::connect();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO pedidos (subtotal, frete, total, cep, endereco, status) VALUES (?, ?, ?, ?, ?, 'pendente')");
        $stmt->execute([$subtotal, $frete, $total, $cep, $endereco]);
        $pedido_id = $pdo->lastInsertId();
        $pdo->commit();
        return $pedido_id;
    }

    public static function listar() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM pedidos ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function calcularDesconto($cupom, $subtotal) {
        if (!$cupom) return 0;
        $cupomData = Cupom::buscarPorCodigo($cupom);
        if (!$cupomData) return 0;
        if ($cupomData['ativo'] != 1) return 0;
        if ($cupomData['valido_ate'] && $cupomData['valido_ate'] < date('Y-m-d')) return 0;
        if ($cupomData['desconto_percentual']) {
            if ($subtotal < $cupomData['valor_minimo_pedido']) return 0;
            return ($subtotal * $cupomData['desconto_percentual']) / 100;
        }
        if ($cupomData['desconto_valor']) {
            if ($subtotal < $cupomData['valor_minimo_pedido']) return 0;
            return $cupomData['desconto_valor'];
        }
        return 0;
    }

    public static function confirmar($id, $forma_pagamento) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE pedidos SET forma_pagamento=?, confirmado=1, status_pagamento='confirmado' WHERE id=?");
        $stmt->execute([$forma_pagamento, $id]);
    }

    public static function cancelar($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE pedidos SET status_pagamento='cancelado' WHERE id=?");
        $stmt->execute([$id]);
    }

    public static function remover($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id=?");
        $stmt->execute([$id]);
    }

    public static function atualizarStatus($id, $status) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE pedidos SET status_pagamento=? WHERE id=?");
        $stmt->execute([$status, $id]);
    }
} 