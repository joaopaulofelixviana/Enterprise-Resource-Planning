<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mini ERP</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
      tailwind.config = {
        theme: {
          extend: {
            fontFamily: { inter: ['Inter', 'sans-serif'] },
            colors: {
              primary: '#2563eb',
              accent: '#22c55e',
              graybg: '#f8fafc',
            }
          }
        }
      }
    </script>
</head>
<body class="bg-graybg font-inter min-h-screen">
    <header class="w-full bg-white shadow-sm sticky top-0 z-30">
      <div class="max-w-7xl mx-auto flex items-center justify-between px-4 py-3">
        <div class="flex items-center gap-2">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50" class="w-8 h-8 text-primary"><path d="M25 0C11.192 0 0 11.192 0 25s11.192 25 25 25 25-11.192 25-25S38.808 0 25 0zm0 2c12.703 0 23 10.297 23 23S37.703 48 25 48 2 37.703 2 25 12.297 2 25 2zm-8.5 13a1 1 0 0 0-.969.75l-5 19A1 1 0 0 0 11.5 36h3a1 1 0 0 0 .969-.75L19.5 22.28l5.031 13.03A1 1 0 0 0 25.5 36h2a1 1 0 0 0 .969-.75L33.5 22.28l4.031 13.03A1 1 0 0 0 38.5 36h3a1 1 0 0 0 .969-1.25l-5-19A1 1 0 0 0 36.5 14h-2a1 1 0 0 0-.969.75L28.5 27.72l-5.031-13.03A1 1 0 0 0 22.5 14h-2z"/></svg>
          <span class="text-2xl font-bold">Mini ERP</span>
        </div>
        <nav class="flex gap-2 md:gap-4">
          <a href="?controller=produto" class="flex items-center gap-1 px-4 py-2 rounded-xl font-semibold text-gray-700 hover:bg-gray-100 transition <?= ($_GET['controller'] ?? 'produto') == 'produto' ? 'bg-gray-100 ring-2 ring-primary' : '' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 7V6a2 2 0 012-2h2a2 2 0 012 2v1m10 0V6a2 2 0 00-2-2h-2a2 2 0 00-2 2v1m-6 4h18M4 21h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Produtos
          </a>
          <a href="?controller=carrinho" class="flex items-center gap-1 px-4 py-2 rounded-xl font-semibold text-gray-700 hover:bg-gray-100 transition <?= ($_GET['controller'] ?? '') == 'carrinho' ? 'bg-gray-100 ring-2 ring-primary' : '' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.35 2.7A2 2 0 007.48 19h9.04a2 2 0 001.83-1.3L21 13M7 13V6a1 1 0 011-1h5a1 1 0 011 1v7"/></svg>
            Carrinho <span class="ml-1 text-xs font-bold text-white bg-red-500 rounded-full px-2 py-0.5"><?php echo isset($_SESSION['carrinho']) ? array_sum(array_column($_SESSION['carrinho'], 'quantidade')) : 0; ?></span>
          </a>
          <a href="?controller=pedido&action=historico" class="flex items-center gap-1 px-4 py-2 rounded-xl font-semibold text-gray-700 hover:bg-gray-100 transition <?= ($_GET['controller'] ?? '') == 'pedido' ? 'bg-gray-100 ring-2 ring-primary' : '' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
            Pedidos
          </a>
          <a href="?controller=cupom" class="flex items-center gap-1 px-4 py-2 rounded-xl font-semibold text-gray-700 hover:bg-gray-100 transition <?= ($_GET['controller'] ?? '') == 'cupom' ? 'bg-gray-100 ring-2 ring-primary' : '' ?>">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m2 0a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v4a2 2 0 002 2zm0 0v4a2 2 0 01-2 2H7a2 2 0 01-2-2v-4"/></svg>
            Cupons
          </a>
        </nav>
      </div>
    </header>
    <main class="max-w-7xl mx-auto px-2 md:px-4 py-6">
        <?php if (!empty($_SESSION['msg'])): ?>
            <div class="mb-4 p-3 rounded-lg bg-green-100 text-green-800 flex items-center justify-between">
                <span><?= $_SESSION['msg']; unset($_SESSION['msg']); ?></span>
                <button onclick="this.parentNode.remove()" class="ml-4 text-lg">&times;</button>
            </div>
        <?php endif; ?>
        <?php if (!empty($_SESSION['erro'])): ?>
            <div class="mb-4 p-3 rounded-lg bg-red-100 text-red-800 flex items-center justify-between">
                <span><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></span>
                <button onclick="this.parentNode.remove()" class="ml-4 text-lg">&times;</button>
            </div>
        <?php endif; ?>
    </main> 