<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row gap-6">
  <div class="w-full md:w-2/3">
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
      <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7V6a2 2 0 012-2h2a2 2 0 012 2v1m10 0V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v1m-6 4h18M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        <h2 class="text-xl font-bold text-gray-800">Histórico de Pedidos</h2>
      </div>
      <div class="overflow-x-auto">
        <table class="min-w-full text-sm">
          <thead>
            <tr class="text-gray-500 text-left">
              <th class="py-2">ID</th>
              <th>Data</th>
              <th>Subtotal</th>
              <th>Frete</th>
              <th>Total</th>
              <th>Status</th>
              <th>Pagamento</th>
              <th>Ações</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($pedidos as $p): ?>
            <tr class="border-b last:border-0">
              <td class="py-2 font-medium text-gray-800"><?= $p['id'] ?></td>
              <td><?= $p['created_at'] ?></td>
              <td>R$ <?= number_format($p['subtotal'],2,',','.') ?></td>
              <td>R$ <?= number_format($p['frete'],2,',','.') ?></td>
              <td>R$ <?= number_format($p['total'],2,',','.') ?></td>
              <td>
                <?php if (($p['status_pagamento'] ?? 'pendente') == 'pendente'): ?>
                  <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs">Pendente</span>
                <?php elseif (($p['status_pagamento'] ?? '') == 'confirmado'): ?>
                  <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">Confirmado</span>
                <?php elseif (($p['status_pagamento'] ?? '') == 'cancelado'): ?>
                  <span class="bg-red-100 text-red-800 px-2 py-1 rounded text-xs">Cancelado</span>
                <?php endif; ?>
              </td>
              <td><?= $p['forma_pagamento'] ? ucfirst($p['forma_pagamento']) : '-' ?></td>
              <td>
                <?php if (($p['status_pagamento'] ?? 'pendente') == 'pendente'): ?>
                  <button class="px-3 py-1 rounded bg-green-500 text-white font-semibold hover:bg-green-600 transition" onclick="abrirPainelPagamento(<?= $p['id'] ?>)">Confirmar</button>
                  <a href="?controller=pedido&action=cancelar&id=<?= $p['id'] ?>" class="px-3 py-1 rounded bg-red-500 text-white font-semibold hover:bg-red-600 transition ml-1" onclick="return confirm('Cancelar este pedido?')">Cancelar</a>
                <?php elseif (($p['status_pagamento'] ?? '') == 'confirmado'): ?>
                  <span class="px-3 py-1 rounded bg-green-100 text-green-800 font-semibold">Confirmado</span>
                <?php elseif (($p['status_pagamento'] ?? '') == 'cancelado'): ?>
                  <span class="px-3 py-1 rounded bg-red-100 text-red-800 font-semibold">Cancelado</span>
                <?php endif; ?>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
      <?php if (empty($pedidos)): ?>
        <div class="text-gray-400 text-lg text-center py-8">Nenhum pedido encontrado.</div>
      <?php endif; ?>
    </div>
  </div>
  <div class="w-full md:w-1/3" id="colunaPagamento">
    <div id="cardPagamento">
      <div class="bg-white rounded-2xl shadow p-6">
        <div class="flex items-center gap-2 mb-4">
          <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
          <h2 class="text-xl font-bold text-gray-800">Formas de Pagamento</h2>
        </div>
        <div class="grid grid-cols-2 gap-4 mb-6">
          <div class="flex flex-col items-center justify-center border-2 border-gray-200 rounded-xl p-4">
            <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4"/></svg>
            <span class="font-bold text-gray-800">Pix</span>
          </div>
          <div class="flex flex-col items-center justify-center border-2 border-gray-200 rounded-xl p-4">
            <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg>
            <span class="font-bold text-gray-800">Crédito</span>
          </div>
          <div class="flex flex-col items-center justify-center border-2 border-gray-200 rounded-xl p-4">
            <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 14h20"/></svg>
            <span class="font-bold text-gray-800">Débito</span>
          </div>
          <div class="flex flex-col items-center justify-center border-2 border-gray-200 rounded-xl p-4">
            <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8v8M10 8v8M14 8v8M17 8v8"/></svg>
            <span class="font-bold text-gray-800">Boleto</span>
          </div>
        </div>
        <div class="flex items-center justify-center gap-2">
       
        </div>
      </div>
    </div>
    <div id="painelPagamento" class="hidden">
      <form method="post" action="?controller=pedido&action=confirmar" id="formPagamento" class="bg-white rounded-2xl shadow p-6 flex flex-col h-full">
        <div class="flex justify-between items-center mb-6">
          <h3 class="text-lg font-bold text-gray-800">Escolha a forma de pagamento</h3>
          <button type="button" class="text-gray-400 hover:text-gray-500" onclick="fecharPainelPagamento()">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
          </button>
        </div>
        <input type="hidden" name="id" id="pedido_id_pagamento">
        <div class="grid grid-cols-2 gap-4 mb-6">
          <label class="cursor-pointer group" onclick="selecionarPagamento('pix')">
            <input type="radio" name="forma_pagamento" value="pix" class="hidden">
            <div id="card-pix" class="flex flex-col items-center justify-center border-2 border-gray-200 rounded-xl p-4 transition group-hover:border-primary hover:shadow-md">
              <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="4" y="4" width="16" height="16" rx="4"/></svg>
              <span class="font-bold text-gray-800">Pix</span>
            </div>
          </label>
          <label class="cursor-pointer group" onclick="selecionarPagamento('credito')">
            <input type="radio" name="forma_pagamento" value="credito" class="hidden">
            <div id="card-credito" class="flex flex-col items-center justify-center border-2 border-gray-200 rounded-xl p-4 transition group-hover:border-primary hover:shadow-md">
              <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 10h20"/></svg>
              <span class="font-bold text-gray-800">Crédito</span>
            </div>
          </label>
          <label class="cursor-pointer group" onclick="selecionarPagamento('debito')">
            <input type="radio" name="forma_pagamento" value="debito" class="hidden">
            <div id="card-debito" class="flex flex-col items-center justify-center border-2 border-gray-200 rounded-xl p-4 transition group-hover:border-primary hover:shadow-md">
              <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="2" y="6" width="20" height="12" rx="2"/><path d="M2 14h20"/></svg>
              <span class="font-bold text-gray-800">Débito</span>
            </div>
          </label>
          <label class="cursor-pointer group" onclick="selecionarPagamento('boleto')">
            <input type="radio" name="forma_pagamento" value="boleto" class="hidden">
            <div id="card-boleto" class="flex flex-col items-center justify-center border-2 border-gray-200 rounded-xl p-4 transition group-hover:border-primary hover:shadow-md">
              <svg class="w-10 h-10 mb-2 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="16" rx="2"/><path d="M7 8v8M10 8v8M14 8v8M17 8v8"/></svg>
              <span class="font-bold text-gray-800">Boleto</span>
            </div>
          </label>
        </div>
        <div class="flex items-center justify-center gap-2 mb-6">
          
        </div>
        <div class="flex gap-3">
          <button type="submit" id="btnConfirmarPagamento" class="flex-1 py-3 px-4 rounded-xl bg-green-500 text-white font-semibold hover:bg-green-600 transition disabled:opacity-50 disabled:cursor-not-allowed" disabled>
            Confirmar
          </button>
          <button type="button" class="flex-1 py-3 px-4 rounded-xl bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition" onclick="fecharPainelPagamento()">
            Cancelar
          </button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function abrirPainelPagamento(id) {
    document.getElementById('pedido_id_pagamento').value = id;
    // Limpa seleção anterior
    document.querySelectorAll('input[name=forma_pagamento]').forEach(e => e.checked = false);
    document.querySelectorAll('[id^=card-]').forEach(e => e.classList.remove('border-primary', 'ring-2', 'ring-primary'));
    document.getElementById('btnConfirmarPagamento').disabled = true;
    document.getElementById('cardPagamento').classList.add('hidden');
    document.getElementById('painelPagamento').classList.remove('hidden');
}

function fecharPainelPagamento() {
    document.getElementById('painelPagamento').classList.add('hidden');
    document.getElementById('cardPagamento').classList.remove('hidden');
}

function selecionarPagamento(tipo) {
  document.querySelectorAll('input[name=forma_pagamento]').forEach(e => e.checked = false);
  document.querySelectorAll('[id^=card-]').forEach(e => e.classList.remove('border-primary', 'ring-2', 'ring-primary'));
  document.querySelector('input[name=forma_pagamento][value='+tipo+']').checked = true;
  document.getElementById('card-'+tipo).classList.add('border-primary', 'ring-2', 'ring-primary');
  document.getElementById('btnConfirmarPagamento').disabled = false;
}
</script>
<?php include __DIR__ . '/../layouts/footer.php'; ?> 