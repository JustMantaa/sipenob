<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'SIPENOB - Sistem Penjualan Obat')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-100">
    @auth
    <div class="flex h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-blue-700 text-white shadow-lg flex flex-col fixed h-screen">
            <!-- Header Sidebar -->
            <div class="p-6 border-b border-blue-600">
                <div class="text-2xl font-bold">SIPENOB</div>
                <p class="text-blue-200 text-sm">Sistem Penjualan Obat</p>
            </div>

            <!-- User Info -->
            <div class="px-6 py-4 border-b border-blue-600">
                <p class="text-sm text-blue-200">Pengguna:</p>
                <p class="font-semibold">{{ auth()->user()->name }}</p>
                <p class="text-xs text-blue-300 capitalize">{{ auth()->user()->role }}</p>
            </div>

            <!-- Navigation Menu -->
            <nav class="flex-1 px-4 py-6 overflow-y-auto">
                <ul class="space-y-2">
                    <!-- Dashboard -->
                    <li>
                        <a href="/dashboard" class="block px-4 py-2 rounded hover:bg-blue-600 transition">
                            Dashboard
                        </a>
                    </li>

                    <!-- Master Data (Admin Only) -->
                    @if(auth()->user()->isAdmin())
                    <li>
                        <div class="text-xs font-semibold text-blue-300 px-4 py-2 mt-4 mb-2">MASTER DATA</div>
                        <ul class="space-y-1">
                            <li>
                                <a href="/relasional-obat" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Kategori Obat
                                </a>
                            </li>
                            <li>
                                <a href="/obat" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Data Obat
                                </a>
                            </li>
                            <li>
                                <a href="/supplier" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Supplier
                                </a>
                            </li>
                            <li>
                                <a href="/pelanggan" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Pelanggan
                                </a>
                            </li>
                            <li>
                                <a href="/user" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Petugas
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    @if(auth()->user()->isPetugas())
                    <li>
                        <div class="text-xs font-semibold text-blue-300 px-4 py-2 mt-4 mb-2">DATA</div>
                        <ul class="space-y-1">
                            <li>
                                <a href="/relasional-obat" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Kategori Obat
                                </a>
                            </li>
                            <li>
                                <a href="/obat" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Data Obat
                                </a>
                            </li>
                            <li>
                                <a href="/supplier" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Supplier
                                </a>
                            </li>
                            <li>
                                <a href="/pelanggan" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Pelanggan
                                </a>
                            </li>
                        </ul>
                    </li>
                    @endif

                    <!-- Transaksi -->
                    <li>
                        <div class="text-xs font-semibold text-blue-300 px-4 py-2 mt-4 mb-2">TRANSAKSI</div>
                        <ul class="space-y-1">
                            <li>
                                <a href="/pembelian" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Pembelian
                                </a>
                            </li>
                            <li>
                                <a href="/penjualan" class="block px-4 py-2 rounded hover:bg-blue-600 transition text-sm">
                                    Penjualan
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            </nav>

            <!-- Logout Button -->
            <div class="p-6 border-t border-blue-600">
                <form action="/logout" method="POST">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700 transition font-semibold">
                        Logout
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 flex flex-col">
            <!-- Top Bar -->
            <div class="bg-white border-b border-slate-200 px-8 py-4 shadow-sm">
                <h1 class="text-2xl font-bold text-slate-800">@yield('title', 'Dashboard')</h1>
            </div>

            <!-- Content Area -->
            <div class="flex-1 overflow-auto">
                <div class="px-8 py-6">
                    @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                        {{ session('success') }}
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                        {{ session('error') }}
                    </div>
                    @endif

                    @yield('content')
                </div>
            </div>
        </main>
    </div>
    @endauth

    @guest
    <main class="flex items-center justify-center min-h-screen">
        @yield('content')
    </main>
    @endguest

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tables = document.querySelectorAll('table');
            tables.forEach((table) => {
                if (table.dataset.searchReady === 'true') {
                    return;
                }
                if (table.getAttribute('data-search') === 'false') {
                    return;
                }

                table.dataset.searchReady = 'true';

                const searchWrapper = document.createElement('div');
                searchWrapper.className = 'flex items-center justify-end mb-3';

                const searchInput = document.createElement('input');
                searchInput.type = 'search';
                searchInput.placeholder = 'Cari di tabel...';
                searchInput.className = 'border border-slate-300 rounded px-3 py-2 text-sm w-full sm:w-64';

                searchWrapper.appendChild(searchInput);
                table.parentNode.insertBefore(searchWrapper, table);

                const getRows = () => {
                    const bodyRows = Array.from(table.querySelectorAll('tbody tr'));
                    if (bodyRows.length > 0) {
                        return bodyRows;
                    }
                    return Array.from(table.querySelectorAll('tr')).filter((row) => {
                        return row.closest('thead') === null && row.closest('tfoot') === null;
                    });
                };

                const rows = getRows();

                searchInput.addEventListener('input', () => {
                    const term = searchInput.value.toLowerCase().trim();
                    rows.forEach((row) => {
                        const rowText = row.textContent.toLowerCase();
                        row.style.display = term === '' || rowText.includes(term) ? '' : 'none';
                    });
                });
            });
        });
    </script>
</body>
</html>
