<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row gap-6">
  <div class="w-full md:w-2/3">
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
      <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 10c-4.41 0-8-1.79-8-4V6c0-2.21 3.59-4 8-4s8 1.79 8 4v8c0 2.21-3.59 4-8 4z"/></svg>
        <h2 class="text-xl font-bold text-gray-800">Criar Cupom</h2>
      </div>
      <form method="post" action="?controller=cupom&action=salvar" class="space-y-3">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Código do Cupom</label>
          <input type="text" name="codigo" class="w-full rounded border-gray-300" placeholder="Ex: DESCONTO10" required>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Tipo de Desconto</label>
          <select name="tipo" class="w-full rounded border-gray-300">
            <option value="percentual">Percentual (%)</option>
            <option value="valor">Valor (R$)</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Valor do Desconto</label>
          <input type="number" name="valor" class="w-full rounded border-gray-300" required step="0.01" value="0">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Valor Mínimo do Pedido</label>
          <input type="number" name="minimo" class="w-full rounded border-gray-300" required step="0.01" value="0">
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Válido até</label>
          <input type="date" name="valido_ate" class="w-full rounded border-gray-300">
        </div>
        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg bg-primary text-white font-semibold text-base hover:bg-blue-700 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 10c-4.41 0-8-1.79-8-4V6c0-2.21 3.59-4 8-4s8 1.79 8 4v8c0 2.21-3.59 4-8 4z"/></svg>
          Criar Cupom
        </button>
      </form>
    </div>
    <div class="bg-white rounded-2xl shadow p-6">
      <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 01-8 0"/></svg>
        <h2 class="text-xl font-bold text-gray-800">Cupons Disponíveis</h2>
      </div>
      <?php foreach ($cupons as $cupom): ?>
        <div class="flex items-center justify-between p-3 mb-2 rounded-lg border border-gray-200">
          <div>
            <div class="font-semibold text-gray-800"><?= htmlspecialchars($cupom['codigo']) ?></div>
            <div class="text-sm text-gray-500">Desconto: <?= $cupom['desconto_percentual'] ? $cupom['desconto_percentual'].'%' : 'R$ '.number_format($cupom['desconto_valor'],2,',','.') ?></div>
            <div class="text-xs text-gray-400">Pedido mínimo: R$ <?= number_format($cupom['valor_minimo_pedido'],2,',','.') ?> | Válido até: <?= $cupom['valido_ate'] ?></div>
          </div>
          <a href="?controller=cupom&action=excluir&id=<?= $cupom['id'] ?>" class="px-3 py-1 rounded bg-red-500 text-white font-semibold hover:bg-red-600 transition">Excluir</a>
        </div>
      <?php endforeach; ?>
      <?php if (empty($cupons)): ?>
        <div class="text-gray-400 text-lg text-center py-8">Nenhum cupom cadastrado.</div>
      <?php endif; ?>
    </div>
  </div>
  <div class="w-full md:w-1/3">
    <div class="bg-white rounded-2xl shadow p-6">
      <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v3a2 2 0 002 2zm0 0v3a2 2 0 01-2 2H7a2 2 0 01-2-2v-3"/></svg>
        <h2 class="text-xl font-bold text-gray-800">Resumo do Pedido</h2>
      </div>
      <form class="space-y-3">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">CEP para Entrega</label>
          <div class="flex gap-2">
            <input type="text" class="flex-1 rounded border-gray-300" placeholder="00000-000" disabled>
            <button type="button" class="rounded bg-gray-100 px-3 cursor-not-allowed" disabled>
              <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
            </button>
          </div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Endereço</label>
          <textarea class="w-full rounded border-gray-300" rows="2" placeholder="Endereço de entrega" disabled></textarea>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Cupom de Desconto</label>
          <input type="text" class="w-full rounded border-gray-300" placeholder="Digite o cupom" disabled>
        </div>
        <div class="flex justify-between text-sm mt-2">
          <span>Subtotal:</span>
          <span>R$ 0,00</span>
        </div>
        <div class="flex justify-between text-sm">
          <span>Frete:</span>
          <span>GRÁTIS</span>
        </div>
        <div class="flex justify-between items-center text-lg font-bold border-t pt-2 mt-2">
          <span>Total:</span>
          <span>R$ 0,00</span>
        </div>
        <button type="button" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg bg-accent text-white font-semibold text-base opacity-60 cursor-not-allowed">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v3a2 2 0 002 2zm0 0v3a2 2 0 01-2 2H7a2 2 0 01-2-2v-3"/></svg>
          Finalizar Pedido
        </button>
      </form>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?> 