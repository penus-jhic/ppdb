{{--
    KHUSUS PENJURIAN: jalan pintas ke dashboard admin PPDB yang tidak ada di navigasi utama, supaya juri
    lomba bisa langsung mencoba kelola data pendaftar, gelombang, pengumuman, dan tarif.
    Bukan untuk production: matikan lewat PPDB_JURI_NAV=false di .env atau hapus komponen ini sebelum rilis resmi.
--}}
@php
    // Middleware verify.auth mengalihkan juri yang belum login ke /ppdb dengan pesan warning,
    // jadi panel dibuka otomatis supaya alasannya terlihat
    $juriWarning = session('warning');

    $juriLinks = [
        ['label' => 'Dashboard & Statistik', 'desc' => 'KPI pendaftar per jurusan, jalur, dan status', 'url' => route('ppdb.dashboard')],
        ['label' => 'Data Pendaftar', 'desc' => 'Filter, cari, detail, edit, dan ubah status seleksi', 'url' => route('ppdb.dashboard.pendaftar')],
        ['label' => 'Export CSV Pendaftar', 'desc' => 'Unduh seluruh data pendaftar (siap dibuka Excel)', 'url' => route('ppdb.dashboard.pendaftar.export')],
        ['label' => 'Gelombang PPDB', 'desc' => 'Kuota, jadwal, biaya formulir, aktifkan gelombang', 'url' => route('ppdb.dashboard.gelombang')],
        ['label' => 'Kelola Pengumuman', 'desc' => 'Terbitkan, pin, dan lampirkan berkas SK', 'url' => route('ppdb.dashboard.pengumuman')],
        ['label' => 'Tarif & Akomodasi', 'desc' => 'Biaya, skema angsuran DSP, rekening, kontak CS', 'url' => route('ppdb.dashboard.akomodasi')],
    ];
@endphp

<div
    x-data="{ juriOpen: {{ $juriWarning ? 'true' : 'false' }} }"
    @keydown.escape.window="juriOpen = false"
    class="fixed bottom-4 left-4 sm:bottom-6 sm:left-6 z-40 print:hidden"
>
    <!-- Panel Navigasi Juri -->
    <div
        x-show="juriOpen"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-2"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-2"
        @click.outside="juriOpen = false"
        id="juri-nav-panel"
        role="dialog"
        aria-label="Navigasi khusus juri"
        class="mb-3 w-[calc(100vw-2rem)] max-w-sm max-h-[75vh] overflow-y-auto overscroll-contain rounded-3xl bg-white border-2 border-dashed border-amber-400 shadow-2xl shadow-brand-ink/20"
        style="display: none;"
    >
        <!-- Header Panel -->
        <div class="flex items-start justify-between gap-3 px-5 pt-5">
            <div>
                <span class="inline-flex items-center rounded-full bg-amber-100 px-2.5 py-0.5 text-[10px] font-bold uppercase tracking-wider text-amber-800">
                    Sementara &middot; Khusus Juri
                </span>
                <h2 class="mt-2 font-display text-lg font-bold uppercase tracking-wide text-brand-ink">Navigasi Juri</h2>
            </div>
            <button
                type="button"
                @click="juriOpen = false"
                class="p-1.5 rounded-full text-brand-ink/60 hover:bg-black/5 hover:text-brand-ink transition-colors cursor-pointer"
                aria-label="Tutup navigasi juri"
            >
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        <!-- Disclaimer -->
        <p role="note" class="mx-5 mt-3 rounded-2xl bg-amber-50 border border-amber-200 px-4 py-3 text-xs leading-relaxed text-amber-900">
            <span class="font-bold">Bukan untuk production.</span>
            Navigasi ini disediakan sementara hanya untuk memudahkan juri mengakses halaman admin PPDB yang memang
            tidak ditampilkan di navigasi utama. Tautan ini akan dihapus saat aplikasi dirilis resmi.
        </p>

        @if ($juriWarning)
            <p role="alert" class="mx-5 mt-3 rounded-2xl bg-brand-signal/10 border border-brand-signal/30 px-4 py-3 text-xs font-semibold leading-relaxed text-brand-darkred">
                {{ $juriWarning }}
            </p>
        @endif

        <!-- Langkah 1: Login -->
        <div class="px-5 mt-4">
            <p class="text-[11px] font-bold uppercase tracking-wider text-brand-ink/50">1. Masuk sebagai admin</p>
            <a
                href="/login"
                target="_blank"
                rel="noopener"
                class="mt-2 flex items-center justify-between gap-3 rounded-2xl bg-brand-darkred px-4 py-2.5 text-sm font-semibold text-white hover:bg-brand-deepred transition-colors"
            >
                <span>Buka Halaman Login Admin</span>
                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
            </a>
            <p class="mt-1.5 text-[11px] leading-snug text-brand-ink/60">
                Dashboard dilindungi Auth Microservice (role ADMIN, KEPALA_SEKOLAH, TU, atau DEVELOPER).
                Setelah login, kembali ke tab ini lalu pilih halaman di bawah.
            </p>
        </div>

        <!-- Langkah 2: Halaman Admin PPDB -->
        <div class="px-5 mt-4 pb-5">
            <p class="text-[11px] font-bold uppercase tracking-wider text-brand-ink/50">2. Halaman admin PPDB</p>
            <ul class="mt-2 space-y-1.5">
                @foreach ($juriLinks as $link)
                    <li>
                        <a
                            href="{{ $link['url'] }}"
                            class="group flex items-center justify-between gap-3 rounded-2xl border border-brand-ink/10 px-4 py-2.5 hover:border-brand-darkred/30 hover:bg-brand-darkred/5 transition-colors"
                        >
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold text-brand-ink group-hover:text-brand-darkred">{{ $link['label'] }}</span>
                                <span class="block text-[11px] leading-snug text-brand-ink/60">{{ $link['desc'] }}</span>
                            </span>
                            <svg class="w-4 h-4 shrink-0 text-brand-ink/40 group-hover:text-brand-darkred transition-transform group-hover:translate-x-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <!-- Tombol Pembuka -->
    <button
        type="button"
        @click="juriOpen = !juriOpen"
        :aria-expanded="juriOpen.toString()"
        aria-controls="juri-nav-panel"
        class="inline-flex items-center gap-2 rounded-full bg-amber-400 hover:bg-amber-300 px-4 py-2.5 text-xs sm:text-sm font-bold text-amber-950 shadow-lg shadow-amber-900/20 ring-2 ring-white transition-all active:scale-95 cursor-pointer"
    >
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
        </svg>
        <span>Navigasi Juri</span>
    </button>
</div>
