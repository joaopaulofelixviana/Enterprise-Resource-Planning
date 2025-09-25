<?php include __DIR__ . '/../layouts/header.php'; ?>
<div class="flex flex-col md:flex-row gap-6">
  <div class="w-full md:w-1/2">
    <div class="bg-white rounded-2xl shadow p-6 mb-6">
      <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
        <h2 class="text-xl font-bold text-gray-800">Cadastrar/Editar Produto</h2>
      </div>
      <?php include __DIR__ . '/form.php'; ?>
    </div>
  </div>
  <div class="w-full md:w-1/2 flex flex-col gap-4">
    <div class="bg-white rounded-2xl shadow p-6 flex flex-col gap-2">
      <div class="flex items-center gap-2 mb-4">
        <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
        <h2 class="text-xl font-bold text-black">Produtos Cadastrados</h2>
      </div>
      <?php include __DIR__ . '/lista.php'; ?>
    </div>
  </div>
</div>
<?php include __DIR__ . '/../layouts/footer.php'; ?> 