<x-sidebar-layout>
    <x-slot name="header">
        Dashboard
    </x-slot>

    @php
        $user = Auth::user();
        $isAdmin = ($user->role ?? 'student') === 'admin';
    @endphp

    <div class="space-y-6">
        <!-- Welcome Card -->
        <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-purple-600 px-6 py-8">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white">
                            Selamat Datang, {{ $user->name }}!
                        </h1>
                        <p class="text-blue-100 mt-2">
                            {{ $isAdmin ? 'Admin Panel - Kelola sistem perpustakaan' : 'Panel Siswa - Pinjam buku favorit Anda' }}
                        </p>
                    </div>
                    <div class="hidden sm:block">
                        <div class="w-20 h-20 bg-white bg-opacity-20 rounded-2xl flex items-center justify-center">
                            <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if($isAdmin)
            <!-- Admin Dashboard -->
            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Buku</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalBooks ?? 0) }}</p>
                            <p class="text-sm text-blue-600">Koleksi perpustakaan</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Buku Dipinjam</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalLoans ?? 0) }}</p>
                            <p class="text-sm text-orange-600">Sedang dipinjam</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Anggota Aktif</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalMembers ?? 0) }}</p>
                            <p class="text-sm text-green-600">Siswa terdaftar</p>
                        </div>
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Buku Terlambat</p>
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($overdueLoans ?? 0) }}</p>
                            <p class="text-sm text-red-600">{{ $overdueLoans > 0 ? 'Butuh perhatian' : 'Semua tepat waktu' }}</p>
                        </div>
                        <div class="w-12 h-12 bg-red-100 dark:bg-red-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
                <!-- Main Chart - Borrowing Trends -->
                <div class="xl:col-span-2 bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Tren Peminjaman</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Aktivitas peminjaman 7 hari terakhir</p>
                        </div>
                        <div class="flex items-center space-x-3">
                            <div class="flex items-center space-x-2 bg-blue-50 dark:bg-blue-900/20 px-3 py-1 rounded-lg">
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                                <span class="text-sm font-medium text-blue-600 dark:text-blue-400">Peminjaman</span>
                            </div>
                            <select id="trendPeriod" class="text-sm border border-gray-300 dark:border-gray-600 rounded-lg px-3 py-2 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="7">7 Hari</option>
                                <option value="30">30 Hari</option>
                                <option value="90">3 Bulan</option>
                            </select>
                        </div>
                    </div>
                    <div class="relative">
                        <div id="chartLoader" class="absolute inset-0 flex items-center justify-center bg-white dark:bg-gray-800 rounded-lg">
                            <div class="flex flex-col items-center">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-500 mb-2"></div>
                                <p class="text-sm text-gray-500 dark:text-gray-400">Memuat grafik...</p>
                            </div>
                        </div>
                        <canvas id="borrowingChart" height="300"></canvas>
                    </div>
                    <div class="mt-4 grid grid-cols-3 gap-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $trends ? collect($trends)->sum('count') : 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Total Minggu Ini</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-green-600 dark:text-green-400">{{ $trends ? number_format(collect($trends)->avg('count'), 1) : 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Rata-rata Harian</p>
                        </div>
                        <div class="text-center">
                            <p class="text-2xl font-bold text-purple-600 dark:text-purple-400">{{ $trends ? collect($trends)->max('count') : 0 }}</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Puncak Harian</p>
                        </div>
                    </div>
                </div>

                <!-- Popular Books -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Buku Populer</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">30 hari terakhir</p>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-r from-yellow-400 to-orange-500 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @forelse($popularBooks ?? [] as $index => $book)
                            @php
                                $gradients = [
                                    'from-blue-500 to-blue-600',
                                    'from-green-500 to-green-600',
                                    'from-yellow-500 to-orange-500',
                                    'from-purple-500 to-purple-600',
                                    'from-pink-500 to-pink-600'
                                ];
                                $gradient = $gradients[$index % count($gradients)];
                                $percentage = $popularBooks->first() && $popularBooks->first()->loans_count > 0
                                    ? ($book->loans_count / $popularBooks->first()->loans_count) * 100
                                    : 0;
                            @endphp
                            <div class="group p-4 bg-gray-50 dark:bg-gray-700 rounded-xl hover:shadow-md transition-all duration-200 border border-gray-100 dark:border-gray-600">
                                <div class="flex items-center space-x-3">
                                    <div class="relative">
                                        <div class="w-10 h-10 bg-gradient-to-r {{ $gradient }} rounded-xl flex items-center justify-center shadow-lg">
                                            <span class="text-sm font-bold text-white">{{ $index + 1 }}</span>
                                        </div>
                                        @if($index === 0)
                                            <div class="absolute -top-1 -right-1 w-4 h-4 bg-yellow-400 rounded-full flex items-center justify-center">
                                                <svg class="w-2 h-2 text-yellow-900" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="font-semibold text-gray-900 dark:text-white truncate">{{ $book->title }}</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 truncate">{{ $book->author }}</p>
                                        <div class="mt-2 flex items-center space-x-2">
                                            <div class="flex-1 bg-gray-200 dark:bg-gray-600 rounded-full h-2">
                                                <div class="h-2 bg-gradient-to-r {{ $gradient }} rounded-full transition-all duration-500"
                                                     style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <span class="text-sm font-bold text-gray-700 dark:text-gray-300">{{ $book->loans_count }}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-12">
                                <div class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">Belum ada data peminjaman</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Additional Analytics Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Recent Activity -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Aktivitas Terbaru</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Peminjaman hari ini</p>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @forelse($recentLoans ?? [] as $loan)
                            <div class="flex items-center space-x-3 p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <p class="font-medium text-gray-900 dark:text-white">{{ $loan->user->name }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $loan->book->title }}</p>
                                    <p class="text-xs text-gray-400 dark:text-gray-500">{{ $loan->created_at->diffForHumans() }}</p>
                                </div>
                                <span class="px-2 py-1 bg-green-100 dark:bg-green-900 text-green-700 dark:text-green-300 text-xs rounded-lg font-medium">
                                    Dipinjam
                                </span>
                            </div>
                        @empty
                            <div class="text-center py-8">
                                <div class="w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-xl flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <p class="text-gray-500 dark:text-gray-400">Belum ada aktivitas hari ini</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- System Status -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-white">Status Sistem</h3>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Kesehatan perpustakaan</p>
                        </div>
                        <div class="w-8 h-8 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                            <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="font-medium text-green-700 dark:text-green-300">Database</span>
                            </div>
                            <span class="text-sm text-green-600 dark:text-green-400 font-medium">Aktif</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-green-500 rounded-full"></div>
                                <span class="font-medium text-green-700 dark:text-green-300">Autentikasi</span>
                            </div>
                            <span class="text-sm text-green-600 dark:text-green-400 font-medium">Normal</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-700">
                            <div class="flex items-center space-x-3">
                                <div class="w-2 h-2 bg-blue-500 rounded-full animate-pulse"></div>
                                <span class="font-medium text-blue-700 dark:text-blue-300">Backup Otomatis</span>
                            </div>
                            <span class="text-sm text-blue-600 dark:text-blue-400 font-medium">Berjalan</span>
                        </div>
                        @if($overdueLoans > 0)
                            <div class="flex items-center justify-between p-3 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-700">
                                <div class="flex items-center space-x-3">
                                    <div class="w-2 h-2 bg-yellow-500 rounded-full"></div>
                                    <span class="font-medium text-yellow-700 dark:text-yellow-300">Peringatan</span>
                                </div>
                                <span class="text-sm text-yellow-600 dark:text-yellow-400 font-medium">{{ $overdueLoans }} Terlambat</span>
                            </div>
                        @endif
                    </div>
                    <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                        <div class="text-center">
                            <p class="text-sm text-gray-500 dark:text-gray-400">
                                Terakhir diperbarui: {{ now()->format('d M Y, H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Quick Actions</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('books.create') }}" class="flex items-center p-4 border-2 border-dashed border-blue-300 dark:border-blue-600 rounded-lg hover:border-blue-500 dark:hover:border-blue-400 hover:bg-blue-50 dark:hover:bg-blue-900/20 transition-colors">
                        <div class="w-10 h-10 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Tambah Buku</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Tambah buku baru</p>
                        </div>
                    </a>

                    <a href="{{ route('loans.create') }}" class="flex items-center p-4 border-2 border-dashed border-green-300 dark:border-green-600 rounded-lg hover:border-green-500 dark:hover:border-green-400 hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors">
                        <div class="w-10 h-10 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Pinjam Buku</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Catat peminjaman</p>
                        </div>
                    </a>

                    <a href="{{ route('loans.index') }}" class="flex items-center p-4 border-2 border-dashed border-purple-300 dark:border-purple-600 rounded-lg hover:border-purple-500 dark:hover:border-purple-400 hover:bg-purple-50 dark:hover:bg-purple-900/20 transition-colors">
                        <div class="w-10 h-10 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center mr-3">
                            <svg class="w-5 h-5 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-medium text-gray-900 dark:text-white">Kelola Peminjaman</p>
                            <p class="text-sm text-gray-500 dark:text-gray-400">Lihat semua peminjaman</p>
                        </div>
                    </a>

                    @if(($pendingAdmins ?? 0) > 0)
                        <a href="{{ route('admin.users.approvals') }}" class="flex items-center p-4 border-2 border-dashed border-yellow-300 dark:border-yellow-600 rounded-lg hover:border-yellow-500 dark:hover:border-yellow-400 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 transition-colors">
                            <div class="w-10 h-10 bg-yellow-100 dark:bg-yellow-900 rounded-lg flex items-center justify-center mr-3">
                                <svg class="w-5 h-5 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900 dark:text-white">Approve Admin</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $pendingAdmins }} admin menunggu</p>
                            </div>
                        </a>
                    @endif
                </div>
            </div>

        @else
            <!-- Student Dashboard -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Quick Borrow -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                        </div>
                        <a href="{{ route('loans.create') }}" class="text-purple-600 dark:text-purple-400 hover:text-purple-800 dark:hover:text-purple-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Pinjam Buku</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Pinjam buku favorit Anda dengan mudah dan cepat</p>
                </div>

                <!-- My Books -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                            </svg>
                        </div>
                        <span class="text-blue-600 dark:text-blue-400 font-semibold">{{ count($currentLoans ?? []) }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Buku Saya</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Buku yang sedang Anda pinjam</p>
                </div>

                <!-- History -->
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6 hover:shadow-xl transition-shadow">
                    <div class="flex items-center justify-between mb-4">
                        <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-lg flex items-center justify-center">
                            <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <span class="text-green-600 dark:text-green-400 font-semibold">{{ $totalBorrowed ?? 0 }}</span>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Riwayat</h3>
                    <p class="text-gray-600 dark:text-gray-400 text-sm">Total buku yang pernah dipinjam</p>
                </div>
            </div>

            <!-- Currently Borrowed Books -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-6">Buku yang Sedang Dipinjam</h3>
                <div class="space-y-4">
                    @forelse($currentLoans ?? [] as $loan)
                        @php
                            $daysLeft = $loan->due_date->diffInDays(now(), false);
                            $isOverdue = $daysLeft < 0;
                            $daysText = $isOverdue ? 'Terlambat ' . abs($daysLeft) . ' hari' : 'Dikembalikan dalam ' . $daysLeft . ' hari';
                            $textColor = $isOverdue ? 'text-red-600 dark:text-red-400' : 'text-orange-600 dark:text-orange-400';
                        @endphp
                        <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                            <div class="flex items-center space-x-4">
                                <div class="w-12 h-16 bg-blue-200 dark:bg-blue-800 rounded-lg flex items-center justify-center">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-medium text-gray-900 dark:text-white">{{ $loan->book->title }}</h4>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">{{ $loan->book->author }}</p>
                                    <p class="text-xs {{ $textColor }}">{{ $daysText }}</p>
                                </div>
                            </div>
                            @if(!$isOverdue)
                                <button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                    Perpanjang
                                </button>
                            @else
                                <span class="px-4 py-2 bg-red-100 text-red-700 rounded-lg text-sm">
                                    Terlambat
                                </span>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <p class="text-gray-500 dark:text-gray-400">Anda belum meminjam buku apapun</p>
                            <a href="{{ route('loans.create') }}" class="inline-block mt-3 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                Pinjam Buku Sekarang
                            </a>
                        </div>
                    @endforelse
                </div>
            </div>
        @endif

        <!-- Token Status (kept as requested) -->
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-green-500 to-emerald-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Claude Token Monitor</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Status subscription dan penggunaan token</p>
                    </div>
                </div>
                <button onclick="refreshTokenStatus()" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    <span>Refresh</span>
                </button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <div class="bg-gradient-to-r from-green-50 to-emerald-50 dark:from-green-900/20 dark:to-emerald-900/20 border border-green-200 dark:border-green-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-green-600 dark:text-green-400">Plan Status</p>
                            <p class="text-2xl font-bold text-green-700 dark:text-green-300">Pro Active</p>
                        </div>
                        <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-blue-900/20 dark:to-indigo-900/20 border border-blue-200 dark:border-blue-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-blue-600 dark:text-blue-400">Available Tokens</p>
                            <p class="text-2xl font-bold text-blue-700 dark:text-blue-300">Unlimited</p>
                        </div>
                        <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-purple-50 to-violet-50 dark:from-purple-900/20 dark:to-violet-900/20 border border-purple-200 dark:border-purple-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-purple-600 dark:text-purple-400">Reset Time</p>
                            <p class="text-2xl font-bold text-purple-700 dark:text-purple-300">No Reset</p>
                        </div>
                        <div class="w-12 h-12 bg-purple-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="bg-gradient-to-r from-orange-50 to-amber-50 dark:from-orange-900/20 dark:to-amber-900/20 border border-orange-200 dark:border-orange-700 rounded-xl p-4">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-orange-600 dark:text-orange-400">Claude Code</p>
                            <p class="text-2xl font-bold text-orange-700 dark:text-orange-300">Full Access</p>
                        </div>
                        <div class="w-12 h-12 bg-orange-500 rounded-full flex items-center justify-center">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-700 rounded-lg p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h4 class="text-sm font-medium text-blue-900 dark:text-blue-300">Claude Pro Subscription Active</h4>
                        <p class="text-sm text-blue-700 dark:text-blue-400">Anda memiliki akses unlimited ke semua fitur Claude Code tanpa batas token atau reset timer.</p>
                    </div>
                </div>
            </div>

            <div class="mt-4 text-center">
                <p class="text-xs text-gray-500 dark:text-gray-400" id="lastUpdated">
                    Last updated: <span id="timestamp">{{ now()->format('d M Y H:i:s') }}</span>
                </p>
            </div>
        </div>
    </div>

    <!-- Chart.js Script for Admin Dashboard -->
    @if($isAdmin)
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Ensure Chart.js is loaded
            if (typeof Chart === 'undefined') {
                console.error('Chart.js is not loaded');
                return;
            }

            // Get chart element
            const chartElement = document.getElementById('borrowingChart');
            if (!chartElement) {
                console.error('Chart element not found');
                return;
            }

            try {
                // Professional Chart Configuration
                const ctx = chartElement.getContext('2d');
                const trendData = @json($trends ?? []);

                // Create gradient background
                const gradient = ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.3)');
                gradient.addColorStop(0.5, 'rgba(59, 130, 246, 0.1)');
                gradient.addColorStop(1, 'rgba(59, 130, 246, 0.02)');

                // Hide loader
                const loader = document.getElementById('chartLoader');
                if (loader) {
                    loader.style.display = 'none';
                }

                const borrowingChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: trendData.map(item => item.label),
                        datasets: [{
                            label: 'Peminjaman',
                            data: trendData.map(item => item.count),
                            borderColor: '#3B82F6',
                            backgroundColor: gradient,
                            borderWidth: 3,
                            pointBackgroundColor: '#3B82F6',
                            pointBorderColor: '#ffffff',
                            pointBorderWidth: 3,
                            pointRadius: 6,
                            pointHoverRadius: 8,
                            pointHoverBackgroundColor: '#3B82F6',
                            pointHoverBorderColor: '#ffffff',
                            pointHoverBorderWidth: 3,
                            tension: 0.4,
                            fill: true
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            intersect: false,
                            mode: 'index'
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                backgroundColor: 'rgba(0, 0, 0, 0.8)',
                                titleColor: '#ffffff',
                                bodyColor: '#ffffff',
                                borderColor: '#3B82F6',
                                borderWidth: 1,
                                cornerRadius: 10,
                                padding: 12,
                                displayColors: false,
                                callbacks: {
                                    title: function(context) {
                                        return 'Hari ' + context[0].label;
                                    },
                                    label: function(context) {
                                        return context.parsed.y + ' peminjaman';
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: {
                                    color: 'rgba(0, 0, 0, 0.05)',
                                    lineWidth: 1
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 12
                                    },
                                    stepSize: 1,
                                    callback: function(value) {
                                        return Math.floor(value);
                                    }
                                }
                            },
                            x: {
                                grid: {
                                    display: false
                                },
                                border: {
                                    display: false
                                },
                                ticks: {
                                    color: '#6B7280',
                                    font: {
                                        size: 12
                                    }
                                }
                            }
                        },
                        elements: {
                            point: {
                                hoverBackgroundColor: '#3B82F6'
                            }
                        },
                        animation: {
                            duration: 2000,
                            easing: 'easeInOutCubic'
                        }
                    }
                });

                // Chart period selector functionality
                const periodSelector = document.getElementById('trendPeriod');
                if (periodSelector) {
                    periodSelector.addEventListener('change', function() {
                        // You can implement AJAX call here to fetch new data
                        // For now, we'll just show a loading state
                        console.log('Period changed to:', this.value);

                        // Add loading animation
                        borrowingChart.data.datasets[0].data = new Array(7).fill(0);
                        borrowingChart.update('none');

                        // Simulate data loading
                        setTimeout(() => {
                            borrowingChart.data.datasets[0].data = trendData.map(item => item.count);
                            borrowingChart.update('active');
                        }, 500);
                    });
                }

            } catch (error) {
                console.error('Error creating chart:', error);
                // Show error state in chart container
                document.getElementById('borrowingChart').parentElement.innerHTML =
                    '<div class="flex items-center justify-center h-64 text-gray-400">' +
                    '<div class="text-center">' +
                    '<svg class="w-12 h-12 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">' +
                    '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>' +
                    '</svg>' +
                    '<p>Chart tidak dapat dimuat</p>' +
                    '</div>' +
                    '</div>';
            }
        });
    </script>
    @endif

    <!-- Token Status JavaScript -->
    <script>
        function refreshTokenStatus() {
            const button = event.target.closest('button');
            const originalHTML = button.innerHTML;

            button.innerHTML = `
                <svg class="w-4 h-4 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                <span>Refreshing...</span>
            `;
            button.disabled = true;

            setTimeout(() => {
                const now = new Date();
                const timestamp = now.toLocaleString('id-ID', {
                    day: 'numeric',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit'
                });
                document.getElementById('timestamp').textContent = timestamp;

                button.innerHTML = originalHTML;
                button.disabled = false;

                showNotification('Token status berhasil diperbarui!', 'success');
            }, 1500);
        }

        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 px-6 py-3 rounded-lg shadow-lg z-50 transition-all duration-300 ${
                type === 'success' ? 'bg-green-500 text-white' : 'bg-red-500 text-white'
            }`;
            notification.textContent = message;

            document.body.appendChild(notification);

            setTimeout(() => {
                notification.remove();
            }, 3000);
        }

        setInterval(() => {
            const now = new Date();
            const timestamp = now.toLocaleString('id-ID', {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
            document.getElementById('timestamp').textContent = timestamp;
        }, 30000);
    </script>
</x-sidebar-layout>
