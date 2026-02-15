@extends('layouts.app')

@section('title', 'Tambah Pembelian')

@section('content')
<div class="bg-white p-6 rounded-lg shadow max-w-5xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Tambah Transaksi Pembelian</h1>

    <form action="/pembelian" method="POST" id="formPembelian">
        @csrf
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div>
                <label class="block text-slate-700 font-semibold mb-2">No Faktur</label>
                <input type="text" name="no_faktur" value="{{ old('no_faktur', $noFaktur) }}" readonly 
                    class="w-full px-4 py-2 border border-slate-300 rounded bg-slate-100">
            </div>

            <div>
                <label class="block text-slate-700 font-semibold mb-2">Supplier</label>
                <select name="supplier_id" id="supplierSelect" required 
                    class="w-full px-4 py-2 border border-slate-300 rounded focus:outline-none focus:border-blue-500">
                    <option value="">Pilih Supplier</option>
                    @foreach($suppliers as $supplier)
                    <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                        {{ $supplier->nama_supplier }}
                    </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-slate-700 font-semibold mb-2">Tanggal</label>
                <input type="date" name="tanggal_pembelian" value="{{ old('tanggal_pembelian', date('Y-m-d')) }}" required 
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
                            <option value="{{ $obat->id }}" data-harga="{{ $obat->harga_beli }}" data-supplier-ids="{{ $obat->suppliers->pluck('id')->implode(',') }}">
                                {{ $obat->nama_obat }} ({{ $obat->relasionalObat->nama_kategori }})
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-span-2">
                        <label class="block text-slate-700 font-semibold mb-2">Jumlah</label>
                        <input type="number" name="jumlah[]" required min="1" value="1" class="jumlah-input w-full px-4 py-2 border border-slate-300 rounded">
                    </div>
                    <div class="col-span-2">
                        <label class="block text-slate-700 font-semibold mb-2">Harga Beli</label>
                        <input type="number" name="harga_beli[]" required min="0" step="0.01" readonly 
                            class="harga-input w-full px-4 py-2 border border-slate-300 rounded bg-slate-100">
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
                <a href="/pembelian" class="bg-slate-300 text-slate-700 px-6 py-2 rounded hover:bg-slate-400">Batal</a>
            </div>
        </div>
    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const container = document.getElementById('detailContainer');
    const supplierSelect = document.getElementById('supplierSelect');
    
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
    
    function filterObatOptions(supplierId) {
        document.querySelectorAll('.obat-select').forEach((select) => {
            const options = Array.from(select.options);
            options.forEach((option) => {
                if (!option.value) {
                    option.hidden = false;
                    option.disabled = false;
                    return;
                }
                const optionSuppliers = option.dataset.supplierIds || '';
                const supplierList = optionSuppliers === '' ? [] : optionSuppliers.split(',');
                const match = !supplierId || supplierList.includes(String(supplierId));
                option.hidden = !match;
                option.disabled = !match;
            });

            const selectedOption = select.options[select.selectedIndex];
            if (selectedOption && selectedOption.value && selectedOption.disabled) {
                select.value = '';
                const row = select.closest('.detail-row');
                row.querySelector('.harga-input').value = '';
                row.querySelector('.subtotal-display').value = '';
            }
        });

        calculateTotals();
    }

    supplierSelect.addEventListener('change', function() {
        filterObatOptions(this.value);
    });

    // Event delegation for obat selection
    container.addEventListener('change', function(e) {
        if (e.target.classList.contains('obat-select')) {
            const selectedOption = e.target.options[e.target.selectedIndex];
            const harga = selectedOption.dataset.harga || 0;
            const row = e.target.closest('.detail-row');
            row.querySelector('.harga-input').value = harga;
            calculateTotals();
        }
    });
    
    // Event delegation for input changes
    container.addEventListener('input', function(e) {
        if (e.target.classList.contains('jumlah-input')) {
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
        filterObatOptions(supplierSelect.value);
        calculateTotals();
    });
    
    // Initial filter and calculation
    filterObatOptions(supplierSelect.value);
    calculateTotals();
});
</script>
@endsection
