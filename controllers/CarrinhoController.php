<?php
require_once __DIR__ . '/../models/Produto.php';

class CarrinhoController {
    public function index() {
        $carrinho = $_SESSION['carrinho'] ?? [];
        $produtos = Produto::listarComEstoque();
        include __DIR__ . '/../views/carrinho/index.php';
    }

    public function adicionar() {
        $produto_id = $_POST['produto_id'] ?? null;
        $variacao = $_POST['variacao'] ?? 'Principal';
        $quantidade = $_POST['quantidade'] ?? 1;
        if (!$produto_id) {
            header('Location: ?controller=produto');
            exit;
        }
        // Busca produto e estoque
        $produto = Produto::buscarPorId($produto_id);
        $estoque = 0;
        foreach ($produto['variacoes'] as $v) {
            if ($v['variacao'] == $variacao) {
                $estoque = $v['quantidade'];
            }
        }
        if ($quantidade > $estoque) {
            $_SESSION['erro'] = 'Estoque insuficiente!';
            header('Location: ?controller=carrinho');
            exit;
        }
        // Adiciona ao carrinho
        $key = $produto_id . '|' . $variacao;
        if (!isset($_SESSION['carrinho'][$key])) {
            $_SESSION['carrinho'][$key] = [
                'produto_id' => $produto_id,
                'variacao' => $variacao,
                'quantidade' => 0,
                'nome' => $produto['nome'],
                'preco' => $produto['preco']
            ];
        }
        $_SESSION['carrinho'][$key]['quantidade'] += $quantidade;
        header('Location: ?controller=carrinho');
        exit;
    }

    public function remover() {
        $key = $_GET['key'] ?? null;
        if ($key && isset($_SESSION['carrinho'][$key])) {
            unset($_SESSION['carrinho'][$key]);
        }
        header('Location: ?controller=carrinho');
        exit;
    }

    public function atualizar() {
        foreach ($_POST['quantidade'] as $key => $qtd) {
            if (isset($_SESSION['carrinho'][$key])) {
                $_SESSION['carrinho'][$key]['quantidade'] = max(1, (int)$qtd);
            }
        }
        header('Location: ?controller=carrinho');
        exit;
    }

    public function limpar() {
        unset($_SESSION['carrinho']);
        header('Location: ?controller=carrinho');
        exit;
    }
} 