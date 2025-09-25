<?php
require_once __DIR__ . '/../models/Cupom.php';

class CupomController {
    public function index() {
        $cupons = Cupom::listar();
        include __DIR__ . '/../views/cupons/index.php';
    }

    public function salvar() {
        $codigo = $_POST['codigo'] ?? '';
        $tipo = $_POST['tipo'] ?? 'percentual';
        $valor = $_POST['valor'] ?? 0;
        $minimo = $_POST['minimo'] ?? 0;
        $valido_ate = $_POST['valido_ate'] ?? null;
        if (!$codigo) {
            $_SESSION['erro'] = 'Preencha o código do cupom!';
            header('Location: ?controller=cupom');
            exit;
        }
        Cupom::criar($codigo, $tipo, $valor, $minimo, $valido_ate);
        $_SESSION['msg'] = 'Cupom criado!';
        header('Location: ?controller=cupom');
        exit;
    }

    public function editar() {
        $id = $_GET['id'] ?? null;
        $cupom = Cupom::buscarPorId($id);
        $cupons = Cupom::listar();
        include __DIR__ . '/../views/cupons/index.php';
    }

    public function excluir() {
        $id = $_GET['id'] ?? null;
        if ($id) {
            Cupom::excluir($id);
            $_SESSION['msg'] = 'Cupom excluído!';
        }
        header('Location: ?controller=cupom');
        exit;
    }
} 