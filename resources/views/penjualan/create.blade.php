@extends('layouts.app')

@section('title', 'Tambah Penjualan')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Tambah Transaksi Penjualan</h1>

    <form action="/penjualan" method="POST" id="formPenjualan">
        @csrf
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-slate-700 font-semibold mb-2">No Nota</label>
                <input type="text" name="no_nota" value="{{ old('no_nota', $noNota) }}" readonly 
                    class="w-full px-4 py-2 border border-slate-300 rounded bg-slate-100">
            </div>

            <div>
                <label class="block text-slate-700 font-semibold mb-2">Pelanggan (Opsional)</label>
                <div class="flex gap-2">
                    <select name="pelanggan_id" id="pelangganSelect"
                        class="flex-1 px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
                        <option value="">Pelanggan Umum</option>
                        @foreach($pelanggans as $pelanggan)
                        <option value="{{ $pelanggan->id }}" {{ old('pelanggan_id') == $pelanggan->id ? 'selected' : '' }}>
                            {{ $pelanggan->nama_pelanggan }}
                        </option>
                        @endforeach
                    </select>
                    <button type="button" id="openModalBtn" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                        + Baru
                    </button>
                </div>
            </div>

            <div>
                <label class="block text-slate-700 font-semibold mb-2">Tanggal</label>
                <input type="date" name="tanggal_penjualan" value="{{ old('tanggal_penjualan', date('Y-m-d')) }}" required 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
            </div>
        </div>

        <h3 class="text-lg font-bold mb-3">Detail Obat</h3>
        <div id="detailContainer">
            <div class="border border-slate-300 rounded p-4 mb-4 detail-row">
                <div class="grid grid-cols-12 gap-2">
                    <div class="col-span-5">
                        <label class="block text-slate-700 font-semibold mb-2">Obat</label>
                        <select name="obat_id[]" required class="obat-select w-full px-4 py-2 border border-slate-300 rounded">
                            <option value="">Pilih Obat</option>
                            @foreach($obats as $obat)
                            <option value="{{ $obat->id }}" data-harga="{{ $obat->harga_jual }}" data-stok="{{ $obat->stok }}">
                                {{ $obat->nama_obat }} (Stok: {{ $obat->stok }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-slate-700 font-semibold mb-2">Jumlah</label>
                        <input type="number" name="jumlah[]" required min="1" value="1" class="jumlah-input w-full px-4 py-2 border border-slate-300 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-slate-700 font-semibold mb-2">Harga Jual</label>
                        <input type="number" name="harga_jual[]" required min="0" step="0.01" class="harga-input w-full px-4 py-2 border border-slate-300 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-slate-700 font-semibold mb-2">Subtotal</label>
                        <input type="text" readonly class="subtotal-display w-full px-4 py-2 border border-slate-300 rounded bg-slate-100">
                    </div>
                    <div class="col-span-1 flex items-end">
                        <button type="button" class="remove-row bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">✕</button>
                    </div>
                </div>
            </div>
        </div>

        <button type="button" id="addRow" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 mb-4">+ Tambah Obat</button>

        <div class="border-t pt-4">
            <div class="flex justify-end mb-4">
                <div class="text-right">
                    <span class="text-lg font-bold">Total: Rp <span id="grandTotal">0</span></span>
                </div>
            </div>

            <div class="flex space-x-2">
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">Simpan</button>
                <a href="/penjualan" class="bg-slate-300 text-slate-700 px-6 py-2 rounded hover:bg-slate-400">Batal</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('detailContainer');
    
    // Calculate subtotal and grand total
    function calculateTotals() {
        let grandTotal = 0;
        
        document.querySelectorAll('.detail-row').forEach(row => {
            const jumlah = parseFloat(row.querySelector('.jumlah-input').value) || 0;
            const harga = parseFloat(row.querySelector('.harga-input').value) || 0;
            const subtotal = jumlah * harga;
            
            row.querySelector('.subtotal-display').value = subtotal.toLocaleString('id-ID');
            grandTotal += subtotal;
        });
        
        document.getElementById('grandTotal').textContent = grandTotal.toLocaleString('id-ID');
    }
    
    // Event delegation for obat selection
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('obat-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const harga = selectedOption.dataset.harga || 0;
            const stok = selectedOption.dataset.stok || 0;
            const row = e.target.closest('.detail-row');
            
            row.querySelector('.harga-input').value = harga;
            row.querySelector('.jumlah-input').max = stok;
            
            calculateTotals();
        }
    });
    
    // Event delegation for input changes
    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('jumlah-input')) {
            const row = e.target.closest('.detail-row');
            const select = row.querySelector('.obat-select');
            const selectedOption = select.options[select.selectedIndex];
            const stok = parseInt(selectedOption.dataset.stok) || 0;
            const jumlah = parseInt(e.target.value) || 0;
            
            if (jumlah > stok) {
                alert(`Stok tidak mencukupi! Tersedia: ${stok}`);
                e.target.value = stok;
            }
            calculateTotals();
        }
        
        if (e.target.classList.contains('harga-input')) {
            calculateTotals();
        }
    });
    
    // Event delegation for remove button
    container.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-row')) {
            const rows = document.querySelectorAll('.detail-row');
            if (rows.length > 1) {
                e.target.closest('.detail-row').remove();
                calculateTotals();
            } else {
                alert('Minimal harus ada 1 item');
            }
        }
    });
    
    // Add new row
    document.getElementById('addRow').addEventListener('click', function() {
        const firstRow = document.querySelector('.detail-row');
        const newRow = firstRow.cloneNode(true);
        
        // Reset values
        newRow.querySelector('.obat-select').value = '';
        newRow.querySelector('.jumlah-input').value = '1';
        newRow.querySelector('.harga-input').value = '';
        newRow.querySelector('.subtotal-display').value = '';
        
        container.appendChild(newRow);
        calculateTotals();
    });
    
    // Modal Pelanggan Baru
    const modal = document.getElementById('pelangganModal');
    const openModalBtn = document.getElementById('openModalBtn');
    const closeModalBtn = document.getElementById('closeModalBtn');
    const formPelangganBaru = document.getElementById('formPelangganBaru');
    const pelangganSelect = document.getElementById('pelangganSelect');

    if (openModalBtn) {
        openModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            console.log('Modal button clicked');
            if (modal) {
                modal.showModal();
            }
        });
    }

    if (closeModalBtn) {
        closeModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            if (modal) {
                modal.close();
            }
        });
    }

    if (formPelangganBaru) {
        formPelangganBaru.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nama = document.getElementById('namaPelangganBaru').value;
            const alamat = document.getElementById('alamatPelangganBaru').value;
            const telepon = document.getElementById('teleponPelangganBaru').value;
            
            fetch('/pelanggan/store-ajax', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({
                    nama_pelanggan: nama,
                    alamat: alamat,
                    telepon: telepon
                })
            })
            .then(response => response.json().then(data => ({ status: response.status, data })))
            .then(({ status, data }) => {
                if (status === 200 && data.success) {
                    // Add new option to select
                    const newOption = document.createElement('option');
                    newOption.value = data.pelanggan.id;
                    newOption.textContent = data.pelanggan.nama_pelanggan;
                    newOption.selected = true;
                    pelangganSelect.appendChild(newOption);
                    
                    // Reset form
                    formPelangganBaru.reset();
                    modal.close();
                    alert('Pelanggan berhasil ditambahkan!');
                } else if (status === 422) {
                    // Validation error
                    let errorMsg = 'Validasi gagal:\n';
                    for (const field in data.errors) {
                        errorMsg += '- ' + data.errors[field][0] + '\n';
                    }
                    alert(errorMsg);
                } else {
                    alert('Error: ' + (data.message || 'Gagal menambahkan pelanggan'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Gagal menambahkan pelanggan');
            });
        });
    }

    // Initial calculation
    calculateTotals();
});
</script>

<!-- Modal Pelanggan Baru -->
<dialog id="pelangganModal" class="rounded-lg shadow-lg p-6 w-96">
    <h2 class="text-xl font-bold mb-4">Tambah Pelanggan Baru</h2>
    
    <form id="formPelangganBaru">
        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Nama Pelanggan</label>
            <input type="text" id="namaPelangganBaru" required
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
        </div>
        
        <div class="mb-4">
            <label class="block text-slate-700 font-semibold mb-2">Alamat</label>
            <textarea id="alamatPelangganBaru" rows="2"
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500"></textarea>
        </div>
        
        <div class="mb-6">
            <label class="block text-slate-700 font-semibold mb-2">Telepon</label>
            <input type="text" id="teleponPelangganBaru"
                class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
        </div>
        
        <div class="flex justify-end gap-2">
            <button type="button" id="closeModalBtn" class="bg-slate-300 text-slate-700 px-4 py-2 rounded hover:bg-slate-400">
                Batal
            </button>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Simpan
            </button>
        </div>
    </form>
</dialog>

@endsection
