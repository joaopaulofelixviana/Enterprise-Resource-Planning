<?php
require_once __DIR__ . '/../config/database.php';
class Cupom {
    public static function buscarPorCodigo($codigo) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM cupons WHERE codigo = ? LIMIT 1");
        $stmt->execute([$codigo]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public static function listar() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM cupons ORDER BY id DESC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function criar($codigo, $tipo, $valor, $minimo, $valido_ate) {
        $pdo = Database::connect();
        $desconto_percentual = $tipo == 'percentual' ? $valor : null;
        $desconto_valor = $tipo == 'valor' ? $valor : null;
        $stmt = $pdo->prepare("INSERT INTO cupons (codigo, desconto_percentual, desconto_valor, valido_ate, ativo, valor_minimo_pedido) VALUES (?, ?, ?, ?, 1, ?)");
        $stmt->execute([$codigo, $desconto_percentual, $desconto_valor, $valido_ate, $minimo]);
    }
    public static function excluir($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("DELETE FROM cupons WHERE id = ?");
        $stmt->execute([$id]);
    }
} 