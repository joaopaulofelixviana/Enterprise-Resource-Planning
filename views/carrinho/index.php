<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row gap-6">
  <div class="w-full md:w-2/3">
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
      <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 007.48 19h9.04a2 2 0 001.83-1.3L21 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7"/></svg>
        <h2 class="text-xl font-bold text-gray-800">Itens no Carrinho</h2>
      </div>
      <?php if (!empty($carrinho)): ?>
      <form method="post" action="?controller=carrinho&action=atualizar">
        <div class="overflow-x-auto">
          <table class="min-w-full text-sm">
            <thead>
              <tr class="text-gray-500 text-left">
                <th class="py-2">Produto</th>
                <th>Variação</th>
                <th>Qtd</th>
                <th>Preço</th>
                <th>Total</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($carrinho as $key => $item): ?>
              <tr class="border-b last:border-0">
                <td class="py-2 font-medium text-gray-800"><?= htmlspecialchars($item['nome']) ?></td>
                <td><?= htmlspecialchars($item['variacao']) ?></td>
                <td><input type="number" name="quantidade[<?= $key ?>]" value="<?= $item['quantidade'] ?>" min="1" class="w-16 rounded border-gray-300"></td>
                <td>R$ <?= number_format($item['preco'],2,',','.') ?></td>
                <td>R$ <?= number_format($item['preco'] * $item['quantidade'],2,',','.') ?></td>
                <td><a href="?controller=carrinho&action=remover&key=<?= urlencode($key) ?>" class="text-red-600 hover:underline">Remover</a></td>
              </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
        <div class="flex gap-2 mt-4">
          <button type="submit" class="px-4 py-2 rounded bg-primary text-white font-semibold hover:bg-blue-700 transition">Atualizar Carrinho</button>
          <a href="?controller=carrinho&action=limpar" class="px-4 py-2 rounded bg-gray-200 text-gray-700 font-semibold hover:bg-gray-300 transition">Limpar Carrinho</a>
        </div>
      </form>
      <?php else: ?>
        <div class="text-gray-400 text-lg text-center py-8">Carrinho vazio</div>
      <?php endif; ?>
    </div>
  </div>
  <div class="w-full md:w-1/3">
    <div class="bg-white rounded-2xl shadow p-6">
      <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v3a2 2 0 002 2zm0 0v3a2 2 0 01-2 2H7a2 2 0 01-2-2v-3"/></svg>
        <h2 class="text-xl font-bold text-gray-800">Resumo do Pedido</h2>
      </div>
      <?php
      $subtotal = 0;
      foreach ($carrinho as $item) {
        $subtotal += $item['preco'] * $item['quantidade'];
      }
      if ($subtotal > 200) {
        $frete = 0;
      } elseif ($subtotal >= 52 && $subtotal <= 166.59) {
        $frete = 15;
      } elseif ($subtotal > 0) {
        $frete = 20;
      } else {
        $frete = 0;
      }
      $total = $subtotal + $frete;
      ?>
      <form method="post" action="?controller=pedido&action=finalizar" id="formFinalizarPedido" class="space-y-3">
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">E-mail para Recebimento</label>
          <input type="email" name="email" class="w-full rounded border-gray-300" placeholder="seu@email.com" required>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">CEP para Entrega</label>
          <div class="flex gap-2">
            <input type="text" name="cep" id="cep" class="flex-1 rounded border-gray-300" placeholder="00000-000" required maxlength="9">
            <button type="button" class="rounded bg-gray-100 px-3" onclick="buscarEndereco()">
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M11 19a8 8 0 100-16 8 8 0 000 16z"/></svg>
            </button>
          </div>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Endereço</label>
          <textarea name="endereco" id="endereco" class="w-full rounded border-gray-300" rows="2" placeholder="Endereço de entrega"></textarea>
        </div>
        <div>
          <label class="block text-sm font-semibold text-gray-700 mb-1">Cupom de Desconto</label>
          <input type="text" name="cupom" class="w-full rounded border-gray-300" placeholder="Digite o cupom">
        </div>
        <div class="flex justify-between text-sm mt-2">
          <span>Subtotal:</span>
          <span>R$ <?= number_format($subtotal,2,',','.') ?></span>
        </div>
        <div class="flex justify-between text-sm">
          <span>Frete:</span>
          <span><?= $frete == 0 ? 'GRÁTIS' : 'R$ '.number_format($frete,2,',','.') ?></span>
        </div>
        <div class="flex justify-between items-center text-lg font-bold border-t pt-2 mt-2">
          <span>Total:</span>
          <span>R$ <?= number_format($total,2,',','.') ?></span>
        </div>
        <button type="submit" class="w-full flex items-center justify-center gap-2 py-2 rounded-lg bg-accent text-white font-semibold text-base hover:bg-green-600 transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v3a2 2 0 002 2zm0 0v3a2 2 0 01-2 2H7a2 2 0 01-2-2v-3"/></svg>
          Finalizar Pedido
        </button>
      </form>
      <script>
      function buscarEndereco() {
        var cep = document.getElementById('cep').value.replace(/\D/g, '');
        if (cep.length !== 8) {
          alert('CEP inválido!');
          return;
        }
        fetch('https://viacep.com.br/ws/' + cep + '/json/')
          .then(response => response.json())
          .then(data => {
            if (data.erro) {
              alert('CEP não encontrado!');
              document.getElementById('endereco').value = '';
            } else {
              document.getElementById('endereco').value = data.logradouro + ', ' + data.bairro + ', ' + data.localidade + ' - ' + data.uf;
            }
          })
          .catch(() => {
            alert('Erro ao consultar o CEP!');
            document.getElementById('endereco').value = '';
          });
      }
      </script>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?> 