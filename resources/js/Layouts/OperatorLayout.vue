<template>
  <div class="min-h-screen bg-gray-50 flex flex-col font-ui text-gray-800 antialiased">
    <!-- Overlay for mobile sidebar -->
    <div
      v-if="!sidebarCollapsed"
      class="fixed inset-0 bg-gray-900/40 backdrop-blur-sm z-30 lg:hidden"
      @click="sidebarCollapsed = true"
    />

    <!-- Sidebar component -->
    <aside
      :class="[
        'fixed top-0 bottom-0 left-0 bg-[#1a3a08] border-r-2 border-[#C8952A] text-white flex flex-col transition-all duration-300 ease-in-out z-40',
        sidebarCollapsed ? '-translate-x-full lg:translate-x-0 lg:w-20' : 'translate-x-0 w-64'
      ]"
    >
      <!-- Sidebar Header -->
      <div class="h-20 flex items-center justify-between px-4 border-b border-white/10 shrink-0">
        <Link href="/operator" class="flex items-center gap-3 shrink-0 focus:outline-none">
          <div class="h-10 w-10 rounded-full bg-[#C8952A] flex items-center justify-center font-bold text-white shadow-inner shrink-0">
            DK
          </div>
          <span
            v-if="!sidebarCollapsed"
            class="font-serif font-bold text-amber-100 text-base tracking-wide whitespace-nowrap transition-opacity duration-300"
          >
            DESA KEMANG
          </span>
        </Link>
        <button
          type="button"
          class="text-amber-100/80 hover:text-white p-1 rounded-md hover:bg-white/5 cursor-pointer lg:hidden"
          @click="sidebarCollapsed = true"
        >
          <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      <!-- Navigation Menu -->
      <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto scrollbar-thin">
        <Link
          v-for="menu in menus"
          :key="menu.href"
          :href="menu.href"
          :class="[
            'flex items-center gap-3 px-3.5 py-3 rounded-lg transition-all duration-150 font-semibold uppercase tracking-wider text-xs',
            $page.url === menu.href
              ? 'bg-[#C8952A] text-white shadow-md'
              : 'text-amber-100/90 hover:text-white hover:bg-white/5'
          ]"
        >
          <!-- Icons -->
          <component :is="menu.icon" class="h-5 w-5 shrink-0 text-current" />
          
          <span v-if="!sidebarCollapsed" class="whitespace-nowrap transition-opacity duration-300">
            {{ menu.label }}
          </span>

          <!-- Special Badge Counters -->
          <div v-if="!sidebarCollapsed && menu.counter" class="ml-auto">
            <span
              v-if="menu.counter === 'berita' && beritaCounter > 0"
              class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold bg-[#C8952A] text-white rounded-full leading-none"
            >
              {{ beritaCounter }}
            </span>
            <span
              v-else-if="menu.counter === 'surat' && suratPendingCounter > 0"
              class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold bg-red-600 text-white rounded-full leading-none animate-pulse"
            >
              {{ suratPendingCounter }}
            </span>
            <span
              v-else-if="menu.counter === 'pesan' && pesanUnreadCounter > 0"
              class="inline-flex items-center justify-center px-2 py-0.5 text-[10px] font-bold bg-blue-600 text-white rounded-full leading-none"
            >
              {{ pesanUnreadCounter }}
            </span>
          </div>
        </Link>
      </nav>

      <!-- Sidebar Footer (User info & Logout) -->
      <div class="p-4 border-t border-white/10 bg-black/10 shrink-0">
        <div class="flex items-center gap-3" :class="{ 'justify-center': sidebarCollapsed }">
          <div class="h-9 w-9 rounded-full bg-[#C8952A]/20 border border-[#C8952A]/40 flex items-center justify-center font-bold text-amber-100 uppercase">
            {{ authUser.nama ? authUser.nama.charAt(0) : 'O' }}
          </div>
          <div v-if="!sidebarCollapsed" class="flex-1 min-w-0">
            <span class="block text-xs font-bold text-white truncate">{{ authUser.nama }}</span>
            <span class="block text-[10px] font-semibold text-amber-100/60 uppercase tracking-widest truncate">{{ authUser.role }}</span>
          </div>
        </div>
        
        <Link
          href="/logout"
          method="post"
          as="button"
          :class="[
            'mt-3 w-full flex items-center gap-3 px-3.5 py-2.5 rounded-lg text-xs font-bold text-red-300 hover:text-red-100 hover:bg-red-500/10 transition-colors uppercase tracking-wider cursor-pointer',
            { 'justify-center': sidebarCollapsed }
          ]"
        >
          <svg class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
          </svg>
          <span v-if="!sidebarCollapsed">Keluar</span>
        </Link>
      </div>
    </aside>

    <!-- Main Content Area -->
    <div
      :class="[
        'flex-1 flex flex-col transition-all duration-300 ease-in-out',
        sidebarCollapsed ? 'lg:pl-20' : 'lg:pl-64'
      ]"
    >
      <!-- Topbar Component -->
      <header class="h-20 bg-[#FAF9F5] border-b border-[#C8952A]/30 px-6 flex items-center justify-between sticky top-0 z-30">
        <!-- Sidebar Toggle -->
        <button
          type="button"
          class="text-gray-600 hover:text-gray-900 p-1.5 rounded-md hover:bg-gray-100 focus:outline-none cursor-pointer"
          @click="sidebarCollapsed = !sidebarCollapsed"
        >
          <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
          </svg>
        </button>

        <!-- Right Side Brand Info -->
        <div class="flex items-center gap-3">
          <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-[#2D5016]/10 text-[#2D5016] border border-[#2D5016]/20 uppercase tracking-widest">
            OPERATOR
          </span>
        </div>
      </header>

      <!-- Main Slot Content -->
      <main class="flex-1 p-6 md:p-8">
        <slot />
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

const page = usePage();
const authUser = computed(() => page.props.auth?.user || { nama: 'Staf Operator', role: 'operator' });

// Counter real statistics data passed down by standard HandleInertiaRequests or dashboard controllers
const beritaCounter = computed(() => page.props.counters?.berita_total || 0);
const suratPendingCounter = computed(() => page.props.counters?.surat_pending || 0);
const pesanUnreadCounter = computed(() => page.props.counters?.pesan_unread || 0);

const sidebarCollapsed = ref(false);

// Core Icons definitions using raw paths
const HomeIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>`
};
const BookOpenIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>`
};
const ChartIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2z"/></svg>`
};
const MailIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>`
};
const ChatAltIcon = {
  template: `<svg fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>`
};

const menus = [
  { label: 'Dashboard', href: '/operator', icon: HomeIcon },
  { label: 'Berita', href: '/operator/berita', icon: BookOpenIcon, counter: 'berita' },
  { label: 'Statistik', href: '/operator/statistik', icon: ChartIcon },
  { label: 'Pengajuan Surat', href: '/operator/surat', icon: MailIcon, counter: 'surat' },
  { label: 'Pesan Kontak', href: '/operator/pesan', icon: ChatAltIcon, counter: 'pesan' },
];
</script>
