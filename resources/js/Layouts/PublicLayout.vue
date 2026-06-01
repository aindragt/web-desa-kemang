<template>
  <div class="min-h-screen bg-[#F5EDD8]/30 flex flex-col font-body text-gray-800 antialiased">
    <!-- Navbar -->
    <header class="sticky top-0 bg-[#2D5016]/95 backdrop-blur-md shadow-md z-40 border-b-2 border-[#C8952A] transition-all">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-20">
          
          <!-- Logo & Brand -->
          <Link href="/" class="flex items-center gap-3 shrink-0 focus:outline-none">
            <div class="h-12 w-12 rounded-full bg-[#C8952A] border-2 border-amber-100 flex items-center justify-center text-white font-serif font-bold text-lg shadow-inner">
              DK
            </div>
            <div>
              <span class="block font-serif font-bold text-amber-100 text-lg leading-tight tracking-wide">DESA KEMANG</span>
              <span class="block text-white text-[10px] tracking-widest font-ui uppercase font-semibold">Kabupaten Pelalawan</span>
            </div>
          </Link>

          <!-- Desktop Menu -->
          <nav class="hidden lg:flex items-center gap-1 font-ui text-sm font-semibold">
            <Link
              v-for="menu in menus"
              :key="menu.href"
              :href="menu.href"
              :class="[
                'px-4 py-2.5 rounded-lg transition-all duration-200 uppercase tracking-wider text-xs',
                $page.url === menu.href
                  ? 'bg-[#C8952A] text-white shadow-sm font-bold'
                  : 'text-amber-100/90 hover:text-white hover:bg-[#2D5016]/50'
              ]"
            >
              {{ menu.label }}
            </Link>
          </nav>

          <!-- Mobile Hamburger -->
          <div class="flex lg:hidden">
            <button
              type="button"
              class="inline-flex items-center justify-center p-2.5 rounded-lg text-amber-100 hover:text-white hover:bg-[#2D5016]/50 focus:outline-none cursor-pointer"
              @click="mobileMenuOpen = !mobileMenuOpen"
            >
              <svg v-if="!mobileMenuOpen" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
              </svg>
              <svg v-else class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

        </div>
      </div>

      <!-- Mobile Dropdown Menu -->
      <Transition
        enter-active-class="transition ease-out duration-200"
        enter-from-class="opacity-0 -translate-y-4"
        enter-to-class="opacity-100 translate-y-0"
        leave-active-class="transition ease-in duration-150"
        leave-from-class="opacity-100 translate-y-0"
        leave-to-class="opacity-0 -translate-y-4"
      >
        <div v-if="mobileMenuOpen" class="lg:hidden bg-[#2D5016] border-t border-[#C8952A]/30">
          <div class="px-2 pt-3 pb-4 space-y-1 font-ui text-xs font-semibold">
            <Link
              v-for="menu in menus"
              :key="menu.href"
              :href="menu.href"
              class="block px-4 py-3 rounded-lg text-amber-100 hover:text-white hover:bg-white/10 uppercase tracking-wide"
              @click="mobileMenuOpen = false"
            >
              {{ menu.label }}
            </Link>
          </div>
        </div>
      </Transition>
    </header>

    <!-- Main Content Slot -->
    <main class="flex-1">
      <slot />
    </main>

    <!-- Decorative Gold Divider (SVG Melayu Riau Ornamen) -->
    <div class="w-full flex justify-center py-6 text-[#C8952A]">
      <svg class="h-8 max-w-lg w-full shrink-0 opacity-80" viewBox="0 0 400 40" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path d="M200 5C170 5 160 35 130 35C100 35 90 5 60 5C30 5 0 35 0 35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <path d="M200 5C230 5 240 35 270 35C300 35 310 5 340 5C370 5 400 35 400 35" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
        <circle cx="200" cy="20" r="6" fill="currentColor"/>
        <circle cx="130" cy="20" r="4" fill="currentColor"/>
        <circle cx="270" cy="20" r="4" fill="currentColor"/>
        <circle cx="60" cy="20" r="4" fill="currentColor"/>
        <circle cx="340" cy="20" r="4" fill="currentColor"/>
      </svg>
    </div>

    <!-- Footer -->
    <footer class="bg-[#2D5016] text-white border-t-4 border-[#C8952A] pt-12 pb-8 font-ui">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
          
          <!-- Info Desa -->
          <div class="space-y-4">
            <h3 class="font-serif font-bold text-amber-100 text-lg">Kantor Desa Kemang</h3>
            <p class="text-xs text-amber-100/80 leading-relaxed font-body">
              Jl. Lintas Timur No.45, Desa Kemang, Kec. Pangkalan Kuras, Kabupaten Pelalawan, Riau, Kode Pos 28382.
            </p>
            <div class="text-xs text-amber-100 space-y-1">
              <span class="block">Email: info@kemang.desa.id</span>
              <span class="block">Telp: +62 823-8888-9999</span>
            </div>
          </div>

          <!-- Navigasi -->
          <div class="space-y-4">
            <h3 class="font-bold text-amber-100 text-sm uppercase tracking-wider">Navigasi</h3>
            <ul class="space-y-2 text-xs text-amber-100/80">
              <li v-for="menu in menus" :key="menu.href">
                <Link :href="menu.href" class="hover:text-white transition-colors">{{ menu.label }}</Link>
              </li>
            </ul>
          </div>

          <!-- Jam Pelayanan -->
          <div class="space-y-4">
            <h3 class="font-bold text-amber-100 text-sm uppercase tracking-wider">Jam Pelayanan</h3>
            <ul class="space-y-2 text-xs text-amber-100/80">
              <li class="flex justify-between"><span>Senin - Kamis:</span><span>08:00 - 15:30</span></li>
              <li class="flex justify-between"><span>Jumat:</span><span>08:00 - 11:30 & 13:30 - 16:00</span></li>
              <li class="flex justify-between"><span>Sabtu - Minggu:</span><span class="text-amber-300">Tutup (Libur)</span></li>
            </ul>
          </div>

          <!-- Lembaga Desa -->
          <div class="space-y-4">
            <h3 class="font-bold text-amber-100 text-sm uppercase tracking-wider">Lembaga Desa</h3>
            <ul class="space-y-2 text-xs text-amber-100/80">
              <li>BPD (Badan Permusyawaratan Desa)</li>
              <li>LPM (Lembaga Pemberdayaan Masyarakat)</li>
              <li>PKK Desa Kemang</li>
              <li>Karang Taruna Kemang Jaya</li>
            </ul>
          </div>

        </div>

        <!-- Watermark / Copy -->
        <div class="border-t border-[#C8952A]/30 mt-12 pt-6 text-center text-xs text-amber-100/60 font-body">
          &copy; {{ new Date().getFullYear() }} Pemerintah Desa Kemang. Seluruh Hak Cipta Dilindungi.
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';

const mobileMenuOpen = ref(false);

const menus = [
  { label: 'Beranda', href: '/' },
  { label: 'Profil', href: '/profil' },
  { label: 'Statistik', href: '/statistik' },
  { label: 'Berita', href: '/berita' },
  { label: 'Layanan Surat', href: '/layanan-surat' },
  { label: 'Kontak', href: '/kontak' },
];
</script>
