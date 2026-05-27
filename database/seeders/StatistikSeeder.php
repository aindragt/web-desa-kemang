<?php

namespace database\seeders;

use Illuminate\Database\Seeder;
use App\Models\Statistik;

class StatistikSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            // 1. Kategori: Pendidikan
            [
                'kategori' => 'pendidikan',
                'label' => 'SD / Sederajat',
                'nilai' => 450,
                'satuan' => 'jiwa',
                'urutan' => 1,
            ],
            [
                'kategori' => 'pendidikan',
                'label' => 'SMP / Sederajat',
                'nilai' => 620,
                'satuan' => 'jiwa',
                'urutan' => 2,
            ],
            [
                'kategori' => 'pendidikan',
                'label' => 'SMA / Sederajat',
                'nilai' => 890,
                'satuan' => 'jiwa',
                'urutan' => 3,
            ],
            [
                'kategori' => 'pendidikan',
                'label' => 'Diploma / Sarjana (D3/S1/S2)',
                'nilai' => 310,
                'satuan' => 'jiwa',
                'urutan' => 4,
            ],

            // 2. Kategori: Pekerjaan
            [
                'kategori' => 'pekerjaan',
                'label' => 'Petani / Pekebun',
                'nilai' => 750,
                'satuan' => 'orang',
                'urutan' => 1,
            ],
            [
                'kategori' => 'pekerjaan',
                'label' => 'Karyawan Swasta / Buruh',
                'nilai' => 580,
                'satuan' => 'orang',
                'urutan' => 2,
            ],
            [
                'kategori' => 'pekerjaan',
                'label' => 'Wiraswasta / Pedagang',
                'nilai' => 340,
                'satuan' => 'orang',
                'urutan' => 3,
            ],
            [
                'kategori' => 'pekerjaan',
                'label' => 'PNS / TNI / Polri',
                'nilai' => 120,
                'satuan' => 'orang',
                'urutan' => 4,
            ],

            // 3. Kategori: Agama
            [
                'kategori' => 'agama',
                'label' => 'Islam',
                'nilai' => 1850,
                'satuan' => 'jiwa',
                'urutan' => 1,
            ],
            [
                'kategori' => 'agama',
                'label' => 'Kristen Protestan',
                'nilai' => 240,
                'satuan' => 'jiwa',
                'urutan' => 2,
            ],
            [
                'kategori' => 'agama',
                'label' => 'Katolik',
                'nilai' => 110,
                'satuan' => 'jiwa',
                'urutan' => 3,
            ],
            [
                'kategori' => 'agama',
                'label' => 'Budha / Lainnya',
                'nilai' => 70,
                'satuan' => 'jiwa',
                'urutan' => 4,
            ],
        ];

        foreach ($data as $item) {
            Statistik::updateOrCreate(
                [
                    'kategori' => $item['kategori'],
                    'label' => $item['label']
                ],
                $item
            );
        }
    }
}
