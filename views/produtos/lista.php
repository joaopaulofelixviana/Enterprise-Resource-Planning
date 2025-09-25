<div class="card mb-3">
    <div class="card-header bg-info text-white">
        Produtos Cadastrados
    </div>
    <div class="card-body" style="max-height: 500px; overflow-y: auto;">
        <?php if (!empty($produtos)): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="bg-white rounded-2xl shadow p-6 flex flex-col gap-2">
                    <div class="mb-2">
                        <h3 class="text-lg font-bold text-gray-800 mb-1"><?= htmlspecialchars($produto['nome']) ?></h3>
                        <div class="text-gray-500 text-sm mb-1"><?= nl2br(htmlspecialchars($produto['descricao'])) ?></div>
                        <div class="text-green-600 text-xl font-bold mb-1">R$ <?= number_format($produto['preco'],2,',','.') ?></div>
                        <div class="text-gray-600 text-sm mb-2">Estoque: <?= array_sum(array_column($produto['variacoes'], 'quantidade')) ?> unidade<?= array_sum(array_column($produto['variacoes'], 'quantidade')) == 1 ? '' : 's' ?></div>
                    </div>
                    <form method="post" action="?controller=carrinho&action=adicionar" class="flex items-center gap-2 mb-2">
                        <input type="hidden" name="produto_id" value="<?= $produto['id'] ?>">
                        <input type="hidden" name="variacao" value="Principal">
                        <button type="button" onclick="alterarQtd(this, -1)" class="px-2 py-1 rounded bg-gray-100 text-xl font-bold">-</button>
                        <input type="number" name="quantidade" value="1" min="1" class="w-16 rounded border-gray-300 text-center" style="appearance: textfield;">
                        <button type="button" onclick="alterarQtd(this, 1)" class="px-2 py-1 rounded bg-gray-100 text-xl font-bold">+</button>
                        <button type="submit" class="flex-1 px-4 py-2 rounded-lg bg-green-500 text-white font-semibold hover:bg-green-600 transition flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 007.48 19h9.04a2 2 0 001.83-1.3L21 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7"/></svg>
                            Comprar
                        </button>
                    </form>
                    <div class="flex gap-2 mt-2">
                        <a href="?controller=produto&action=editar&id=<?= $produto['id'] ?>" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-blue-600 text-white font-semibold hover:bg-blue-700 transition">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536M9 13h3l8-8a2.828 2.828 0 10-4-4l-8 8v3z"/></svg>
                            Editar
                        </a>
                        <a href="?controller=produto&action=excluir&id=<?= $produto['id'] ?>" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-red-500 text-white font-semibold hover:bg-red-600 transition" onclick="return confirm('Excluir este produto?')">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            Excluir
                        </a>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-gray-400 text-lg text-center py-8">Nenhum produto cadastrado.</div>
        <?php endif; ?>
    </div>
</div>
<script>
function alterarQtd(btn, delta) {
    const input = btn.parentNode.querySelector('input[name="quantidade"]');
    let val = parseInt(input.value) || 1;
    val = Math.max(1, val + delta);
    input.value = val;
}
</script> 