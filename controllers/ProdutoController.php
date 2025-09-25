<?php
require_once __DIR__ . '/../models/Produto.php';

class ProdutoController {
    public function index() {
        $produtos = Produto::listarComEstoque();
        include __DIR__ . '/../views/produtos/index.php';
    }

    public function salvar() {
        $id = $_POST['id'] ?? null;
        $nome = $_POST['nome'] ?? '';
        $preco = $_POST['preco'] ?? '';
        $estoque = $_POST['estoque'] ?? 0;
        $descricao = $_POST['descricao'] ?? '';
        $variacoes = $_POST['variacoes'] ?? [];
        if (!$nome || !$preco) {
            $_SESSION['erro'] = 'Preencha todos os campos obrigatórios!';
            header('Location: ?controller=produto');
            exit;
        }
        if ($id) {
            Produto::atualizar($id, $nome, $descricao, $preco, $variacoes, $estoque);
            $_SESSION['msg'] = 'Produto atualizado com sucesso!';
        } else {
            Produto::criar($nome, $descricao, $preco, $variacoes, $estoque);
            $_SESSION['msg'] = 'Produto cadastrado com sucesso!';
        }
        header('Location: ?controller=produto');
        exit;
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        if (!$id) {
            header('Location: ?controller=produto');
            exit;
        }
        $produto = Produto::buscarPorId($id);
        $produtos = Produto::listarComEstoque();
        include __DIR__ . '/../views/produtos/index.php';
    }

    public function excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Produto::excluir($id);
            $_SESSION['msg'] = 'Produto excluído!';
        }
        header('Location: ?controller=produto');
        exit;
    }
} 