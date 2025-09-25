<?php
require_once __DIR__ . '/../models/Pedido.php';
require_once __DIR__ . '/../models/Produto.php';
require_once __DIR__ . '/../utils/Mailer.php';

class PedidoController {
    public function finalizar() {
        $carrinho = $_SESSION['carrinho'] ?? [];
        if (empty($carrinho)) {
            $_SESSION['erro'] = 'Carrinho vazio!';
            header('Location: ?controller=carrinho');
            exit;
        }
        $email = $_POST['email'] ?? '';
        $cep = $_POST['cep'] ?? '';
        $endereco = $_POST['endereco'] ?? '';
        $cupom = $_POST['cupom'] ?? '';
        
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['erro'] = 'E-mail inválido!';
            header('Location: ?controller=carrinho');
            exit;
        }
        
        $subtotal = 0;
        foreach ($carrinho as $item) {
            $subtotal += $item['preco'] * $item['quantidade'];
        }
        // Cálculo do frete
        if ($subtotal > 200) {
            $frete = 0;
        } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
            $frete = 15;
        } elseif ($subtotal > 0) {
            $frete = 20;
        } else {
            $frete = 0;
        }
        $desconto = Pedido::calcularDesconto($cupom, $subtotal);
        $total = $subtotal + $frete - $desconto;
        $pedido_id = Pedido::criar($subtotal, $frete, $total, $cep, $endereco, $carrinho, $cupom, $desconto);
        // Atualiza estoque
        foreach ($carrinho as $item) {
            Produto::baixarEstoque($item['produto_id'], $item['variacao'], $item['quantidade']);
        }
        // Envio de e-mail
        $email_enviado = Mailer::enviarEmailPedido($pedido_id, $endereco, $cep, $carrinho, $subtotal, $frete, $desconto, $total, $email);
        
        // Armazena informações do pedido na sessão para o pop-up
        $_SESSION['pedido_finalizado'] = [
            'id' => $pedido_id,
            'email' => $email,
            'endereco' => $endereco,
            'cep' => $cep,
            'itens' => $carrinho,
            'subtotal' => $subtotal,
            'frete' => $frete,
            'desconto' => $desconto,
            'total' => $total,
            'email_enviado' => $email_enviado
        ];
        
        unset($_SESSION['carrinho']);
        header('Location: ?controller=pedido&action=confirmacao');
        exit;
    }

    public function confirmacao() {
        if (!isset($_SESSION['pedido_finalizado'])) {
            header('Location: ?controller=pedido&action=historico');
            exit;
        }
        $pedido = $_SESSION['pedido_finalizado'];
        unset($_SESSION['pedido_finalizado']);
        include __DIR__ . '/../views/pedidos/confirmacao.php';
    }

    public function historico() {
        $pedidos = Pedido::listar();
        include __DIR__ . '/../views/pedidos/index.php';
    }

    public function confirmar() {
        $id = $_POST['id'] ?? null;
        $forma = $_POST['forma_pagamento'] ?? null;
        if ($id && $forma) {
            Pedido::confirmar($id, $forma);
            $_SESSION['msg'] = 'Pedido confirmado com sucesso!';
        }
        header('Location: ?controller=pedido&action=historico');
        exit;
    }

    public function cancelar() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Pedido::cancelar($id);
            $_SESSION['msg'] = 'Pedido cancelado!';
        }
        header('Location: ?controller=pedido&action=historico');
        exit;
    }
} 