<x-guest-layout>
    <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-lg mx-auto">
        <!-- SMK-Friendly Header -->
        <div class="text-center mb-8">
            <div class="flex justify-center mb-6">
                <div class="relative">
                    <div class="w-20 h-20 bg-gradient-to-br from-green-500 via-emerald-600 to-blue-600 rounded-3xl flex items-center justify-center shadow-lg">
                        <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                    </div>
                    <div class="absolute -top-1 -right-1 w-6 h-6 bg-yellow-500 rounded-full flex items-center justify-center">
                        <span class="text-white text-xs font-bold">★</span>
                    </div>
                </div>
            </div>
            <h1 class="text-3xl font-bold text-gray-800 mb-2">🎓 Bergabung dengan SMK</h1>
            <p class="text-gray-600 mb-3">Daftarkan diri Anda di Sistem Perpustakaan Digital</p>
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-gradient-to-r from-green-50 to-blue-50 border border-green-200">
                <svg class="w-4 h-4 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                </svg>
                <span class="text-sm font-medium text-green-700">Gratis & Mudah</span>
            </div>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-6">
            @csrf

            <!-- Name -->
            <div class="space-y-2">
                <label for="name" class="block text-sm font-semibold text-gray-700">
                    👤 Nama Lengkap
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                    </div>
                    <input id="name"
                           type="text"
                           name="name"
                           value="{{ old('name') }}"
                           required
                           autofocus
                           autocomplete="name"
                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-500 transition duration-200 placeholder-gray-400 text-lg"
                           placeholder="Contoh: Ahmad Rizki Pratama">
                </div>
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div class="space-y-2">
                <label for="email" class="block text-sm font-semibold text-gray-700">
                    📧 Email Sekolah/Pribadi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                        </svg>
                    </div>
                    <input id="email"
                           type="email"
                           name="email"
                           value="{{ old('email') }}"
                           required
                           autocomplete="username"
                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-500 transition duration-200 placeholder-gray-400 text-lg"
                           placeholder="nama@gmail.com atau nama@smk.sch.id">
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Role Selection -->
            <div x-data="{ selectedRole: '{{ old('role', 'student') }}' }">
                <label class="block text-sm font-semibold text-gray-700 mb-4">
                    🎯 Pilih Status Anda
                </label>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Student Role -->
                    <div class="relative">
                        <input type="radio"
                               id="role_student"
                               name="role"
                               value="student"
                               x-model="selectedRole"
                               class="sr-only peer"
                               {{ old('role', 'student') === 'student' ? 'checked' : '' }}>
                        <label for="role_student"
                               class="flex flex-col items-center p-6 bg-gradient-to-br from-blue-50 to-indigo-50 border-2 border-blue-200 rounded-2xl cursor-pointer hover:border-blue-400 peer-checked:border-blue-500 peer-checked:bg-gradient-to-br peer-checked:from-blue-100 peer-checked:to-indigo-100 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="w-14 h-14 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2 text-lg">📚 Siswa SMK</h3>
                            <p class="text-sm text-gray-600 text-center mb-3">Akses untuk meminjam dan mengembalikan buku</p>
                            <div class="inline-flex items-center px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                ✅ Langsung Aktif
                            </div>
                        </label>
                    </div>

                    <!-- Admin Role -->
                    <div class="relative">
                        <input type="radio"
                               id="role_admin"
                               name="role"
                               value="admin"
                               x-model="selectedRole"
                               class="sr-only peer"
                               {{ old('role') === 'admin' ? 'checked' : '' }}>
                        <label for="role_admin"
                               class="flex flex-col items-center p-6 bg-gradient-to-br from-purple-50 to-pink-50 border-2 border-purple-200 rounded-2xl cursor-pointer hover:border-purple-400 peer-checked:border-purple-500 peer-checked:bg-gradient-to-br peer-checked:from-purple-100 peer-checked:to-pink-100 transition-all duration-200 shadow-sm hover:shadow-md">
                            <div class="w-14 h-14 bg-gradient-to-r from-purple-500 to-pink-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-gray-900 mb-2 text-lg">👨‍💼 Admin Sekolah</h3>
                            <p class="text-sm text-gray-600 text-center mb-3">Kelola sistem perpustakaan dan laporan</p>
                            <div class="inline-flex items-center px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">
                                ⏳ Perlu Persetujuan
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Role Information -->
                <div class="mt-5 p-5 rounded-2xl"
                     :class="selectedRole === 'admin' ? 'bg-gradient-to-r from-amber-50 to-orange-50 border border-amber-200' : 'bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-200'">
                    <div class="flex items-start">
                        <div class="w-8 h-8 rounded-xl flex items-center justify-center mr-4"
                             :class="selectedRole === 'admin' ? 'bg-amber-100' : 'bg-blue-100'">
                            <svg class="w-5 h-5"
                                 :class="selectedRole === 'admin' ? 'text-amber-600' : 'text-blue-600'"
                                 fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div x-show="selectedRole === 'student'">
                            <h4 class="font-bold text-blue-800 mb-2">✨ Hak Akses Siswa</h4>
                            <ul class="text-sm text-blue-700 space-y-1">
                                <li>🚀 Langsung dapat mengakses sistem setelah verifikasi email</li>
                                <li>📚 Dapat meminjam dan mengembalikan buku</li>
                                <li>📊 Melihat riwayat peminjaman pribadi</li>
                                <li>🔔 Notifikasi batas pengembalian buku</li>
                            </ul>
                        </div>
                        <div x-show="selectedRole === 'admin'">
                            <h4 class="font-bold text-amber-800 mb-2">🛡️ Hak Akses Admin</h4>
                            <ul class="text-sm text-amber-700 space-y-1">
                                <li>⏳ Memerlukan persetujuan dari admin yang sudah ada</li>
                                <li>📋 Setelah disetujui: dapat mengelola buku dan peminjaman</li>
                                <li>📈 Akses ke dashboard analytics dan laporan</li>
                                <li>👥 Dapat menyetujui pendaftaran admin baru</li>
                            </ul>
                        </div>
                    </div>
                </div>
                <x-input-error :messages="$errors->get('role')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="space-y-2">
                <label for="password" class="block text-sm font-semibold text-gray-700">
                    🔐 Kata Sandi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <input id="password"
                           type="password"
                           name="password"
                           required
                           autocomplete="new-password"
                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-500 transition duration-200 placeholder-gray-400 text-lg"
                           placeholder="Minimal 8 karakter">
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div class="space-y-2">
                <label for="password_confirmation" class="block text-sm font-semibold text-gray-700">
                    🔄 Konfirmasi Kata Sandi
                </label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <input id="password_confirmation"
                           type="password"
                           name="password_confirmation"
                           required
                           autocomplete="new-password"
                           class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-2xl focus:outline-none focus:ring-4 focus:ring-green-200 focus:border-green-500 transition duration-200 placeholder-gray-400 text-lg"
                           placeholder="Ulangi kata sandi yang sama">
                </div>
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>

            <!-- Terms and Conditions -->
            <div class="bg-gray-50 rounded-2xl p-5">
                <div class="flex items-start">
                    <input id="terms"
                           type="checkbox"
                           required
                           class="h-5 w-5 text-green-600 focus:ring-green-500 border-gray-300 rounded-lg mt-1 flex-shrink-0">
                    <label for="terms" class="ml-4 text-sm text-gray-700 cursor-pointer">
                        <span class="font-medium">✅ Saya menyetujui</span>
                        <a href="#" class="text-green-600 hover:text-green-800 font-semibold underline">Syarat dan Ketentuan</a>
                        serta
                        <a href="#" class="text-green-600 hover:text-green-800 font-semibold underline">Kebijakan Privasi</a>
                        yang berlaku untuk sistem perpustakaan SMK.
                    </label>
                </div>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    id="registerBtn"
                    class="w-full bg-gradient-to-r from-green-500 via-emerald-600 to-blue-600 text-white py-4 px-6 rounded-2xl font-bold text-lg hover:from-green-600 hover:via-emerald-700 hover:to-blue-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition-all duration-200 transform hover:scale-[1.02] shadow-lg">
                <span class="flex items-center justify-center" id="registerBtnText">
                    🎯 Daftar & Verifikasi Email
                </span>
            </button>

            <!-- Security Note -->
            <div class="bg-gradient-to-r from-green-50 to-blue-50 border border-green-200 rounded-2xl p-4">
                <div class="flex items-start">
                    <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <div class="text-sm">
                        <p class="text-green-800 font-medium">🔐 Verifikasi Email Diperlukan</p>
                        <p class="text-green-700 mt-1">Setelah mendaftar, kami akan mengirim kode verifikasi ke email Anda untuk memastikan keamanan akun.</p>
                    </div>
                </div>
            </div>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-gray-200"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-4 bg-white text-gray-500 font-medium">Sudah punya akun?</span>
                </div>
            </div>

            <!-- Sign In Link -->
            <div class="text-center space-y-3">
                <a href="{{ route('login') }}"
                   class="inline-flex items-center px-6 py-3 border-2 border-green-500 text-green-600 rounded-2xl font-semibold hover:bg-green-50 transition duration-200">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    🔐 Masuk ke Akun Saya
                </a>

                <!-- Test Button (Temporary) -->
                <button type="button" onclick="testModal()" class="text-sm text-gray-500 hover:text-gray-700 underline">
                    Test 2FA Modal
                </button>
            </div>
        </form>
    </div>

    <!-- 2FA Verification Modal -->
    <div id="twoFactorModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md mx-4 transform scale-95 transition-all duration-300" id="modalContent">
            <!-- Modal Header -->
            <div class="text-center mb-6">
                <div class="relative mx-auto w-16 h-16 mb-4">
                    <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg"></div>
                    <div class="absolute inset-0 flex items-center justify-center">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                </div>
                <h3 class="text-2xl font-bold text-gray-900 mb-2">Verifikasi Email</h3>
                <p class="text-gray-600 text-sm">Kode verifikasi telah dikirim ke</p>
                <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 border border-blue-200 mt-2">
                    <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                    </svg>
                    <span class="text-sm font-medium text-blue-700" id="userEmail"></span>
                </div>
            </div>

            <!-- 2FA Form -->
            <form id="twoFactorForm" class="space-y-6">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <!-- Token Input -->
                <div class="space-y-4">
                    <label class="block text-sm font-semibold text-gray-700 text-center">
                        Masukkan Kode Verifikasi 6 Digit
                    </label>

                    <!-- Individual Input Boxes for Each Digit -->
                    <div class="flex justify-center space-x-2" id="modalTokenInputs">
                        <input type="text" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="0">
                        <input type="text" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="1">
                        <input type="text" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="2">
                        <input type="text" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="3">
                        <input type="text" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="4">
                        <input type="text" maxlength="1" class="w-10 h-12 text-center text-xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="5">
                    </div>

                    <!-- Hidden input for form submission -->
                    <input type="hidden" name="token" id="modalToken" required>

                    <div id="tokenError" class="text-red-600 text-sm text-center hidden"></div>
                </div>

                <!-- Timer -->
                <div class="flex justify-center">
                    <div class="relative w-20 h-20">
                        <svg class="w-20 h-20 transform -rotate-90" viewBox="0 0 36 36">
                            <path d="M18 2.0845 A 15.9155 15.9155 0 0 1 18 33.9155 A 15.9155 15.9155 0 0 1 18 2.0845"
                                  fill="none" stroke="#e5e7eb" stroke-width="2"/>
                            <path id="modalTimerProgress" d="M18 2.0845 A 15.9155 15.9155 0 0 1 18 33.9155 A 15.9155 15.9155 0 0 1 18 2.0845"
                                  fill="none" stroke="#3b82f6" stroke-width="2" stroke-dasharray="100, 100"
                                  class="transition-all duration-1000 ease-linear"/>
                        </svg>
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="text-center">
                                <div id="modalTimer" class="text-sm font-bold text-gray-900">10:00</div>
                                <div class="text-xs text-gray-500">tersisa</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="space-y-3">
                    <button type="submit"
                            class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg"
                            id="modalSubmitBtn">
                        <span class="flex items-center justify-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Verifikasi & Daftar
                        </span>
                    </button>

                    <button type="button"
                            onclick="resendModalCode()"
                            id="modalResendBtn"
                            class="w-full bg-gray-100 text-gray-700 py-2 px-4 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                        <span class="flex items-center justify-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Kirim Ulang Kode
                        </span>
                    </button>

                    <button type="button"
                            onclick="closeTwoFactorModal()"
                            class="w-full text-gray-500 py-2 px-4 rounded-xl font-medium hover:text-gray-700 transition-colors">
                        Batalkan
                    </button>
                </div>
            </form>

            <!-- Info Alert -->
            <div class="mt-4 bg-blue-50 border border-blue-200 rounded-xl p-3">
                <div class="flex items-start">
                    <svg class="w-4 h-4 text-blue-500 mt-0.5 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <div class="text-xs">
                        <p class="text-blue-800 font-medium">Tips:</p>
                        <p class="text-blue-700">Kode ini hanya berlaku selama 10 menit dan jangan bagikan kepada siapapun.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Registration form submission
        document.querySelector('form[action="{{ route('register') }}"]').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const registerBtn = document.getElementById('registerBtn');
            const registerBtnText = document.getElementById('registerBtnText');

            // Show loading state
            registerBtn.disabled = true;
            registerBtnText.innerHTML = `
                <svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                </svg>
                Mengirim Kode...
            `;

            fetch('{{ route("register") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('Response status:', response.status);
                return response.json();
            })
            .then(data => {
                console.log('Response data:', data);
                if (data.success) {
                    // Show 2FA modal
                    document.getElementById('userEmail').textContent = formData.get('email');
                    showTwoFactorModal();
                } else {
                    // Handle validation errors
                    if (data.errors) {
                        // Show validation errors
                        Object.keys(data.errors).forEach(key => {
                            const errorElement = document.querySelector(`[name="${key}"]`).closest('.space-y-2').querySelector('.text-red-600');
                            if (errorElement) {
                                errorElement.textContent = data.errors[key][0];
                                errorElement.classList.remove('hidden');
                            }
                        });
                    }
                    if (data.message) {
                        showNotification(data.message, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
            })
            .finally(() => {
                // Reset button
                registerBtn.disabled = false;
                registerBtnText.innerHTML = '🎯 Daftar & Verifikasi Email';
            });
        });

        // Modal functions
        function showTwoFactorModal() {
            const modal = document.getElementById('twoFactorModal');
            const modalContent = document.getElementById('modalContent');

            modal.classList.remove('hidden');
            modal.classList.add('flex');

            setTimeout(() => {
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);

            // Initialize modal components
            initializeModalInputs();
            startModalTimer();
        }

        function closeTwoFactorModal() {
            const modal = document.getElementById('twoFactorModal');
            const modalContent = document.getElementById('modalContent');

            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');

            setTimeout(() => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');

                // Reset form
                clearModalInputs();
                if (modalTimerInterval) {
                    clearInterval(modalTimerInterval);
                }
            }, 300);
        }

        // Modal input handling
        let modalTimerInterval;
        let modalTimeLeft = 600;

        function initializeModalInputs() {
            const inputs = document.querySelectorAll('#modalTokenInputs input');
            const hiddenInput = document.getElementById('modalToken');

            inputs.forEach((input, index) => {
                input.addEventListener('input', function(e) {
                    // Only allow numbers
                    this.value = this.value.replace(/\D/g, '');

                    // Update hidden input
                    updateModalHiddenInput();

                    // Auto-focus next input
                    if (this.value && index < inputs.length - 1) {
                        inputs[index + 1].focus();
                    }

                    // Auto-submit when all filled
                    if (hiddenInput.value.length === 6) {
                        setTimeout(() => {
                            document.getElementById('twoFactorForm').dispatchEvent(new Event('submit'));
                        }, 300);
                    }
                });

                input.addEventListener('keydown', function(e) {
                    // Handle backspace
                    if (e.key === 'Backspace' && !this.value && index > 0) {
                        inputs[index - 1].focus();
                        inputs[index - 1].value = '';
                        updateModalHiddenInput();
                    }
                });

                // Handle paste event
                input.addEventListener('paste', function(e) {
                    e.preventDefault();
                    const paste = (e.clipboardData || window.clipboardData).getData('text');
                    const numbers = paste.replace(/\D/g, '').slice(0, 6);

                    numbers.split('').forEach((num, i) => {
                        if (inputs[i]) {
                            inputs[i].value = num;
                        }
                    });

                    updateModalHiddenInput();
                    if (numbers.length === 6) {
                        setTimeout(() => {
                            document.getElementById('twoFactorForm').dispatchEvent(new Event('submit'));
                        }, 300);
                    }
                });
            });

            // Focus first input
            inputs[0].focus();
        }

        function updateModalHiddenInput() {
            const inputs = document.querySelectorAll('#modalTokenInputs input');
            const hiddenInput = document.getElementById('modalToken');
            hiddenInput.value = Array.from(inputs).map(input => input.value).join('');
        }

        function clearModalInputs() {
            const inputs = document.querySelectorAll('#modalTokenInputs input');
            inputs.forEach(input => {
                input.value = '';
                input.disabled = false;
            });
            document.getElementById('modalToken').value = '';
            document.getElementById('tokenError').classList.add('hidden');
        }

        function startModalTimer() {
            modalTimeLeft = 600;
            const timerElement = document.getElementById('modalTimer');
            const progressRing = document.getElementById('modalTimerProgress');
            const totalTime = 600;

            function updateModalTimer() {
                const minutes = Math.floor(modalTimeLeft / 60);
                const seconds = modalTimeLeft % 60;
                timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

                // Update progress ring
                const progress = (modalTimeLeft / totalTime) * 100;
                progressRing.style.strokeDasharray = `${progress}, 100`;

                // Change color based on remaining time
                if (modalTimeLeft <= 60) {
                    progressRing.style.stroke = '#ef4444'; // red
                    timerElement.classList.add('text-red-600');
                } else if (modalTimeLeft <= 180) {
                    progressRing.style.stroke = '#f59e0b'; // yellow
                    timerElement.classList.add('text-yellow-600');
                }

                if (modalTimeLeft > 0) {
                    modalTimeLeft--;
                } else {
                    timerElement.textContent = '0:00';
                    progressRing.style.stroke = '#ef4444';
                    showNotification('Kode verifikasi telah kadaluarsa. Silakan minta kode baru.', 'error');

                    // Disable inputs
                    const inputs = document.querySelectorAll('#modalTokenInputs input');
                    inputs.forEach(input => input.disabled = true);
                    document.getElementById('modalSubmitBtn').disabled = true;
                }
            }

            modalTimerInterval = setInterval(updateModalTimer, 1000);
            updateModalTimer(); // Initial call
        }

        // 2FA form submission
        document.getElementById('twoFactorForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const submitBtn = document.getElementById('modalSubmitBtn');
            const originalContent = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Memverifikasi...
                </span>
            `;

            fetch('{{ route("2fa.verify") }}', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success || data.redirect) {
                    showNotification('Registrasi berhasil! Mengarahkan...', 'success');
                    setTimeout(() => {
                        window.location.href = data.redirect || '{{ route("dashboard") }}';
                    }, 1500);
                } else {
                    // Handle errors
                    if (data.errors && data.errors.token) {
                        document.getElementById('tokenError').textContent = data.errors.token[0];
                        document.getElementById('tokenError').classList.remove('hidden');
                    }
                    if (data.message) {
                        showNotification(data.message, 'error');
                    }
                    clearModalInputs();
                    const inputs = document.querySelectorAll('#modalTokenInputs input');
                    inputs[0].focus();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
                clearModalInputs();
            })
            .finally(() => {
                // Reset button
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalContent;
            });
        });

        // Resend code function
        let modalResendCooldown = false;

        function resendModalCode() {
            if (modalResendCooldown) return;

            const resendBtn = document.getElementById('modalResendBtn');
            const originalContent = resendBtn.innerHTML;

            resendBtn.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Mengirim...
                </span>
            `;
            resendBtn.disabled = true;
            modalResendCooldown = true;

            fetch('{{ route("2fa.resend") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showNotification(data.message, 'success');
                    // Reset timer
                    modalTimeLeft = 600;
                    const progressRing = document.getElementById('modalTimerProgress');
                    const timerElement = document.getElementById('modalTimer');
                    progressRing.style.stroke = '#3b82f6';
                    timerElement.classList.remove('text-red-600', 'text-yellow-600');

                    // Clear and enable inputs
                    clearModalInputs();
                    const inputs = document.querySelectorAll('#modalTokenInputs input');
                    inputs[0].focus();
                    document.getElementById('modalSubmitBtn').disabled = false;
                } else if (data.error) {
                    showNotification(data.error, 'error');
                }
            })
            .catch(error => {
                showNotification('Terjadi kesalahan saat mengirim ulang kode.', 'error');
            })
            .finally(() => {
                resendBtn.innerHTML = originalContent;
                resendBtn.disabled = false;

                // Set cooldown for 30 seconds
                setTimeout(() => {
                    modalResendCooldown = false;
                }, 30000);
            });
        }

        // Notification system
        function showNotification(message, type = 'info') {
            const notification = document.createElement('div');
            const bgColor = type === 'success' ? 'bg-green-500' : type === 'error' ? 'bg-red-500' : 'bg-blue-500';

            notification.className = `fixed top-4 right-4 ${bgColor} text-white px-6 py-3 rounded-lg shadow-lg z-50 transform translate-x-full transition-transform duration-300`;
            notification.textContent = message;

            document.body.appendChild(notification);

            // Slide in
            setTimeout(() => {
                notification.classList.remove('translate-x-full');
            }, 100);

            // Slide out and remove
            setTimeout(() => {
                notification.classList.add('translate-x-full');
                setTimeout(() => {
                    if (document.body.contains(notification)) {
                        document.body.removeChild(notification);
                    }
                }, 300);
            }, 4000);
        }

        // Test function
        function testModal() {
            document.getElementById('userEmail').textContent = 'test@example.com';
            showTwoFactorModal();
        }
    </script>
</x-guest-layout>
