<template>
  <div class="min-h-screen bg-gray-100 flex flex-col font-serif text-black antialiased">
    <!-- Top print preview bar (Hidden during print) -->
    <div class="print:hidden w-full bg-[#2D5016] border-b-2 border-[#C8952A] py-3.5 px-6 flex items-center justify-between z-50 text-white font-ui shrink-0">
      <div class="flex items-center gap-3">
        <Link href="/operator/surat" class="text-amber-100 hover:text-white flex items-center gap-1.5 font-bold uppercase text-xs">
          <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7" />
          </svg>
          Kembali
        </Link>
        <span class="h-4 w-px bg-white/20"></span>
        <span class="text-xs font-semibold text-amber-50">Pratinjau Layout Surat Resmi A4</span>
      </div>
      <button
        type="button"
        class="px-4 py-2 bg-[#C8952A] hover:bg-[#b08120] text-white rounded-lg font-bold text-xs uppercase tracking-wider shadow cursor-pointer transition-all active:scale-98"
        @click="windowPrint"
      >
        Cetak Surat (A4)
      </button>
    </div>

    <!-- Paper Sheet A4 -->
    <div class="mx-auto my-0 sm:my-8 bg-white max-w-[210mm] min-h-[297mm] w-full p-[20mm] md:p-[25mm] shadow-2xl relative flex flex-col justify-between overflow-hidden border border-gray-200 print:shadow-none print:border-0 print:my-0 print:mx-0">
      
      <!-- Kop Surat -->
      <div class="space-y-4">
        <div class="flex items-center justify-between gap-6 border-b-4 border-black pb-4">
          <!-- Logo Pelalawan (Simetri Kiri) -->
          <div class="h-16 w-16 bg-gray-150 rounded flex items-center justify-center text-[10px] text-center font-bold text-gray-500 uppercase border border-gray-300 shadow-inner shrink-0 leading-tight">
            Kabupaten<br>Pelalawan
          </div>
          <!-- Text Kop -->
          <div class="text-center flex-1 space-y-1">
            <span class="block text-sm uppercase tracking-wide font-semibold">Pemerintah Kabupaten Pelalawan</span>
            <span class="block text-xs uppercase tracking-wider font-semibold">Kecamatan Pangkalan Kuras</span>
            <span class="block text-base uppercase tracking-widest font-bold">Pemerintah Desa Kemang</span>
            <span class="block text-[10px] italic font-sans text-gray-600">Alamat: Lintas Timur No. 45, Desa Kemang, Kode Pos 28382. Email: pemdes@kemang.desa.id</span>
          </div>
          <!-- Logo Desa Kemang (Simetri Kanan) -->
          <div class="h-16 w-16 bg-[#C8952A] rounded-full flex items-center justify-center font-serif font-bold text-white shadow-inner shrink-0 text-xl">
            DK
          </div>
        </div>

        <!-- Judul Surat Resmi -->
        <div class="text-center space-y-1 py-4">
          <h2 class="text-sm font-bold uppercase tracking-wide underline">
            {{ formatJudul(surat.jenis_surat) }}
          </h2>
          <span class="block text-xs font-sans">Nomor: {{ surat.nomor_referensi }}</span>
        </div>

        <!-- Pembuka -->
        <div class="space-y-4 text-xs leading-relaxed font-serif text-justify">
          <p>
            Yang bertanda tangan di bawah ini Kepala Desa Kemang, Kecamatan Pangkalan Kuras, Kabupaten Pelalawan, Provinsi Riau dengan ini menerangkan bahwa:
          </p>

          <!-- Data Diri Pemohon -->
          <table class="w-full text-xs border-collapse">
            <tbody>
              <tr><td class="py-1 w-36">Nama Lengkap</td><td class="py-1 px-2">:</td><td class="py-1 font-bold">{{ surat.nama_lengkap }}</td></tr>
              <tr><td class="py-1">NIK (Kependudukan)</td><td class="py-1 px-2">:</td><td class="py-1 font-mono font-bold">{{ surat.nik }}</td></tr>
              <tr><td class="py-1">Nomor Kontak</td><td class="py-1 px-2">:</td><td class="py-1">{{ surat.kontak }}</td></tr>
              <tr v-if="surat.jenis_surat === 'usaha'"><td class="py-1">Nama Usaha / UMKM</td><td class="py-1 px-2">:</td><td class="py-1 font-bold">{{ surat.nama_usaha }}</td></tr>
              <tr v-if="surat.jenis_surat === 'usaha'"><td class="py-1">Bidang / Jenis Usaha</td><td class="py-1 px-2">:</td><td class="py-1 font-bold">{{ surat.jenis_usaha }}</td></tr>
            </tbody>
          </table>

          <!-- Isi Berdasarkan Jenis -->
          <div class="py-2 space-y-4">
            <p v-if="surat.jenis_surat === 'usaha'">
              Orang tersebut di atas adalah benar memiliki unit usaha/UMKM mandiri <strong>"{{ surat.nama_usaha }}"</strong> di bidang <strong>{{ surat.jenis_usaha }}</strong> yang bertempat di wilayah administratif Desa Kemang. Surat keterangan ini dikeluarkan untuk dipergunakan sebagai kelayakan legalitas usaha serta pendukung pengajuan modal kemitraan.
            </p>
            <p v-else-if="surat.jenis_surat === 'domisili'">
              Berdasarkan catatan kependudukan yang ada, nama tersebut di atas benar merupakan warga yang berdomisili tetap dan tinggal menetap di lingkungan Desa Kemang, Kecamatan Pangkalan Kuras. Surat keterangan domisili ini dibuat untuk pemenuhan kelengkapan administrasi yang diperlukan.
            </p>
            <p v-else-if="surat.jenis_surat === 'ktp'">
              Orang tersebut di atas benar merupakan penduduk terdaftar di Desa Kemang dan surat pengantar ini dikeluarkan sebagai rekomendasi resmi pengurusan Kartu Tanda Penduduk Elektronik (E-KTP) baru atau penggantian berkas rusak di Kantor Camat Pangkalan Kuras.
            </p>
            <p v-else-if="surat.jenis_surat === 'kematian'">
              Pemerintah Desa Kemang menerangkan bahwa nama yang bersangkutan telah meninggal dunia. Surat keterangan kematian ini dibuat atas laporan resmi dari pihak keluarga/ahli waris untuk dipergunakan sebagaimana mestinya.
            </p>
            <p v-else>
              Demikian surat keterangan ini kami berikan kepada yang bersangkutan untuk dapat dipergunakan sebagaimana mestinya dan pihak yang berkepentingan harap maklum.
            </p>
          </div>

          <p>
            Demikian surat keterangan ini dibuat dengan sebenar-benarnya untuk dapat dipergunakan secara bertanggung jawab oleh pemohon.
          </p>
        </div>
      </div>

      <!-- TTD, Cap Stempel & Audit Trail -->
      <div class="flex justify-end pt-12 relative">
        <div class="w-64 text-center text-xs space-y-1 relative">
          <span class="block">Kemang, {{ new Date(surat.disetujui_at || Date.now()).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}</span>
          <span class="block font-bold">Kepala Desa Kemang</span>
          
          <!-- TTD & Cap Box (Tumpuk / Cap di belakang TTD) -->
          <div class="h-32 w-full relative flex items-center justify-center">
            
            <!-- Cap Stempel (Z-index 10, di belakang TTD) -->
            <img
              v-if="kades.cap_url"
              :src="kades.cap_url"
              class="absolute h-28 w-28 object-contain opacity-85 z-10 select-none pointer-events-none"
              alt="Cap Stempel Desa"
            />
            
            <!-- Tanda Tangan Digital (Z-index 20, menimpa cap) -->
            <img
              v-if="kades.ttd_url"
              :src="kades.ttd_url"
              class="absolute h-24 w-40 object-contain z-20 select-none pointer-events-none"
              alt="Tanda Tangan Kades"
            />

            <!-- Placeholder TTD jika belum diupload -->
            <span v-if="!kades.ttd_url && !kades.cap_url" class="text-[10px] text-gray-400 font-sans border border-dashed border-gray-300 p-4 rounded uppercase">
              TTD & Cap Digital
            </span>
          </div>

          <span class="block font-bold text-xs underline">{{ kades.nama }}</span>
          <span class="block font-sans text-[10px] text-gray-600">NIP: {{ kades.nip }}</span>
        </div>

        <!-- Audit Trail / QR Code placeholder info (Pojok Kiri Bawah Surat) -->
        <div class="absolute bottom-0 left-0 text-[8px] font-sans text-gray-400 max-w-xs space-y-0.5 select-none leading-normal">
          <span class="block font-bold">VALIDASI SISTEM E-GOVERNMENT</span>
          <span class="block">Disetujui oleh: {{ kades.nama }}</span>
          <span class="block">Waktu Audit: {{ new Date(surat.disetujui_at || Date.now()).toLocaleString('id-ID') }}</span>
          <span class="block">Dokumen ini sah dikeluarkan oleh server resmi Desa Kemang.</span>
        </div>
      </div>

    </div>
  </div>
</template>

<script setup>
import { Head, Link } from '@inertiajs/vue3';

defineProps({
  surat: {
    type: Object,
    required: true
  },
  kades: {
    type: Object,
    default: () => ({
      nama: 'Lukman Hakim',
      nip: '19700101 199903 1 001',
      ttd_url: null,
      cap_url: null
    })
  }
});

const formatJudul = (jenis) => {
  const map = {
    usaha: 'Surat Keterangan Usaha (SKU)',
    domisili: 'Surat Keterangan Domisili (SKD)',
    ktp: 'Surat Keterangan Pengantar KTP (SPK)',
    kematian: 'Surat Keterangan Kematian (SKK)'
  };
  return map[jenis] || 'Surat Keterangan Kependudukan';
};

const windowPrint = () => {
  window.print();
};
</script>

<style>
@media print {
  body {
    background-color: white !important;
  }
  .print\:hidden {
    display: none !important;
  }
}
</style>
