<form id="formProduto" method="post" action="?controller=produto&action=salvar" class="space-y-4">
    <input type="hidden" name="id" value="<?= isset($produto['id']) ? $produto['id'] : '' ?>">
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Nome do Produto</label>
        <input type="text" name="nome" class="w-full rounded border-gray-300 px-3 py-2" required placeholder="Digite o nome do produto" value="<?= isset($produto['nome']) ? htmlspecialchars($produto['nome']) : '' ?>">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Preço (R$)</label>
        <input type="number" step="0.01" name="preco" class="w-full rounded border-gray-300 px-3 py-2" required placeholder="0.00" value="<?= isset($produto['preco']) ? htmlspecialchars($produto['preco']) : '' ?>">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Estoque Principal</label>
        <input type="number" name="estoque" class="w-full rounded border-gray-300 px-3 py-2" required placeholder="Quantidade em estoque" value="<?php
            if (isset($produto['variacoes'])) {
                foreach ($produto['variacoes'] as $v) {
                    if ($v['variacao'] == 'Principal') echo $v['quantidade'];
                }
            }
        ?>">
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Descrição</label>
        <textarea name="descricao" class="w-full rounded border-gray-300 px-3 py-2" rows="3" placeholder="Descrição do produto"><?php if(isset($produto['descricao'])) echo htmlspecialchars($produto['descricao']); ?></textarea>
    </div>
    <div>
        <label class="block text-sm font-semibold text-gray-700 mb-1">Variações</label>
        <div id="variacoes" class="space-y-2">
            <?php if(isset($produto['variacoes'])): ?>
                <?php foreach ($produto['variacoes'] as $v): ?>
                    <?php if($v['variacao'] != 'Principal'): ?>
                        <div class="flex gap-2">
                            <input type="text" name="variacoes[]" class="flex-1 rounded border-gray-300 px-3 py-2" value="<?= htmlspecialchars($v['variacao']) ?>" required>
                            <button type="button" class="px-3 py-2 rounded bg-red-100 text-red-600 hover:bg-red-200" onclick="this.parentNode.remove()">&times;</button>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <button type="button" class="mt-2 px-3 py-2 rounded bg-primary text-white font-semibold hover:bg-blue-700 transition" onclick="adicionarVariacao()">
            <svg class="w-4 h-4 inline-block mr-1" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
            Adicionar Variação
        </button>
    </div>
    <div class="flex gap-2 mt-4">
        <button type="submit" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-primary text-white font-semibold hover:bg-blue-700 transition">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Salvar Produto
        </button>
        <button type="reset" class="flex-1 flex items-center justify-center gap-2 px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-semibold hover:bg-gray-200 transition" onclick="window.location='?controller=produto'">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 4v16h16V4H4zm4 8h8"/></svg>
            Limpar Formulário
        </button>
    </div>
</form>
<script>
function adicionarVariacao() {
    const div = document.createElement('div');
    div.className = 'flex gap-2';
    div.innerHTML = `<input type=\"text\" name=\"variacoes[]\" class=\"flex-1 rounded border-gray-300 px-3 py-2\" placeholder=\"Ex: Tamanho P\" required>\n<button type=\"button\" class=\"px-3 py-2 rounded bg-red-100 text-red-600 hover:bg-red-200\" onclick=\"this.parentNode.remove()\">&times;</button>`;
    document.getElementById('variacoes').appendChild(div);
}
</script> 