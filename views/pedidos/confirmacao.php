<?php include __DIR__ . '/../layouts/header.php'; ?>

<div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50" id="modalConfirmacao">
    <div class="bg-white rounded-2xl shadow-xl max-w-2xl w-full mx-4 overflow-hidden">
        <div class="p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Pedido Confirmado!</h2>
                <button onclick="fecharModal()" class="text-gray-400 hover:text-gray-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
            
            <div class="space-y-4">
                <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                    <div class="flex items-center gap-3">
                        <div class="bg-green-100 rounded-full p-2">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="font-semibold text-green-800">Pedido #<?= $pedido['id'] ?> realizado com sucesso!</h3>
                            <p class="text-green-600">
                                <?php if ($pedido['email_enviado']): ?>
                                    Um e-mail de confirmação foi enviado para <?= htmlspecialchars($pedido['email']) ?>
                                <?php else: ?>
                                    Houve um problema ao enviar o e-mail de confirmação. Por favor, verifique se o endereço está correto.
                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Detalhes do Pedido</h3>
                    <div class="space-y-2">
                        <p><span class="text-gray-600">Endereço de Entrega:</span><br>
                        <?= htmlspecialchars($pedido['endereco']) ?><br>
                        CEP: <?= htmlspecialchars($pedido['cep']) ?></p>
                    </div>
                </div>

                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Itens do Pedido</h3>
                    <div class="space-y-2">
                        <?php foreach ($pedido['itens'] as $item): ?>
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="font-medium"><?= htmlspecialchars($item['nome']) ?></p>
                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($item['variacao']) ?> x <?= $item['quantidade'] ?></p>
                                </div>
                                <p class="font-medium">R$ <?= number_format($item['preco'] * $item['quantidade'], 2, ',', '.') ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="border rounded-lg p-4">
                    <h3 class="font-semibold text-gray-800 mb-3">Resumo do Pedido</h3>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Subtotal:</span>
                            <span>R$ <?= number_format($pedido['subtotal'], 2, ',', '.') ?></span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Frete:</span>
                            <span><?= $pedido['frete'] == 0 ? 'GRÁTIS' : 'R$ ' . number_format($pedido['frete'], 2, ',', '.') ?></span>
                        </div>
                        <?php if ($pedido['desconto'] > 0): ?>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Desconto:</span>
                            <span>R$ <?= number_format($pedido['desconto'], 2, ',', '.') ?></span>
                        </div>
                        <?php endif; ?>
                        <div class="flex justify-between font-bold text-lg pt-2 border-t">
                            <span>Total:</span>
                            <span>R$ <?= number_format($pedido['total'], 2, ',', '.') ?></span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex gap-3">
                <a href="?controller=pedido&action=historico" class="flex-1 py-3 px-4 rounded-xl bg-primary text-white font-semibold hover:bg-blue-700 transition text-center">
                    Ver Meus Pedidos
                </a>
                <a href="?controller=produto" class="flex-1 py-3 px-4 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition text-center">
                    Continuar Comprando
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function fecharModal() {
    window.location.href = '?controller=pedido&action=historico';
}
</script>

<?php include __DIR__ . '/../layouts/footer.php'; ?> 