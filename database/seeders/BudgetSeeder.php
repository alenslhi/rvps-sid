<?php

namespace Database\Seeders;

use App\Models\Budget;
use Illuminate\Database\Seeder;

class BudgetSeeder extends Seeder
{
    public function run(): void
    {
        $rows = [
            ['tahun' => 2026, 'kategori' => 'pendapatan', 'label' => 'Dana Desa', 'nominal' => 1200000000, 'realisasi' => 1150000000],
            ['tahun' => 2026, 'kategori' => 'pendapatan', 'label' => 'Alokasi Dana Desa', 'nominal' => 800000000, 'realisasi' => 790000000],
            ['tahun' => 2026, 'kategori' => 'pengeluaran', 'label' => 'Pembangunan Infrastruktur', 'nominal' => 900000000, 'realisasi' => 850000000],
            ['tahun' => 2026, 'kategori' => 'pengeluaran', 'label' => 'Pemberdayaan Masyarakat', 'nominal' => 400000000, 'realisasi' => 360000000],
        ];

        foreach ($rows as $row) {
            Budget::create($row);
        }
    }
}