<?php
require_once __DIR__ . '/../models/Pedido.php';
header('Content-Type: application/json');

// Recebe dados JSON
$input = json_decode(file_get_contents('php://input'), true);
$id = $input['id'] ?? null;
$status = $input['status'] ?? null;

if (!$id || !$status) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'ID e status obrigatórios']);
    exit;
}

if (strtolower($status) === 'cancelado') {
    Pedido::remover($id);
    echo json_encode(['success' => true, 'message' => 'Pedido removido']);
    exit;
} else {
    Pedido::atualizarStatus($id, $status);
    echo json_encode(['success' => true, 'message' => 'Status atualizado']);
    exit;
} 