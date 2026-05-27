<?php

namespace database\seeders;

use Illuminate\Database\Seeder;
use App\Models\Berita;
use Illuminate\Support\Str;

class BeritaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $beritaList = [
            [
                'judul' => 'Pemerintah Desa Kemang Salurkan BLT Dana Desa Tahap Pertama',
                'kategori' => 'Pemerintahan',
                'ringkasan' => 'Pemerintah Desa Kemang menyalurkan Bantuan Langsung Tunai (BLT) Dana Desa tahap pertama untuk membantu kesejahteraan warga kurang mampu.',
                'isi' => '<p><strong>Desa Kemang</strong> — Pemerintah Desa Kemang, Kecamatan Pangkalan Kuras, menyalurkan Bantuan Langsung Tunai (BLT) Dana Desa tahap pertama tahun anggaran 2026. Kegiatan ini bertempat di Aula Kantor Desa Kemang dan dihadiri langsung oleh Kepala Desa serta jajaran perangkat desa lainnya.</p><p>Sebanyak puluhan Keluarga Penerima Manfaat (KPM) menerima dana bantuan tunai. Kepala Desa Kemang menyampaikan agar warga penerima manfaat dapat menggunakan dana bantuan ini dengan bijak, terutama untuk mencukupi kebutuhan pokok sehari-hari keluarga.</p><p>Penyaluran berjalan dengan tertib, lancar, dan tetap menjaga keterbukaan informasi publik desa.</p>',
                'penulis' => 'Lukman Hakim',
                'is_published' => true,
                'published_at' => now(),
            ],
            [
                'judul' => 'Gotong Royong Bersama Warga Membersihkan Area Fasilitas Publik Desa',
                'kategori' => 'Kegiatan Desa',
                'ringkasan' => 'Warga Desa Kemang bersama aparat desa mengadakan aksi gotong royong membersihkan area jalan utama dan fasilitas publik desa.',
                'isi' => '<p><strong>Desa Kemang</strong> — Dalam rangka menjaga kebersihan lingkungan dan mempererat tali silaturahmi antar warga, masyarakat Desa Kemang antusias mengikuti kegiatan kerja bakti gotong royong membersihkan fasilitas umum di sekitar lingkungan desa pada hari Minggu pagi.</p><p>Kegiatan difokuskan pada pembersihan parit saluran air guna mencegah banjir menjelang musim penghujan serta pembabatan rumput liar di sepanjang bahu jalan utama desa. Kepala Desa Kemang mengapresiasi tinggi kerukunan dan kebersamaan warga yang senantiasa terjaga dari waktu ke waktu.</p>',
                'penulis' => 'Indra Pramudya',
                'is_published' => true,
                'published_at' => now()->subDays(2),
            ],
            [
                'judul' => 'Pengumuman Pelayanan Surat Online E-Service Resmi Diluncurkan',
                'kategori' => 'Pengumuman',
                'ringkasan' => 'Kini warga Desa Kemang dapat melakukan pengajuan surat administratif secara online tanpa harus antre lama di kantor desa.',
                'isi' => '<p><strong>Desa Kemang</strong> — Guna meningkatkan efisiensi dan transparansi pelayanan publik, Pemerintah Desa Kemang secara resmi meluncurkan portal E-Government berbasis website. Fitur unggulan dari portal ini adalah E-Service Layanan Surat Online.</p><p>Dengan sistem baru ini, warga kini dapat mengajukan Surat Keterangan Domisili (SKD), Surat Keterangan Usaha (SKU), Surat Keterangan Tidak Mampu (SKM), maupun Surat Pengantar KTP/KK secara online langsung dari smartphone atau komputer mereka tanpa perlu repot datang berkali-kali ke kantor desa.</p><p>Cukup mengisi form yang disediakan dan memantau status surat menggunakan nomor referensi unik yang digenerate otomatis oleh sistem.</p>',
                'penulis' => 'Indra Pramudya',
                'is_published' => true,
                'published_at' => now()->subDays(5),
            ],
        ];

        foreach ($beritaList as $berita) {
            Berita::updateOrCreate(
                ['slug' => Str::slug($berita['judul'])],
                $berita
            );
        }
    }
}
