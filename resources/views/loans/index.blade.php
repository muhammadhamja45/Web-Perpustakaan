<x-sidebar-layout>
    <x-slot name="header">
        {{ Auth::user()->isAdmin() ? 'Daftar Peminjaman' : 'Buku Saya' }}
    </x-slot>
    <div class="space-y-6" x-data="{
        searchQuery: '',
        statusFilter: 'all',
        overdueFilter: false,
        filteredLoans: @js($loans ?? []),
        init() {
            this.filterLoans();
            this.$watch('searchQuery', () => this.filterLoans());
            this.$watch('statusFilter', () => this.filterLoans());
            this.$watch('overdueFilter', () => this.filterLoans());
        },
        filterLoans() {
            let loans = @js($loans ?? []);

            // Search filter
            if (this.searchQuery) {
                loans = loans.filter(loan =>
                    loan.user?.name?.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    loan.book?.title?.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
                    loan.book?.author?.toLowerCase().includes(this.searchQuery.toLowerCase())
                );
            }

            // Status filter
            if (this.statusFilter !== 'all') {
                loans = loans.filter(loan => {
                    if (this.statusFilter === 'borrowed') {
                        return !loan.returned_date;
                    } else if (this.statusFilter === 'returned') {
                        return loan.returned_date;
                    }
                    return true;
                });
            }

            // Overdue filter
            if (this.overdueFilter) {
                const today = new Date().toISOString().split('T')[0];
                loans = loans.filter(loan =>
                    !loan.returned_date && loan.due_date < today
                );
            }

            this.filteredLoans = loans;
        },
        getStatusBadge(loan) {
            if (loan.returned_date) {
                return { class: 'bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300', text: 'Dikembalikan' };
            }

            const today = new Date().toISOString().split('T')[0];
            if (loan.due_date < today) {
                return { class: 'bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300', text: 'Terlambat' };
            }

            return { class: 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-800 dark:text-yellow-300', text: 'Dipinjam' };
        }
    }">
        <!-- Header -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="bg-gradient-to-r from-purple-600 to-pink-600 px-6 py-4">
                <h1 class="text-2xl font-bold text-white flex items-center">
                    <svg class="w-8 h-8 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                    </svg>
                    {{ Auth::user()->isAdmin() ? 'Daftar Peminjaman' : 'Buku Saya' }}
                </h1>
                <p class="text-purple-100 mt-1">
                    @if(Auth::user()->isAdmin())
                        Kelola dan monitor peminjaman buku perpustakaan
                    @else
                        Buku yang sedang Anda pinjam dari perpustakaan
                    @endif
                </p>
            </div>
        </div>

        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Pinjaman</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ count($loans ?? []) }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-yellow-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Sedang Dipinjam</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ collect($loans ?? [])->where('returned_date', null)->count() }}</p>
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
                        <p class="text-sm text-gray-600 dark:text-gray-400">Dikembalikan</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">{{ collect($loans ?? [])->whereNotNull('returned_date')->count() }}</p>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg p-4 border border-gray-200 dark:border-gray-700">
                <div class="flex items-center">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center mr-3">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.464 0L4.268 15.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Terlambat</p>
                        <p class="text-lg font-semibold text-gray-900 dark:text-white">
                            {{ collect($loans ?? [])->filter(function($loan) {
                                return !$loan['returned_date'] && $loan['due_date'] < now()->toDateString();
                            })->count() }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Search -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
            <div class="flex flex-col lg:flex-row gap-4">
                <!-- Search -->
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Cari Peminjaman</label>
                    <div class="relative">
                        <input
                            type="text"
                            x-model="searchQuery"
                            class="w-full px-4 py-3 pl-10 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all duration-200"
                            placeholder="{{ Auth::user()->isAdmin() ? 'Cari berdasarkan nama peminjam, judul buku, atau penulis...' : 'Cari berdasarkan judul buku atau penulis...' }}"
                        >
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Status Filter -->
                <div class="lg:w-48">
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Status</label>
                    <select x-model="statusFilter"
                            class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-purple-500 focus:border-transparent">
                        <option value="all">Semua Status</option>
                        <option value="borrowed">Sedang Dipinjam</option>
                        <option value="returned">Dikembalikan</option>
                    </select>
                </div>

                <!-- Overdue Filter -->
                <div class="lg:w-48 flex items-end">
                    <label class="flex items-center h-12 cursor-pointer">
                        <input type="checkbox" x-model="overdueFilter"
                               class="w-4 h-4 text-purple-600 bg-gray-100 dark:bg-gray-700 border-gray-300 dark:border-gray-600 rounded focus:ring-purple-500 dark:focus:ring-purple-600 focus:ring-2">
                        <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Hanya Terlambat</span>
                    </label>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        @if (session('success'))
            <div class="bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 mr-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <span class="text-green-800 dark:text-green-200 font-medium">{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4">
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

        <!-- Loans Table -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            @if(Auth::user()->isAdmin())
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Peminjam</th>
                            @endif
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Buku</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Tanggal Pinjam</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Jatuh Tempo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        <template x-for="loan in filteredLoans" :key="loan.id">
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors duration-200">
                                @if(Auth::user()->isAdmin())
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-500 to-purple-600 flex items-center justify-center">
                                                    <span class="text-white font-semibold text-sm" x-text="loan.user?.name?.charAt(0)?.toUpperCase()"></span>
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="loan.user?.name"></div>
                                                <div class="text-sm text-gray-500 dark:text-gray-400" x-text="loan.user?.email"></div>
                                            </div>
                                        </div>
                                    </td>
                                @endif
                                <td class="px-6 py-4">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white" x-text="loan.book?.title"></div>
                                    <div class="text-sm text-gray-500 dark:text-gray-400" x-text="'oleh ' + loan.book?.author"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="new Date(loan.borrowed_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })"></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white" x-text="new Date(loan.due_date).toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' })"></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span :class="getStatusBadge(loan).class" class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full" x-text="getStatusBadge(loan).text"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <template x-if="!loan.returned_date">
                                        <form :action="'/loans/' + loan.id + '/return'" method="POST" class="inline-block">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit"
                                                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-white bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 transform hover:scale-105">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Kembalikan
                                            </button>
                                        </form>
                                    </template>
                                    <template x-if="loan.returned_date">
                                        <span class="text-green-600 dark:text-green-400 text-sm font-medium">
                                            ✓ Dikembalikan
                                        </span>
                                    </template>
                                </td>
                            </tr>
                        </template>

                        <!-- Empty State -->
                        <tr x-show="filteredLoans.length === 0">
                            <td colspan="6" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center space-y-3">
                                    <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400 text-sm">Tidak ada peminjaman yang sesuai dengan filter.</p>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl border border-blue-200 dark:border-blue-800 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-blue-800 dark:text-blue-200 font-semibold mb-2">Aksi Cepat</h3>
                    <p class="text-blue-700 dark:text-blue-300 text-sm">Kelola peminjaman dengan mudah</p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('loans.create') }}"
                       class="inline-flex items-center px-4 py-2 bg-gradient-to-r from-blue-600 to-purple-600 text-white font-semibold rounded-lg hover:from-blue-700 hover:to-purple-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-800 transition-all duration-200 transform hover:scale-105">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Pinjam Buku Baru
                    </a>
                    @php
                        $user = Auth::user();
                        $isAdmin = ($user->role ?? 'student') === 'admin';
                    @endphp
                    @if($isAdmin)
                        <a href="{{ route('books.create') }}"
                           class="inline-flex items-center px-4 py-2 border border-blue-300 dark:border-blue-700 text-blue-700 dark:text-blue-300 font-medium rounded-lg hover:bg-blue-100 dark:hover:bg-blue-900/30 transition-all duration-200">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Tambah Buku
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-sidebar-layout>
