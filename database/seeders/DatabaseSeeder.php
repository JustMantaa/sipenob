<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\RelasionalObat;
use App\Models\Supplier;
use App\Models\Pelanggan;
use App\Models\Obat;
use App\Models\Pembelian;
use App\Models\PembelianDetail;
use App\Models\Penjualan;
use App\Models\PenjualanDetail;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat users default
        User::updateOrCreate(
            ['email' => 'admin@sipenob.test'],
            [
                'name' => 'Administrator',
                'role' => 'admin',
                'password' => Hash::make('password'),
            ]
        );

        User::updateOrCreate(
            ['email' => 'petugas@sipenob.test'],
            [
                'name' => 'Petugas',
                'role' => 'petugas',
                'password' => Hash::make('password'),
            ]
        );

        for ($i = 1; $i <= 10; $i++) {
            $role = $i % 2 === 0 ? 'admin' : 'petugas';

            User::updateOrCreate(
                ['email' => "user{$i}@sipenob.test"],
                [
                    'name' => "User {$i}",
                    'role' => $role,
                    'password' => Hash::make('password'),
                ]
            );
        }

        // Buat kategori obat default
        $kategoris = [
            ['nama' => 'Antibiotik', 'keterangan' => 'Obat untuk infeksi bakteri'],
            ['nama' => 'Analgesik', 'keterangan' => 'Obat pereda nyeri'],
            ['nama' => 'Antipiretik', 'keterangan' => 'Obat penurun panas'],
            ['nama' => 'Vitamin', 'keterangan' => 'Suplemen vitamin dan mineral'],
            ['nama' => 'Obat Batuk & Flu', 'keterangan' => 'Obat untuk batuk dan flu'],
            ['nama' => 'Antiseptik', 'keterangan' => 'Obat pembersih luka ringan'],
            ['nama' => 'Antihistamin', 'keterangan' => 'Obat untuk alergi'],
            ['nama' => 'Antasida', 'keterangan' => 'Obat untuk asam lambung'],
            ['nama' => 'Antivirus', 'keterangan' => 'Obat untuk infeksi virus'],
            ['nama' => 'Obat Kulit', 'keterangan' => 'Obat perawatan kulit'],
        ];

        foreach ($kategoris as $kategori) {
            RelasionalObat::updateOrCreate(
                ['nama_kategori' => $kategori['nama']],
                ['keterangan' => $kategori['keterangan']]
            );
        }

        for ($i = 1; $i <= 10; $i++) {
            Supplier::updateOrCreate(
                ['nama_supplier' => "Supplier {$i}"],
                [
                    'alamat' => "Jl. Merdeka No. {$i}",
                    'telepon' => sprintf('0812%07d', $i),
                    'email' => "supplier{$i}@sipenob.test",
                ]
            );

            Pelanggan::updateOrCreate(
                ['nama_pelanggan' => "Pelanggan {$i}"],
                [
                    'alamat' => "Jl. Mawar No. {$i}",
                    'telepon' => sprintf('0821%07d', $i),
                ]
            );
        }

        $kategoriIds = RelasionalObat::orderBy('id')->pluck('id');

        for ($i = 1; $i <= 10; $i++) {
            if ($kategoriIds->isEmpty()) {
                break;
            }

            $hargaBeli = 1000 + ($i * 100);
            $hargaJual = $hargaBeli + 500;
            $kategoriId = $kategoriIds[($i - 1) % $kategoriIds->count()];

            Obat::updateOrCreate(
                ['kode_obat' => sprintf('OBT-%04d', $i)],
                [
                    'relasional_obat_id' => $kategoriId,
                    'nama_obat' => "Obat {$i}",
                    'deskripsi' => "Deskripsi obat {$i}",
                    'stok' => 50 + $i,
                    'harga_beli' => $hargaBeli,
                    'harga_jual' => $hargaJual,
                    'tanggal_kadaluarsa' => now()->addMonths(6 + $i)->toDateString(),
                ]
            );
        }

        $supplierIds = Supplier::orderBy('id')->pluck('id');
        $userIds = User::orderBy('id')->pluck('id');

        for ($i = 1; $i <= 10; $i++) {
            if ($supplierIds->isEmpty() || $userIds->isEmpty()) {
                break;
            }

            $supplierId = $supplierIds[($i - 1) % $supplierIds->count()];
            $userId = $userIds[($i - 1) % $userIds->count()];

            Pembelian::updateOrCreate(
                ['no_faktur' => sprintf('FKT-%04d', $i)],
                [
                    'supplier_id' => $supplierId,
                    'user_id' => $userId,
                    'tanggal_pembelian' => now()->subDays(20 - $i)->toDateString(),
                    'total_pembelian' => 0,
                ]
            );
        }

        $pembelians = Pembelian::orderBy('id')->take(10)->get();
        $obats = Obat::orderBy('id')->take(10)->get();

        foreach ($pembelians as $index => $pembelian) {
            if ($obats->isEmpty()) {
                break;
            }

            $obat = $obats[$index % $obats->count()];
            $jumlah = 2 + $index;
            $hargaBeli = (float) $obat->harga_beli;
            $subtotal = round($hargaBeli * $jumlah, 2);

            PembelianDetail::updateOrCreate(
                [
                    'pembelian_id' => $pembelian->id,
                    'obat_id' => $obat->id,
                ],
                [
                    'jumlah' => $jumlah,
                    'harga_beli' => $hargaBeli,
                    'subtotal' => $subtotal,
                ]
            );
        }

        foreach ($pembelians as $pembelian) {
            $total = PembelianDetail::where('pembelian_id', $pembelian->id)
                ->sum('subtotal');

            $pembelian->update(['total_pembelian' => $total]);
        }

        $pelangganIds = Pelanggan::orderBy('id')->pluck('id');

        for ($i = 1; $i <= 10; $i++) {
            if ($userIds->isEmpty()) {
                break;
            }

            $pelangganId = $pelangganIds->isEmpty() || $i % 3 === 0
                ? null
                : $pelangganIds[($i - 1) % $pelangganIds->count()];
            $userId = $userIds[($i - 1) % $userIds->count()];

            Penjualan::updateOrCreate(
                ['no_nota' => sprintf('NT-%04d', $i)],
                [
                    'pelanggan_id' => $pelangganId,
                    'user_id' => $userId,
                    'tanggal_penjualan' => now()->subDays(10 - $i)->toDateString(),
                    'total_penjualan' => 0,
                ]
            );
        }

        $penjualans = Penjualan::orderBy('id')->take(10)->get();

        foreach ($penjualans as $index => $penjualan) {
            if ($obats->isEmpty()) {
                break;
            }

            $obat = $obats[$index % $obats->count()];
            $jumlah = 1 + $index;
            $hargaJual = (float) $obat->harga_jual;
            $subtotal = round($hargaJual * $jumlah, 2);

            PenjualanDetail::updateOrCreate(
                [
                    'penjualan_id' => $penjualan->id,
                    'obat_id' => $obat->id,
                ],
                [
                    'jumlah' => $jumlah,
                    'harga_jual' => $hargaJual,
                    'subtotal' => $subtotal,
                ]
            );
        }

        foreach ($penjualans as $penjualan) {
            $total = PenjualanDetail::where('penjualan_id', $penjualan->id)
                ->sum('subtotal');

            $penjualan->update(['total_penjualan' => $total]);
        }
    }
}
