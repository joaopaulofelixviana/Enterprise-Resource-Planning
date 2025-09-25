<?php
require_once __DIR__ . '/../config/database.php';

class Produto {
    public static function listarTodos() {
        $pdo = Database::connect();
        $stmt = $pdo->query("SELECT * FROM produtos");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function listarComEstoque() {
        $pdo = Database::connect();
        $produtos = $pdo->query("SELECT * FROM produtos ORDER BY id DESC")->fetchAll(PDO::FETCH_ASSOC);
        foreach ($produtos as &$produto) {
            $stmt = $pdo->prepare("SELECT * FROM estoque WHERE produto_id = ?");
            $stmt->execute([$produto['id']]);
            $produto['variacoes'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return $produtos;
    }

    public static function criar($nome, $descricao, $preco, $variacoes, $estoquePrincipal) {
        $pdo = Database::connect();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("INSERT INTO produtos (nome, descricao, preco) VALUES (?, ?, ?)");
        $stmt->execute([$nome, $descricao, $preco]);
        $produto_id = $pdo->lastInsertId();
        // Estoque principal
        $stmt2 = $pdo->prepare("INSERT INTO estoque (produto_id, variacao, quantidade) VALUES (?, ?, ?)");
        $stmt2->execute([$produto_id, 'Principal', $estoquePrincipal]);
        // Variações
        foreach ($variacoes as $var) {
            if (trim($var) !== '') {
                $stmt2->execute([$produto_id, $var, 0]);
            }
        }
        $pdo->commit();
    }

    public static function atualizar($id, $nome, $descricao, $preco, $variacoes, $estoquePrincipal) {
        $pdo = Database::connect();
        $pdo->beginTransaction();
        $stmt = $pdo->prepare("UPDATE produtos SET nome=?, descricao=?, preco=? WHERE id=?");
        $stmt->execute([$nome, $descricao, $preco, $id]);
        // Atualiza estoque principal
        $stmt2 = $pdo->prepare("UPDATE estoque SET quantidade=? WHERE produto_id=? AND variacao='Principal'");
        $stmt2->execute([$estoquePrincipal, $id]);
        // Remove variações antigas
        $pdo->prepare("DELETE FROM estoque WHERE produto_id=? AND variacao!='Principal'")->execute([$id]);
        // Adiciona novas variações
        foreach ($variacoes as $var) {
            if (trim($var) !== '') {
                $stmt2 = $pdo->prepare("INSERT INTO estoque (produto_id, variacao, quantidade) VALUES (?, ?, 0)");
                $stmt2->execute([$id, $var]);
            }
        }
        $pdo->commit();
    }

    public static function buscarPorId($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM produtos WHERE id=?");
        $stmt->execute([$id]);
        $produto = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt2 = $pdo->prepare("SELECT * FROM estoque WHERE produto_id=?");
        $stmt2->execute([$id]);
        $produto['variacoes'] = $stmt2->fetchAll(PDO::FETCH_ASSOC);
        return $produto;
    }

    public static function excluir($id) {
        $pdo = Database::connect();
        $pdo->prepare("DELETE FROM estoque WHERE produto_id=?")->execute([$id]);
        $pdo->prepare("DELETE FROM produtos WHERE id=?")->execute([$id]);
    }

    public static function baixarEstoque($produto_id, $variacao, $quantidade) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("UPDATE estoque SET quantidade = quantidade - ? WHERE produto_id = ? AND variacao = ? AND quantidade >= ?");
        $stmt->execute([$quantidade, $produto_id, $variacao, $quantidade]);
    }
} 