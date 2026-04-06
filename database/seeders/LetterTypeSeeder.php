<?php

namespace Database\Seeders;

use App\Models\LetterType;
use Illuminate\Database\Seeder;

class LetterTypeSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            [
                'nama_surat' => 'Surat Keterangan Usaha',
                'kode_surat' => 'SKU',
                'template_file' => 'letters.sku',
                'persyaratan' => ['Foto KTP', 'Foto KK', 'Surat Pengantar RT'],
            ],
            [
                'nama_surat' => 'Surat Keterangan Tidak Mampu',
                'kode_surat' => 'SKTM',
                'template_file' => 'letters.sktm',
                'persyaratan' => ['Foto KTP', 'Foto KK', 'Surat Pengantar RT'],
            ],
            [
                'nama_surat' => 'Surat Keterangan Domisili',
                'kode_surat' => 'DOMISILI',
                'template_file' => 'letters.domisili',
                'persyaratan' => ['Foto KTP', 'Foto KK'],
            ],
            [
                'nama_surat' => 'Surat Keterangan Kematian',
                'kode_surat' => 'KEMATIAN',
                'template_file' => 'letters.kematian',
                'persyaratan' => ['Foto KTP Pelapor', 'Surat Keterangan Kematian RS'],
            ],
            [
                'nama_surat' => 'Surat Keterangan Kelahiran',
                'kode_surat' => 'KELAHIRAN',
                'template_file' => 'letters.kelahiran',
                'persyaratan' => ['Foto KTP Orang Tua', 'Surat Kelahiran Bidan/RS'],
            ],
            [
                'nama_surat' => 'Surat Pengantar Nikah',
                'kode_surat' => 'NIKAH',
                'template_file' => 'letters.pengantar_nikah',
                'persyaratan' => ['Foto KTP', 'Foto KK', 'Surat Pengantar RT'],
            ],
        ];

        foreach ($data as $item) {
            LetterType::updateOrCreate(
                ['kode_surat' => $item['kode_surat']],
                $item
            );
        }
    }
}