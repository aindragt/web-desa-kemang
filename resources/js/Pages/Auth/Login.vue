<script setup>
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

const showPassword = ref(false);

const form = useForm({
    username: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post('/login', {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Masuk Portal E-Gov" />
    
    <div class="min-h-screen flex items-center justify-center bg-krem relative overflow-hidden px-4 md:px-6">
        
        <!-- Ornamen Latar Belakang Songket Melayu Riau (Aksen Emas) -->
        <div class="absolute -top-12 -left-12 w-64 h-64 rounded-full bg-accent/10 blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-12 -right-12 w-64 h-64 rounded-full bg-primary/10 blur-3xl pointer-events-none"></div>
        
        <div class="w-full max-w-md bg-putih-gading rounded-2xl shadow-xl border-t-8 border-accent overflow-hidden relative z-10">
            <div class="p-8">
                
                <!-- Logo & Judul -->
                <div class="text-center mb-8">
                    <div class="w-20 h-20 mx-auto rounded-full bg-primary flex items-center justify-center text-accent text-3xl font-bold shadow border-2 border-accent mb-4">
                        DK
                    </div>
                    <h2 class="font-display text-2xl font-bold text-coklat-tua">Portal E-Government</h2>
                    <p class="font-serif text-sm text-primary font-semibold mt-1">Desa Kemang, Kabupaten Pelalawan</p>
                </div>

                <!-- Alert Flash Messages -->
                <div v-if="$page.props.flash.success" class="mb-4 p-3 bg-primary/10 border border-primary/20 text-primary text-xs rounded-lg font-sans">
                    {{ $page.props.flash.success }}
                </div>
                <div v-if="$page.props.flash.error" class="mb-4 p-3 bg-red-500/10 border border-red-500/20 text-red-700 text-xs rounded-lg font-sans">
                    {{ $page.props.flash.error }}
                </div>

                <!-- Form Login -->
                <form @submit.prevent="submit" class="space-y-5">
                    
                    <!-- Username Field -->
                    <div>
                        <label for="username" class="block font-sans text-xs font-semibold text-coklat-tua uppercase tracking-wider mb-2">Username</label>
                        <input 
                            id="username" 
                            type="text" 
                            v-model="form.username" 
                            class="w-full px-4 py-3 rounded-lg border border-accent/20 bg-krem/20 font-sans text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-coklat-tua transition-all"
                            placeholder="Masukkan username"
                            required
                            autocomplete="username"
                        />
                        <div v-if="form.errors.username" class="text-red-700 text-xs font-sans mt-1.5">
                            {{ form.errors.username }}
                        </div>
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block font-sans text-xs font-semibold text-coklat-tua uppercase tracking-wider mb-2">Password</label>
                        <div class="relative">
                            <input 
                                id="password" 
                                :type="showPassword ? 'text' : 'password'" 
                                v-model="form.password" 
                                class="w-full px-4 py-3 rounded-lg border border-accent/20 bg-krem/20 font-sans text-sm focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent text-coklat-tua transition-all"
                                placeholder="Masukkan password"
                                required
                                autocomplete="current-password"
                            />
                            <button 
                                type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute right-3 top-3.5 text-xs text-primary font-semibold hover:text-primary-hover focus:outline-none transition-colors"
                            >
                                {{ showPassword ? 'Sembunyikan' : 'Tampilkan' }}
                            </button>
                        </div>
                        <div v-if="form.errors.password" class="text-red-700 text-xs font-sans mt-1.5">
                            {{ form.errors.password }}
                        </div>
                    </div>

                    <!-- Remember Me checkbox -->
                    <div class="flex items-center justify-between">
                        <label class="flex items-center space-x-2 select-none cursor-pointer">
                            <input 
                                type="checkbox" 
                                v-model="form.remember" 
                                class="rounded border-accent/20 text-primary focus:ring-primary"
                            />
                            <span class="font-sans text-xs text-coklat-tua/75">Ingat Saya</span>
                        </label>
                    </div>

                    <!-- Submit Button -->
                    <button 
                        type="submit" 
                        :disabled="form.processing"
                        class="w-full py-3 bg-primary text-putih-gading font-sans font-bold text-sm rounded-lg hover:bg-primary-hover active:scale-[0.98] transition-all shadow border border-accent flex items-center justify-center gap-2 cursor-pointer disabled:opacity-50 disabled:cursor-not-allowed"
                    >
                        <span v-if="form.processing" class="w-4 h-4 border-2 border-putih-gading border-t-transparent rounded-full animate-spin"></span>
                        {{ form.processing ? 'Sedang Masuk...' : 'Masuk Portal' }}
                    </button>
                </form>
            </div>
            
            <div class="bg-primary/5 py-4 border-t border-accent/10 text-center text-[10px] text-primary/75 font-sans tracking-wide">
                MATA KULIAH E-GOVERNMENT &copy; 2026 DESA KEMANG
            </div>
        </div>
    </div>
</template>
