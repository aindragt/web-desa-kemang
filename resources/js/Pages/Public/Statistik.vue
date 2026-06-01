<template>
  <PublicLayout>
    <Head title="Statistik Desa - E-Government Desa Kemang" />

    <!-- Banner Section -->
    <section class="bg-gradient-to-r from-[#2D5016] to-[#1f370e] text-white py-16 border-b-4 border-[#C8952A] relative">
      <div class="max-w-7xl mx-auto px-4 text-center space-y-4">
        <h1 class="text-3xl md:text-5xl font-serif font-bold">Statistik Demografi Desa</h1>
        <p class="text-xs md:text-sm text-amber-100/70 uppercase tracking-widest font-ui font-semibold">
          Data Kependudukan, Pendidikan, Pekerjaan, dan Agama Secara Aktual
        </p>
      </div>
    </section>

    <!-- Demography Main Stats Cards -->
    <section class="py-12 bg-white">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- 4 Angka Utama -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-6 max-w-5xl mx-auto mb-16">
          <div v-for="stat in summaryCards" :key="stat.label" class="bg-[#F5EDD8]/20 border border-[#C8952A]/10 rounded-2xl p-6 text-center shadow-sm">
            <span class="block text-2xl md:text-3xl font-bold text-[#2D5016] font-serif">{{ stat.value }}</span>
            <span class="block text-xs font-semibold text-gray-500 mt-2 uppercase tracking-wide font-ui">{{ stat.label }}</span>
          </div>
        </div>

        <!-- Demography Charts (Grouped data representation using clean Tailwind CSS simulated charts) -->
        <div class="space-y-16 max-w-4xl mx-auto">
          
          <!-- Loop per Category (Pendidikan, Pekerjaan, Agama) -->
          <div v-for="(items, category) in dataStatistik" :key="category" class="bg-white rounded-3xl p-8 border border-gray-100 shadow-sm space-y-6">
            <div class="space-y-1">
              <h3 class="text-lg font-serif font-bold text-[#2D5016] uppercase tracking-wider">
                Statistik Kategori: {{ category }}
              </h3>
              <div class="h-0.5 w-16 bg-[#C8952A] rounded-full" />
            </div>

            <!-- Custom Horizontal Bar Chart Simulation (using Tailwind widths) -->
            <div class="space-y-5">
              <div v-for="item in items" :key="item.id" class="space-y-1.5 font-ui">
                <div class="flex justify-between text-xs font-semibold text-gray-700">
                  <span>{{ item.label }}</span>
                  <span>{{ item.nilai }} {{ item.satuan }}</span>
                </div>
                <div class="w-full bg-gray-100 h-3.5 rounded-full overflow-hidden">
                  <div
                    class="bg-gradient-to-r from-[#2D5016] to-[#C8952A] h-full rounded-full transition-all duration-1000 ease-out"
                    :style="{ width: calculatePercent(item.nilai, items) + '%' }"
                  />
                </div>
                <span class="block text-[10px] text-gray-400 font-body">
                  Persentase: {{ calculatePercent(item.nilai, items) }}%
                </span>
              </div>
            </div>
          </div>

        </div>

      </div>
    </section>
  </PublicLayout>
</template>

<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import PublicLayout from '@/Layouts/PublicLayout.vue';

const props = defineProps({
  statistik: {
    type: Object,
    default: () => ({})
  }
});

// Group data or default to static array structures if empty
const dataStatistik = computed(() => {
  if (Object.keys(props.statistik).length > 0) {
    return props.statistik;
  }
  return {
    pendidikan: [
      { id: 1, label: 'SD / Sederajat', nilai: 450, satuan: 'jiwa' },
      { id: 2, label: 'SMP / Sederajat', nilai: 620, satuan: 'jiwa' },
      { id: 3, label: 'SMA / Sederajat', nilai: 890, satuan: 'jiwa' },
      { id: 4, label: 'Diploma / Sarjana (D3/S1/S2)', nilai: 310, satuan: 'jiwa' }
    ],
    pekerjaan: [
      { id: 5, label: 'Petani / Pekebun', nilai: 750, satuan: 'orang' },
      { id: 6, label: 'Karyawan Swasta / Buruh', nilai: 580, satuan: 'orang' },
      { id: 7, label: 'Wiraswasta / Pedagang', nilai: 340, satuan: 'orang' },
      { id: 8, label: 'PNS / TNI / Polri', nilai: 120, satuan: 'orang' }
    ],
    agama: [
      { id: 9, label: 'Islam', nilai: 1850, satuan: 'jiwa' },
      { id: 10, label: 'Kristen Protestan', nilai: 240, satuan: 'jiwa' },
      { id: 11, label: 'Katolik', nilai: 110, satuan: 'jiwa' },
      { id: 12, label: 'Budha / Lainnya', nilai: 70, satuan: 'jiwa' }
    ]
  };
});

// Calculate totals per category
const totals = computed(() => {
  const res = {};
  for (const cat in dataStatistik.value) {
    res[cat] = dataStatistik.value[cat].reduce((sum, item) => sum + parseFloat(item.nilai), 0);
  }
  return res;
});

const calculatePercent = (val, list) => {
  const sum = list.reduce((s, i) => s + parseFloat(i.nilai), 0);
  if (sum === 0) return 0;
  return Math.round((val / sum) * 100);
};

const summaryCards = [
  { label: 'Kepadatan Penduduk', value: '2.280 Jiwa' },
  { label: 'Total Kepala Keluarga', value: '540 KK' },
  { label: 'Laki-Laki', value: '1.160 Jiwa' },
  { label: 'Perempuan', value: '1.120 Jiwa' }
];
</script>
