<x-sidebar-layout>
    <div class="space-y-6" x-data="{ selectedBook: null, searchQuery: '', filteredBooks: @js($books ?? []) }" x-init="
        $watch('searchQuery', value => {
            if (value === '') {
                filteredBooks = @js($books ?? []);
            } else {
                filteredBooks = @js($books ?? []).filter(book =>
                    book.title.toLowerCase().includes(value.toLowerCase()) ||
                    book.author.toLowerCase().includes(value.toLowerCase())
                );
            }
        })
    ">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 3L12 2l1.5 1L15 2l1.5 1L18 2l1.5 1L21 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V2l1.5 1L6 2l1.5 1L9 2z"></path>
                    </svg>
                    Pinjam Buku
                </h1>
                <p class="text-emerald-100 mt-1">Pinjam buku dari koleksi perpustakaan</p>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Buku</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $books->count() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Buku Tersedia</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ $books->where('available_quantity', '>', 0)->count() ?? 0 }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-amber-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Masa Pinjam</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">14 Hari</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
            <div class="p-8">
                <!-- Success Message -->
                @if (session('success'))
                    <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                            </svg>
                            <span class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</span>
                        </div>
                    </div>
                @endif

                <!-- Error Messages -->
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-3 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                            </svg>
                            <div>
                                <h3 class="text-red-800 dark:text-red-200 font-medium mb-2">Terjadi kesalahan:</h3>
                                <ul class="text-red-700 dark:text-red-300 text-sm space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>• {{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Form -->
                <form method="POST" action="{{ route('loans.store') }}" class="space-y-6">
                    @csrf

                    <!-- Book Search & Selection -->
                    <div class="space-y-4">
                        <label class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Pilih Buku <span class="text-red-500">*</span>
                        </label>

                        <!-- Search Input -->
                        <div class="relative">
                            <input
                                type="text"
                                x-model="searchQuery"
                                class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                                placeholder="Cari buku berdasarkan judul atau penulis..."
                            >
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                        </div>

                        <!-- Book Selection -->
                        <div class="relative">
                            <select id="book_id" name="book_id" required
                                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200">
                                <option value="">-- Pilih Buku --</option>
                                @foreach ($books ?? [] as $book)
                                    <option value="{{ $book->id }}"
                                            data-title="{{ $book->title }}"
                                            data-author="{{ $book->author }}"
                                            data-available="{{ $book->available_quantity ?? 0 }}"
                                            {{ old('book_id') == $book->id ? 'selected' : '' }}
                                            {{ ($book->available_quantity ?? 0) <= 0 ? 'disabled' : '' }}>
                                        {{ $book->title }} oleh {{ $book->author }}
                                        @if(($book->available_quantity ?? 0) > 0)
                                            - Tersedia: {{ $book->available_quantity ?? 0 }}
                                        @else
                                            - Tidak Tersedia
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </div>
                        </div>
                        @error('book_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Selected Book Preview -->
                    <div x-show="selectedBook" x-transition class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4 border border-gray-200 dark:border-gray-600">
                        <h4 class="font-medium text-gray-900 dark:text-white mb-2">Buku yang Dipilih:</h4>
                        <div class="flex items-start space-x-4">
                            <div class="flex-shrink-0">
                                <div class="w-12 h-16 bg-gradient-to-br from-blue-500 to-purple-600 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white" x-text="selectedBook?.title"></p>
                                <p class="text-sm text-gray-600 dark:text-gray-400" x-text="'Penulis: ' + selectedBook?.author"></p>
                                <p class="text-sm text-green-600 dark:text-green-400" x-text="'Tersedia: ' + selectedBook?.available + ' eksemplar'"></p>
                            </div>
                        </div>
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label for="due_date" class="block text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            Tanggal Pengembalian <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input
                                id="due_date"
                                type="date"
                                name="due_date"
                                value="{{ old('due_date', date('Y-m-d', strtotime('+14 days'))) }}"
                                min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                max="{{ date('Y-m-d', strtotime('+30 days')) }}"
                                required
                                class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all duration-200"
                            >
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        </div>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Maksimal masa pinjam 30 hari dari hari ini
                        </p>
                        @error('due_date')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Form Actions -->
                    <div class="pt-6 border-t border-gray-200 dark:border-gray-700">
                        <div class="flex flex-col sm:flex-row gap-3 sm:justify-end">
                            <a href="{{ route('dashboard') }}"
                               class="inline-flex justify-center items-center px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 font-medium transition-all duration-200">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batal
                            </a>
                            <button type="submit"
                                    class="inline-flex justify-center items-center px-8 py-3 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-lg hover:from-emerald-700 hover:to-teal-700 focus:ring-4 focus:ring-emerald-300 dark:focus:ring-emerald-800 transition-all duration-200 transform hover:scale-105">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M10.5 3L12 2l1.5 1L15 2l1.5 1L18 2l1.5 1L21 2v7a2 2 0 01-2 2H5a2 2 0 01-2-2V2l1.5 1L6 2l1.5 1L9 2z"></path>
                                </svg>
                                Pinjam Buku
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Information Card -->
        <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl border border-amber-200 dark:border-amber-800 p-6">
            <div class="flex items-start">
                <svg class="w-6 h-6 text-amber-600 dark:text-amber-400 mr-3 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-amber-800 dark:text-amber-200 font-semibold mb-2">Ketentuan Peminjaman:</h3>
                    <ul class="text-amber-700 dark:text-amber-300 text-sm space-y-1">
                        <li>• Maksimal masa peminjaman adalah 30 hari</li>
                        <li>• Buku harus dikembalikan sesuai tanggal yang ditentukan</li>
                        <li>• Keterlambatan pengembalian akan dikenakan denda</li>
                        <li>• Setiap peminjam hanya boleh meminjam maksimal 3 buku</li>
                        <li>• Buku yang rusak atau hilang akan dikenakan ganti rugi</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Book selection preview
        document.getElementById('book_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            if (selectedOption.value) {
                window.dispatchEvent(new CustomEvent('book-selected', {
                    detail: {
                        title: selectedOption.dataset.title,
                        author: selectedOption.dataset.author,
                        available: selectedOption.dataset.available
                    }
                }));
            } else {
                window.dispatchEvent(new CustomEvent('book-deselected'));
            }
        });

        // Alpine.js integration
        document.addEventListener('alpine:init', () => {
            Alpine.data('bookSelection', () => ({
                selectedBook: null,
                init() {
                    this.$nextTick(() => {
                        window.addEventListener('book-selected', (e) => {
                            this.selectedBook = e.detail;
                        });
                        window.addEventListener('book-deselected', () => {
                            this.selectedBook = null;
                        });
                    });
                }
            }));
        });
    </script>
</x-sidebar-layout>
