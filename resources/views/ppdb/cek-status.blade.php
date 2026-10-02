@extends('layouts.app')

@section('title', 'Cek Status Pendaftar PPDB 2027/2028 - SMK Plus Pelita Nusantara')

@section('content')
@php
    $serverData = $serverPendaftar ?? null;
@endphp

<div x-data="{
    nisnInput: '{{ $query ?? '' }}',
    isLoading: false,
    searchResult: @json($serverData),
    errorMessage: '{{ ($searched && !$serverData) ? 'Data pendaftar dengan NISN tersebut tidak ditemukan.' : '' }}',
    hasSearched: {{ isset($searched) && $searched ? 'true' : 'false' }},

    async handlePerformSearch(query) {
        const q = (query !== undefined ? query : this.nisnInput || '').trim();
        if (!q) {
            this.errorMessage = 'Silakan masukkan nomor NISN Anda.';
            return;
        }

        if (!/^\d+$/.test(q)) {
            this.errorMessage = 'NISN harus berupa digit angka.';
            return;
        }

        this.isLoading = true;
        this.errorMessage = '';
        this.hasSearched = true;

        try {
            const response = await fetch(`/ppdb/cek-status?nisn=${encodeURIComponent(q)}`, {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok && result.success && result.data) {
                this.searchResult = result.data;
                this.errorMessage = '';
            } else {
                this.searchResult = null;
                this.errorMessage = result.message || `Data pendaftar dengan NISN '${q}' tidak ditemukan.`;
            }
        } catch (err) {
            console.error('Fetch error:', err);
            this.searchResult = null;
            this.errorMessage = 'Terjadi kesalahan saat memeriksa data. Silakan coba kembali.';
        } finally {
            this.isLoading = false;
        }
    },

    handleResetSearch() {
        this.nisnInput = '';
        this.searchResult = null;
        this.errorMessage = '';
        this.hasSearched = false;
    }
}" class="min-h-screen bg-[#F5F4F2] flex flex-col font-sans text-brand-ink antialiased">

    <!-- ========================================================
        2. Editorial Hero Section
       ======================================================== -->
    <div class="max-w-canvas mx-auto px-6 md:px-12 pt-2 sm:pt-4 no-print">
        <div
            style="background: linear-gradient(135deg, #7A1018 0%, #5C0B12 100%);"
            class="bg-[#7A1018] rounded-[32px] p-8 sm:p-12 lg:p-14 text-white relative overflow-hidden flex flex-col md:flex-row items-start md:items-center justify-between min-h-[220px] shadow-md border border-white/10"
        >
            <!-- Background Ghost Typography -->
            <div class="absolute right-4 top-1/2 -translate-y-1/2 select-none pointer-events-none opacity-10">
                <x-ghost-heading text="VERIFICATION" variant="light" />
            </div>

            <!-- Orbital Line Vector Curve in Hero -->
            <x-orbital-line variant="s-curve" color="#D04A43" opacity="0.35" class="absolute inset-0 w-full h-full" />

            <!-- Left Content -->
            <div class="z-10 max-w-2xl">
                <span class="font-editorial-eyebrow text-[#E5B5B8] tracking-eyebrow uppercase block mb-2.5 text-xs font-bold">
                    PORTAL PELACAKAN SELEKSI • SMK PLUS PELITA NUSANTARA
                </span>
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold uppercase tracking-wide !text-white leading-tight drop-shadow-sm font-display" style="color: #ffffff !important;">
                    Cek Status Pendaftar PPDB
                </h1>
                <p class="mt-3 font-editorial-body text-[#E8E8E8] text-sm sm:text-base leading-relaxed max-w-xl">
                    Pantau hasil verifikasi administrasi, jadwal tes observasi minat bakat, penetapan kelulusan, dan status daftar ulang cukup dengan memasukkan 10 digit NISN Anda.
                </p>
            </div>

            <!-- Right Circular Graphic -->
            <div class="hidden md:flex z-10 shrink-0 ml-6 items-center justify-center">
                <div class="w-44 h-44 sm:w-48 sm:h-48 lg:w-56 lg:h-56 rounded-full border-4 border-white/20 bg-white/10 backdrop-blur-xs flex flex-col items-center justify-center p-4 shadow-softpill text-center group">
                    <div class="w-16 h-16 rounded-full bg-white/20 flex items-center justify-center mb-2 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                    </div>
                    <span class="text-xs font-bold uppercase tracking-wider text-[#E5B5B8]">
                        VERIFIKASI RESMI
                    </span>
                    <span class="text-sm font-extrabold text-white mt-0.5">
                        Data PPDB 2027
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- ========================================================
        3. Main Canvas Layout: Form Input & Status Card
       ======================================================== -->
    <main class="max-w-canvas mx-auto px-6 md:px-12 py-10 md:py-14 flex-1">
        <div class="flex flex-col lg:flex-row gap-8 lg:gap-10 items-start">
            
            <!-- LEFT COLUMN: Form Input Pencarian & Tips NISN -->
            <div class="w-full lg:w-[420px] shrink-0 space-y-6 no-print">
                <!-- Card Pencarian NISN -->
                <div class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 space-y-5">
                    <div class="flex items-center gap-3 pb-3 border-b border-brand-ink/10">
                        <div class="w-9 h-9 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <div>
                            <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-xs">
                                FORM PENCARIAN
                            </span>
                            <h3 class="font-editorial-h3 text-brand-ink font-bold text-lg mt-0.5">
                                Masukkan NISN Pendaftar
                            </h3>
                        </div>
                    </div>

                    <form @submit.prevent="handlePerformSearch()" class="space-y-4">
                        <div>
                            <label class="block text-xs font-bold uppercase tracking-wider text-brand-ink/70 mb-2">
                                Nomor Induk Siswa Nasional (NISN) <span class="text-brand-signal">*</span>
                            </label>
                            <div class="relative">
                                <input
                                    type="text"
                                    maxlength="10"
                                    placeholder="Masukkan 10 digit NISN (cth: 0081234567)"
                                    x-model="nisnInput"
                                    @input="nisnInput = nisnInput.replace(/\D/g, ''); if(errorMessage) errorMessage = '';"
                                    class="w-full rounded-full border border-brand-ink/20 pl-5 pr-11 py-3 text-sm text-brand-ink bg-[#F9F8F6] focus:bg-white focus:outline-none focus:border-brand-darkred font-mono transition-all"
                                />
                                <button
                                    type="button"
                                    x-show="nisnInput"
                                    @click="nisnInput = ''"
                                    class="absolute right-4 top-1/2 -translate-y-1/2 text-brand-ink/40 hover:text-brand-ink"
                                    style="display: none;"
                                >
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                            <p class="text-[11px] text-brand-ink/55 mt-1.5 leading-normal">
                                NISN dapat ditemukan pada Kartu Pelajar SMP, Ijazah SD, atau Surat Keterangan Kepala Sekolah.
                            </p>
                            <div x-show="errorMessage" class="mt-2 text-xs font-semibold text-brand-signal flex items-center gap-1.5 animate-fade-in" style="display: none;">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <span x-text="errorMessage"></span>
                            </div>
                        </div>

                        <button
                            type="submit"
                            :disabled="isLoading"
                            class="w-full py-3 rounded-full bg-linear-to-r from-brand-signal to-brand-darkred hover:from-brand-warmred hover:to-brand-deepred text-white text-sm font-semibold shadow-md shadow-brand-darkred/25 hover:shadow-lg hover:shadow-brand-darkred/30 flex items-center justify-center gap-2 transition-all cursor-pointer"
                        >
                            <template x-if="isLoading">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    <span>Memeriksa Data...</span>
                                </span>
                            </template>
                            <template x-if="!isLoading">
                                <span class="flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    <span>Cari Status Seleksi</span>
                                </span>
                            </template>
                        </button>
                    </form>
                </div>

                <!-- Card Bantuan: Panduan NISN -->
                <div class="bg-white rounded-card p-6 shadow-softpill border border-brand-ink/10 space-y-3">
                    <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block">
                        PANDUAN NISN
                    </span>
                    <h4 class="font-sans font-bold text-sm text-brand-ink">
                        Lupa atau Belum Mengetahui NISN?
                    </h4>
                    <p class="text-xs text-brand-ink/75 leading-relaxed">
                        Anda dapat mengecek keabsahan dan keaktifan NISN secara mandiri melalui laman resmi Pusdatin Kemendikbudristek.
                    </p>
                    <div class="pt-1">
                        <a
                            href="https://nisn.data.kemdikbud.go.id"
                            target="_blank"
                            rel="noreferrer"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-brand-darkred hover:underline"
                        >
                            <span>Buka Portal NISN Kemendikbud</span>
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                        </a>
                    </div>
                </div>

                <!-- Card Hotline PPDB -->
                <div class="p-5 rounded-card bg-brand-darkred/[0.04] border border-brand-darkred/15 space-y-2.5 shadow-softpill">
                    <div class="flex items-center gap-2 text-brand-darkred">
                        <svg class="w-4 h-4 text-brand-darkred" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                        <span class="font-editorial-eyebrow uppercase tracking-eyebrow text-xs font-bold">
                            BANTUAN VERIFIKASI
                        </span>
                    </div>
                    <p class="text-xs text-brand-ink/75 leading-relaxed">
                        Jika data Anda belum sesuai atau butuh bantuan verifikasi berkas, hubungi sekretariat PPDB melalui WhatsApp:
                    </p>
                    <a
                        href="https://wa.me/6281210868958?text=Halo%20Panitia%20PPDB,%20saya%20ingin%20menanyakan%20status%20pendaftaran%20NISN"
                        target="_blank"
                        rel="noreferrer"
                        class="w-full py-2.5 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold inline-flex items-center justify-center gap-2"
                    >
                        <span>Hubungi Panitia Seleksi</span>
                    </a>
                </div>
            </div>

            <!-- RIGHT COLUMN: Hasil Pelacakan Status -->
            <div class="flex-1 min-w-0 space-y-6">
                
                <!-- Initial State -->
                <div x-show="!searchResult && !hasSearched" class="bg-white rounded-card p-10 text-center border border-brand-ink/10 shadow-softpill space-y-4">
                    <div class="w-16 h-16 rounded-full bg-brand-softmist flex items-center justify-center mx-auto text-brand-darkred">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <h3 class="font-editorial-h3 text-xl font-bold text-brand-ink">
                        Masukkan NISN untuk Melacak Status
                    </h3>
                    <p class="text-xs sm:text-sm text-brand-ink/70 max-w-md mx-auto leading-relaxed">
                        Gunakan kolom pencarian di sebelah kiri atau klik salah satu contoh NISN untuk melihat data hasil seleksi dan tahapan pendaftaran.
                    </p>
                </div>

                <!-- Not Found State -->
                <div x-show="!searchResult && hasSearched && errorMessage" class="bg-white rounded-card p-8 sm:p-10 text-center border border-brand-signal/30 shadow-softpill space-y-4 animate-scale-in" style="display: none;">
                    <div class="w-16 h-16 rounded-full bg-brand-signal/10 text-brand-signal flex items-center justify-center mx-auto">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <h3 class="font-editorial-h3 text-xl font-bold text-brand-ink">
                        Data Tidak Ditemukan
                    </h3>
                    <p class="text-xs sm:text-sm text-brand-ink/75 max-w-md mx-auto leading-relaxed" x-text="errorMessage"></p>
                    <div class="pt-2 flex justify-center gap-3">
                        <button
                            type="button"
                            @click="handleResetSearch()"
                            class="py-2 px-5 rounded-full border border-brand-ink/20 text-brand-ink text-xs font-bold inline-flex items-center gap-1.5"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Cari Ulang</span>
                        </button>
                        <a
                            href="https://wa.me/6281210868958"
                            target="_blank"
                            rel="noreferrer"
                            class="py-2 px-5 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold inline-flex items-center gap-1.5"
                        >
                            <span>Tanya Panitia via WA</span>
                        </a>
                    </div>
                </div>

                <!-- SUCCESS / FOUND STATE -->
                <div x-show="searchResult" class="space-y-6 animate-fade-in" style="display: none;">
                    
                    <!-- 1. Status Header Card -->
                    <div
                        :class="searchResult?.statusType === 'success' ? 'border-[#107c41]/40' : (searchResult?.statusType === 'warning' ? 'border-[#d83b01]/40' : (searchResult?.statusType === 'danger' ? 'border-brand-signal/40' : 'border-brand-darkred/30'))"
                        class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border-2 space-y-4 relative overflow-hidden"
                    >
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                            <div class="space-y-1">
                                <div class="flex items-center gap-2 flex-wrap">
                                    <span
                                        :class="searchResult?.statusType === 'success' ? 'bg-[#107c41]/10 text-[#107c41] border-[#107c41]/30' : (searchResult?.statusType === 'warning' ? 'bg-[#d83b01]/10 text-[#d83b01] border-[#d83b01]/30' : (searchResult?.statusType === 'danger' ? 'bg-brand-signal/10 text-brand-signal border-brand-signal/30' : 'bg-brand-darkred/10 text-brand-darkred border-brand-darkred/30'))"
                                        class="text-xs font-extrabold px-3 py-1 rounded-full border uppercase tracking-wider flex items-center gap-1.5"
                                    >
                                        <span
                                            :class="searchResult?.statusType === 'success' ? 'bg-[#107c41]' : (searchResult?.statusType === 'warning' ? 'bg-[#d83b01]' : (searchResult?.statusType === 'danger' ? 'bg-brand-signal' : 'bg-brand-darkred'))"
                                            class="w-2 h-2 rounded-full"
                                        ></span>
                                        <span x-text="searchResult?.statusUtama"></span>
                                    </span>
                                </div>
                                <h2 class="text-2xl sm:text-3xl font-bold uppercase tracking-wide text-brand-ink font-display mt-2" x-text="searchResult?.namaLengkap"></h2>
                                <p class="text-xs font-mono text-brand-ink/60">
                                    NISN: <strong class="text-brand-darkred" x-text="searchResult?.nisn"></strong> • No. Reg: <strong class="text-brand-darkred" x-text="searchResult?.noPendaftaran"></strong>
                                </p>
                            </div>

                            <div class="shrink-0 flex items-center flex-wrap gap-2 no-print">
                                <template x-if="searchResult?.id">
                                    <a
                                        :href="'/ppdb/cetak-kartu/' + searchResult.id"
                                        target="_blank"
                                        class="py-2 px-4 rounded-full bg-blue-700 hover:bg-blue-800 text-white text-xs font-bold inline-flex items-center gap-1.5 shadow-sm transition-all cursor-pointer"
                                    >
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                        <span>Cetak Kartu Tanda Peserta</span>
                                    </a>
                                </template>
                                <button
                                    type="button"
                                    onclick="window.print()"
                                    class="py-2 px-4 rounded-full border border-brand-ink/20 text-brand-ink text-xs font-bold inline-flex items-center gap-1.5 hover:bg-brand-softmist/50 cursor-pointer"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                                    <span>Cetak Bukti</span>
                                </button>
                            </div>
                        </div>

                        <!-- Keterangan Status Text -->
                        <p class="text-xs sm:text-sm text-brand-ink/85 leading-relaxed pt-2 border-t border-brand-ink/10 font-medium" x-text="searchResult?.keteranganStatus"></p>

                        <!-- Catatan Panitia Box -->
                        <template x-if="searchResult?.catatanPanitia">
                            <div class="p-4 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 text-xs text-brand-ink/80 space-y-1">
                                <span class="font-editorial-eyebrow text-brand-darkred text-[10px] uppercase font-bold block">
                                    CATATAN RESMI PANITIA SELEKSI
                                </span>
                                <p class="leading-relaxed" x-text="searchResult.catatanPanitia"></p>
                            </div>
                        </template>
                    </div>

                    <!-- 2. Biodata Card -->
                    <div class="bg-white rounded-card p-6 sm:p-8 shadow-softpill border border-brand-ink/10 space-y-5">
                        <div class="flex items-center gap-3 pb-3 border-b border-brand-ink/10">
                            <div class="w-9 h-9 rounded-full bg-brand-darkred text-white flex items-center justify-center font-bold text-xs shrink-0">
                                ID
                            </div>
                            <div>
                                <span class="font-editorial-eyebrow text-brand-darkred tracking-eyebrow block text-xs">
                                    RINGKASAN DATA
                                </span>
                                <h3 class="font-editorial-h3 text-brand-ink font-bold text-lg mt-0.5">
                                    Biodata Pendaftaran Siswa
                                </h3>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 text-xs">
                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Kompetensi Keahlian (Jurusan)</span>
                                <span class="text-sm font-bold text-brand-darkred block leading-snug" x-text="searchResult?.jurusan || '-'"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Jalur Seleksi & Jenjang</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="`${searchResult?.jalur_seleksi || searchResult?.jalurSeleksi || '-'} • ${searchResult?.kelas_pilihan || searchResult?.kelasPilihan || '-'}`"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Gelombang Pendaftaran</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="searchResult?.gelombang || 'Gelombang 1'"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Asal Sekolah SMP/MTs</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="searchResult?.asal_sekolah || searchResult?.asalSekolah || '-'"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Jenis Kelamin</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="searchResult?.jenis_kelamin_label || (searchResult?.jenis_kelamin === 'L' ? 'Laki-laki' : 'Perempuan')"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Tempat & Tanggal Lahir</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="`${searchResult?.tempat_lahir || '-'}, ${searchResult?.tanggal_lahir || searchResult?.tanggalLahir || '-'}`"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Nomor HP Siswa</span>
                                <span class="text-sm font-bold text-brand-ink font-mono block" x-text="searchResult?.nomor_kontak_pendaftar || '-'"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Nomor HP Orang Tua</span>
                                <span class="text-sm font-bold text-brand-ink font-mono block" x-text="searchResult?.nomor_kontak_ortu || '-'"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Ukuran Seragam</span>
                                <span class="text-sm font-bold text-brand-darkred block" x-text="searchResult?.ukuran_seragam || '-'"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Tanggal Registrasi</span>
                                <span class="text-sm font-bold text-brand-ink block" x-text="searchResult?.tanggal_daftar || searchResult?.tanggalDaftar || '-'"></span>
                            </div>

                            <div class="p-3.5 rounded-2xl bg-[#F9F8F6] border border-brand-ink/10 space-y-1 sm:col-span-2">
                                <span class="text-[10px] uppercase font-bold text-brand-ink/50 block">Alamat Tempat Tinggal</span>
                                <span class="text-xs text-brand-ink block leading-relaxed" x-text="searchResult?.alamat_lengkap || '-'"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Action Buttons -->
                    <div class="flex flex-col sm:flex-row items-center justify-end gap-3 pt-2 no-print">
                        <button
                            type="button"
                            @click="handleResetSearch()"
                            class="w-full sm:w-auto py-2.5 px-6 rounded-full border border-brand-ink/20 text-brand-ink text-xs font-bold inline-flex items-center justify-center gap-1.5 hover:bg-brand-softmist/50 transition-all cursor-pointer"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <span>Cari NISN Lain</span>
                        </button>

                        <button
                            type="button"
                            onclick="window.print()"
                            class="w-full sm:w-auto py-2.5 px-6 rounded-full bg-brand-ink hover:bg-brand-deepred text-white text-xs font-bold inline-flex items-center justify-center gap-1.5 cursor-pointer shadow-sm transition-all"
                        >
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                            <span>Cetak Bukti</span>
                        </button>

                        <template x-if="searchResult?.id">
                            <a
                                :href="'/ppdb/cetak-kartu/' + searchResult.id"
                                target="_blank"
                                class="w-full sm:w-auto py-2.5 px-6 rounded-full bg-[#B72A32] hover:bg-[#7A1018] text-white text-xs font-bold shadow-softpill inline-flex items-center justify-center gap-1.5 transition-all cursor-pointer"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span>Cetak Kartu Peserta Resmi</span>
                            </a>
                        </template>
                    </div>

                </div>
            </div>

        </div>
    </main>
</div>
@endsection
