<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-xl text-slate-800 dark:text-slate-100 leading-tight uppercase tracking-wider">
            {{ __('Manajemen Data Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-50 dark:bg-slate-900 min-h-screen transition-colors duration-200" x-data="{
        editMode: false,
        activeId: null,
        namapegawai: '',
        namalengkap: '',
        jenispegawai: '',
        kdjenispegawai: '',
        produk: '',
        siteId: '',
        partiesId: '',
        openEditModal(pegawai) {
            this.editMode = true;
            this.activeId = pegawai.id;
            this.namapegawai = pegawai.namapegawai;
            this.namalengkap = pegawai.namalengkap;
            this.jenispegawai = pegawai.jenispegawai;
            this.kdjenispegawai = pegawai.kdjenispegawai;
            this.produk = pegawai.produk || 'SIMRS';
            this.siteId = pegawai.site_id || '';
            this.partiesId = pegawai.parties_id || '';
            document.getElementById('pegawai-form').action = '/pegawai/' + pegawai.id;
            document.getElementById('_method').value = 'PUT';
            document.getElementById('form-submit-btn').innerText = 'UPDATE PEGAWAI';
            document.getElementById('cancel-edit-btn').classList.remove('hidden');
            document.getElementById('form-title').innerText = 'Edit Data Pegawai';
            window.scrollTo({top: 0, behavior: 'smooth'});
        },
        resetForm() {
            this.editMode = false;
            this.activeId = null;
            this.namapegawai = '';
            this.namalengkap = '';
            this.jenispegawai = '';
            this.kdjenispegawai = '';
            this.produk = '';
            this.siteId = '';
            this.partiesId = '';
            document.getElementById('pegawai-form').action = '{{ route('pegawai.store') }}';
            document.getElementById('_method').value = 'POST';
            document.getElementById('form-submit-btn').innerText = 'SIMPAN PEGAWAI';
            document.getElementById('cancel-edit-btn').classList.add('hidden');
            document.getElementById('form-title').innerText = 'Tambah Pegawai Baru';
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Alerts -->
            @if(session('success'))
                <div class="p-4 mb-4 text-sm text-lime-800 rounded-2xl bg-lime-50 border border-lime-200 dark:bg-lime-950 dark:text-lime-200 dark:border-lime-900 shadow-sm" role="alert">
                    <span class="font-bold">Sukses!</span> {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="p-4 mb-4 text-sm text-red-800 rounded-2xl bg-red-50 border border-red-200 dark:bg-red-950 dark:text-red-200 dark:border-red-900 shadow-sm" role="alert">
                    <span class="font-bold">Error!</span> {{ $errors->first() }}
                </div>
            @endif

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Form Pegawai -->
                <div class="bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 h-fit transition-all duration-200">
                    <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider mb-4 border-b border-slate-100 dark:border-slate-700 pb-3" id="form-title">
                        Tambah Pegawai Baru
                    </h3>
                    
                    <form id="pegawai-form" method="POST" action="{{ route('pegawai.store') }}" class="space-y-4">
                        @csrf
                        <input type="hidden" name="_method" id="_method" value="POST">

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nama Panggilan</label>
                            <input type="text" name="namapegawai" x-model="namapegawai" required placeholder="Contoh: Fathur" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Nama Lengkap</label>
                            <input type="text" name="namalengkap" x-model="namalengkap" required placeholder="Contoh: Fathur Abdul Halim" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Jenis Pegawai</label>
                            <select name="jenispegawai" x-model="jenispegawai" required class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                                <option value="">-- Pilih Jenis --</option>
                                <option value="Programmer">Programmer (Developer)</option>
                                <option value="Analis">Analis</option>
                                <option value="pic_koordinator">PIC Koordinator</option>
                                <option value="Operator">Operator (Client)</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Kode Jenis Pegawai</label>
                            <input type="text" name="kdjenispegawai" x-model="kdjenispegawai" required placeholder="Contoh: DC atau OS" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Parties / Kelompok</label>
                            <select name="parties_id" x-model="partiesId" required class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                                <option value="">-- Pilih Parties --</option>
                                @foreach($parties as $p_item)
                                    <option value="{{ $p_item->id }}">{{ $p_item->parties }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Produk</label>
                            <input type="text" name="produk" x-model="produk" placeholder="Contoh: SIMRS" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-1.5">Penempatan Site / Faskes</label>
                            <select name="site_id" x-model="siteId" class="w-full text-xs font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all p-2.5">
                                <option value="">-- Pilih Penempatan --</option>
                                @foreach($sites as $s)
                                    <option value="{{ $s->id }}">{{ $s->namasite }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="pt-2 flex flex-col gap-2">
                            <button type="submit" id="form-submit-btn" class="w-full py-2.5 bg-lime-600 hover:bg-lime-700 text-white text-xs font-bold rounded-xl shadow-md transition-all uppercase tracking-wider">
                                SIMPAN PEGAWAI
                            </button>
                            <button type="button" id="cancel-edit-btn" @click="resetForm()" class="hidden w-full py-2 bg-slate-200 hover:bg-slate-300 dark:bg-slate-700 dark:hover:bg-slate-650 text-slate-700 dark:text-slate-200 text-xs font-bold rounded-xl transition-all uppercase tracking-wider">
                                Batal Edit
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Table Pegawai List -->
                <div class="lg:col-span-2 bg-white dark:bg-slate-800 rounded-3xl border border-slate-100 dark:border-slate-700 shadow-xl p-6 transition-all duration-200">
                    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 pb-3 border-b border-slate-100 dark:border-slate-700">
                        <h3 class="text-base font-bold text-slate-800 dark:text-slate-100 uppercase tracking-wider">
                            Daftar Pegawai
                        </h3>
                        <!-- Form Filter Site -->
                        <form method="GET" action="{{ route('pegawai.index') }}" class="flex items-center gap-2">
                            <select name="filter_site_id" class="text-[10px] font-semibold rounded-xl border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-900 dark:text-white focus:ring-lime-500 focus:border-lime-500 transition-all py-1.5 px-3">
                                <option value="">-- Semua Site --</option>
                                @foreach($sites as $s)
                                    <option value="{{ $s->id }}" {{ isset($selectedSiteId) && $selectedSiteId == $s->id ? 'selected' : '' }}>{{ $s->namasite }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="bg-lime-600 hover:bg-lime-700 text-white font-bold text-[10px] px-3.5 py-1.5 rounded-xl uppercase tracking-wider shadow-sm transition-all">
                                Filter
                            </button>
                        </form>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left text-xs border-collapse">
                            <thead>
                                <tr class="bg-slate-50 dark:bg-slate-900 text-slate-500 dark:text-slate-400 font-bold uppercase tracking-wider border-b border-slate-100 dark:border-slate-700">
                                    <th class="py-3.5 px-4 rounded-l-xl">Nama</th>
                                    <th class="py-3.5 px-4">Nama Lengkap</th>
                                    <th class="py-3.5 px-4">Jenis & Parties</th>
                                    <th class="py-3.5 px-4">Produk</th>
                                    <th class="py-3.5 px-4">Penempatan Site</th>
                                    <th class="py-3.5 px-4 text-center rounded-r-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700 text-slate-700 dark:text-slate-200 font-medium">
                                @forelse($pegawais as $p)
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/30 transition-colors">
                                        <td class="py-3.5 px-4 font-bold text-slate-800 dark:text-slate-100">{{ $p->namapegawai }}</td>
                                        <td class="py-3.5 px-4">{{ $p->namalengkap }}</td>
                                        <td class="py-3.5 px-4 whitespace-nowrap">
                                            <span class="inline-block px-2.5 py-1 bg-indigo-50 text-indigo-700 dark:bg-indigo-950/40 dark:text-indigo-300 rounded-lg text-[10px] font-bold uppercase tracking-wide">
                                                {{ $p->jenispegawai }} ({{ trim($p->kdjenispegawai) }})
                                            </span>
                                            @php
                                                $partyItem = $parties->firstWhere('id', $p->parties_id);
                                            @endphp
                                            <div class="text-[10px] text-slate-400 font-semibold mt-1">Parties: {{ $partyItem ? $partyItem->parties : '-' }}</div>
                                        </td>
                                        <td class="py-3.5 px-4">{{ $p->produk }}</td>
                                        <td class="py-3.5 px-4 text-slate-600 dark:text-slate-400 font-semibold">
                                            @php
                                                $sitePenempatan = $sites->firstWhere('id', $p->site_id);
                                            @endphp
                                            {{ $sitePenempatan ? $sitePenempatan->namasite : 'Belum Ditentukan' }}
                                        </td>
                                        <td class="py-3.5 px-4">
                                            <div class="flex justify-center items-center gap-2">
                                                <button @click="openEditModal({
                                                    id: {{ $p->id }},
                                                    namapegawai: '{{ addslashes($p->namapegawai) }}',
                                                    namalengkap: '{{ addslashes($p->namalengkap) }}',
                                                    jenispegawai: '{{ $p->jenispegawai }}',
                                                    kdjenispegawai: '{{ trim($p->kdjenispegawai) }}',
                                                    produk: '{{ $p->produk }}',
                                                    site_id: '{{ $p->site_id }}',
                                                    parties_id: '{{ $p->parties_id }}'
                                                })" class="w-7 h-7 flex items-center justify-center bg-amber-500 hover:bg-amber-600 text-white rounded-lg transition-colors shadow-sm" title="Edit Pegawai">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.83 20.84a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                    </svg>
                                                </button>
                                                <form action="/pegawai/{{ $p->id }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data pegawai ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-7 h-7 flex items-center justify-center bg-rose-600 hover:bg-rose-700 text-white rounded-lg transition-colors shadow-sm" title="Hapus Pegawai">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-3.5 h-3.5">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="py-8 text-center text-slate-400 font-medium">
                                            Tidak ada data pegawai terdaftar.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Pagination Links -->
                    <div class="mt-4">
                        {{ $pegawais->appends(request()->query())->links() }}
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
