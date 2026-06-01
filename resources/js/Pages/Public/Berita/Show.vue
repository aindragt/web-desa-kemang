<template>
  <PublicLayout>
    <Head :title="berita.judul + ' - Kabar Desa Kemang'" />

    <!-- Article Content -->
    <section class="py-12 bg-white font-ui">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
          
          <!-- Article Left -->
          <article class="lg:col-span-2 space-y-6">
            
            <!-- Category and Date -->
            <div class="flex items-center gap-3 text-xs font-semibold">
              <span class="px-2.5 py-0.5 rounded-full bg-[#C8952A]/90 text-white uppercase tracking-wider">
                {{ berita.kategori }}
              </span>
              <span class="text-gray-400">
                {{ new Date(berita.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' }) }}
              </span>
            </div>

            <!-- Title -->
            <h1 class="text-2xl md:text-4xl font-serif font-bold text-gray-900 leading-snug">
              {{ berita.judul }}
            </h1>

            <!-- Main Photo -->
            <div class="h-64 md:h-[400px] bg-gray-100 rounded-3xl overflow-hidden border border-gray-100 shadow-sm shrink-0">
              <img
                :src="berita.foto_utama ? '/storage/' + berita.foto_utama : '/storage/default_berita.png'"
                class="w-full h-full object-cover"
                alt="Foto berita utama"
              />
            </div>

            <!-- Content Render HTML -->
            <div class="text-sm md:text-base leading-relaxed text-gray-700 font-body space-y-4" v-html="berita.isi">
            </div>

            <!-- Photo Gallery Grid (Multiple Photo support) -->
            <div v-if="berita.foto_galeri && berita.foto_galeri.length > 0" class="space-y-4 pt-8 border-t border-gray-100 shrink-0">
              <h3 class="text-lg font-serif font-bold text-[#2D5016]">Galeri Foto Kegiatan</h3>
              <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div
                  v-for="(photo, idx) in berita.foto_galeri"
                  :key="idx"
                  class="h-24 bg-gray-100 rounded-xl overflow-hidden border border-gray-200 cursor-pointer hover:opacity-90 transition-opacity"
                  @click="openLightbox(photo.path)"
                >
                  <img :src="'/storage/' + photo.path" class="w-full h-full object-cover" alt="Galeri foto" />
                </div>
              </div>
            </div>

          </article>

          <!-- Sidebar Right -->
          <aside class="space-y-8">
            
            <!-- Berita Lainnya -->
            <div class="bg-gray-50 border border-gray-100 rounded-3xl p-6 space-y-6 shrink-0">
              <h3 class="text-base font-serif font-bold text-[#2D5016] border-b border-gray-200 pb-3">Kabar Berita Lainnya</h3>
              <div class="space-y-4">
                <div v-for="other in beritaLainnya" :key="other.id" class="flex gap-3 items-start font-ui text-xs">
                  <div class="h-14 w-14 rounded-lg bg-gray-200 overflow-hidden shrink-0">
                    <img :src="other.foto_utama ? '/storage/' + other.foto_utama : '/storage/default_berita.png'" class="w-full h-full object-cover" alt="Foto mini" />
                  </div>
                  <div class="space-y-1">
                    <Link :href="'/berita/' + (other.slug || other.id)" class="block font-bold text-gray-800 hover:text-[#2D5016] line-clamp-2">
                      {{ other.judul }}
                    </Link>
                    <span class="block text-[10px] text-gray-400">
                      {{ new Date(other.created_at).toLocaleDateString('id-ID', { day: 'numeric', month: 'short' }) }}
                    </span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Info Jam Pelayanan -->
            <div class="bg-[#2D5016] text-white rounded-3xl p-6 border-l-4 border-[#C8952A] space-y-3 font-ui text-xs shrink-0">
              <h4 class="font-bold text-amber-100 uppercase tracking-wider">Layanan Pengaduan</h4>
              <p class="text-amber-50/70 font-body">Anda dapat menyampaikan laporan perihal pembangunan atau kendala kependudukan di halaman Kontak kami.</p>
              <Link href="/kontak" class="block">
                <AppButton variant="secondary" size="sm" customClass="w-full">Hubungi Staf Desa</AppButton>
              </Link>
            </div>

          </aside>

        </div>
      </div>
    </section>

    <!-- Lightbox Modal Simulation -->
    <Teleport to="body">
      <div v-if="lightboxOpen" class="fixed inset-0 bg-black/90 z-50 flex items-center justify-center p-4" @click="lightboxOpen = false">
        <button type="button" class="absolute top-4 right-4 text-white text-3xl font-bold cursor-pointer">&times;</button>
        <img :src="'/storage/' + activeLightboxImg" class="max-w-full max-h-[85vh] rounded-lg shadow-2xl" alt="Lightbox Preview" />
      </div>
    </Teleport>
  </PublicLayout>
</template>

<script setup>
import { ref } from 'vue';
import { Head, Link } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';
import AppButton from '@/Components/UI/AppButton.vue';

defineProps({
  berita: {
    type: Object,
    required: true
  },
  beritaLainnya: {
    type: Array,
    default: () => []
  }
});

const lightboxOpen = ref(false);
const activeLightboxImg = ref('');

const openLightbox = (path) => {
  activeLightboxImg.value = path;
  lightboxOpen.value = true;
};
</script>
