<?php

namespace database\seeders;

use Illuminate\Database\Seeder;
use App\Models\Pengaturan;

class PengaturanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $settings = [
            [
                'kunci' => 'nama_kepala_desa',
                'nilai' => 'Lukman Hakim',
            ],
            [
                'kunci' => 'nip_kepala_desa',
                'nilai' => '19700101 199903 1 001',
            ],
            [
                'kunci' => 'ttd_kepala_desa',
                'nilai' => 'ttd/default_ttd.png',
            ],
            [
                'kunci' => 'cap_desa',
                'nilai' => 'cap/default_cap.png',
            ],
        ];

        foreach ($settings as $setting) {
            Pengaturan::updateOrCreate(
                ['kunci' => $setting['kunci']],
                $setting
            );
        }
    }
}
