<x-guest-layout>
    <!-- Modern Header Section -->
    <div class="text-center mb-8">
        <div class="relative mx-auto w-20 h-20 mb-6">
            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-600 rounded-2xl shadow-lg"></div>
            <div class="absolute inset-0 flex items-center justify-center">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                </svg>
            </div>
        </div>

        <h2 class="text-3xl font-bold text-gray-900 mb-2">Verifikasi Keamanan</h2>
        <div class="space-y-2">
            <p class="text-gray-600">
                @if($type === 'login')
                    Kami telah mengirimkan kode verifikasi ke
                @else
                    Langkah terakhir! Kode verifikasi telah dikirim ke
                @endif
            </p>
            <div class="inline-flex items-center px-3 py-1 rounded-full bg-blue-50 border border-blue-200">
                <svg class="w-4 h-4 text-blue-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                </svg>
                <span class="text-sm font-medium text-blue-700">{{ $email }}</span>
            </div>
        </div>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-6" :status="session('status')" />

    <form method="POST" action="{{ route('2fa.verify') }}" id="verifyForm" class="space-y-6">
        @csrf

        <!-- Modern Token Input -->
        <div class="space-y-4">
            <label class="block text-sm font-semibold text-gray-700 text-center">
                Masukkan Kode Verifikasi 6 Digit
            </label>

            <!-- Individual Input Boxes for Each Digit -->
            <div class="flex justify-center space-x-3" id="tokenInputs">
                <input type="text" maxlength="1" class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="0">
                <input type="text" maxlength="1" class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="1">
                <input type="text" maxlength="1" class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="2">
                <input type="text" maxlength="1" class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="3">
                <input type="text" maxlength="1" class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="4">
                <input type="text" maxlength="1" class="w-12 h-14 text-center text-2xl font-bold border-2 border-gray-300 rounded-xl focus:border-blue-500 focus:ring-0 transition-colors" data-index="5">
            </div>

            <!-- Hidden input for form submission -->
            <input type="hidden" name="token" id="token" required>

            <x-input-error :messages="$errors->get('token')" class="mt-2 text-center" />
        </div>

        <!-- Timer with Progress Ring -->
        <div class="flex justify-center mb-6">
            <div class="relative w-24 h-24">
                <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                    <path d="M18 2.0845 A 15.9155 15.9155 0 0 1 18 33.9155 A 15.9155 15.9155 0 0 1 18 2.0845"
                          fill="none" stroke="#e5e7eb" stroke-width="2"/>
                    <path id="timerProgress" d="M18 2.0845 A 15.9155 15.9155 0 0 1 18 33.9155 A 15.9155 15.9155 0 0 1 18 2.0845"
                          fill="none" stroke="#3b82f6" stroke-width="2" stroke-dasharray="100, 100"
                          class="transition-all duration-1000 ease-linear"/>
                </svg>
                <div class="absolute inset-0 flex items-center justify-center">
                    <div class="text-center">
                        <div id="timer" class="text-lg font-bold text-gray-900">10:00</div>
                        <div class="text-xs text-gray-500">tersisa</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="space-y-4">
            <button type="submit"
                    class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white py-3 px-6 rounded-xl font-semibold text-lg hover:from-blue-700 hover:to-purple-700 transform hover:scale-[1.02] transition-all duration-200 shadow-lg"
                    id="submitBtn">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    {{ $type === 'login' ? 'Verifikasi & Masuk' : 'Verifikasi & Daftar' }}
                </span>
            </button>

            <button type="button"
                    onclick="resendCode()"
                    id="resendBtn"
                    class="w-full bg-gray-100 text-gray-700 py-3 px-6 rounded-xl font-medium hover:bg-gray-200 transition-colors">
                <span class="flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Kirim Ulang Kode
                </span>
            </button>
        </div>

        <div class="text-center pt-4">
            <a href="{{ route('login') }}" class="text-sm text-gray-500 hover:text-gray-700 transition-colors">
                ← Kembali ke Halaman Login
            </a>
        </div>
    </form>

    <!-- Info Alert -->
    <div class="mt-6 bg-blue-50 border border-blue-200 rounded-xl p-4">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-blue-500 mt-0.5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <div class="text-sm">
                <p class="text-blue-800 font-medium">Tips Keamanan:</p>
                <p class="text-blue-700 mt-1">Jangan bagikan kode verifikasi ini kepada siapapun. Kode ini hanya berlaku selama 10 menit.</p>
            </div>
        </div>
    </div>

    <script>
        // Initialize individual input boxes
        const inputs = document.querySelectorAll('#tokenInputs input');
        const hiddenInput = document.getElementById('token');
        const submitBtn = document.getElementById('submitBtn');

        // Handle individual input boxes
        inputs.forEach((input, index) => {
            input.addEventListener('input', function(e) {
                // Only allow numbers
                this.value = this.value.replace(/\D/g, '');

                // Update hidden input
                updateHiddenInput();

                // Auto-focus next input
                if (this.value && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                }

                // Auto-submit when all filled
                if (hiddenInput.value.length === 6) {
                    submitBtn.classList.add('bg-green-600', 'hover:bg-green-700');
                    setTimeout(() => {
                        document.getElementById('verifyForm').submit();
                    }, 300);
                }
            });

            input.addEventListener('keydown', function(e) {
                // Handle backspace
                if (e.key === 'Backspace' && !this.value && index > 0) {
                    inputs[index - 1].focus();
                    inputs[index - 1].value = '';
                    updateHiddenInput();
                }

                // Handle paste
                if (e.key === 'ArrowLeft' && index > 0) {
                    inputs[index - 1].focus();
                }
                if (e.key === 'ArrowRight' && index < inputs.length - 1) {
                    inputs[index + 1].focus();
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

                updateHiddenInput();
                if (numbers.length === 6) {
                    setTimeout(() => {
                        document.getElementById('verifyForm').submit();
                    }, 300);
                }
            });
        });

        function updateHiddenInput() {
            hiddenInput.value = Array.from(inputs).map(input => input.value).join('');
        }

        // Focus first input
        inputs[0].focus();

        // Timer countdown with progress ring
        let timeLeft = 600; // 10 minutes in seconds
        const timerElement = document.getElementById('timer');
        const progressRing = document.getElementById('timerProgress');
        const totalTime = 600;

        function updateTimer() {
            const minutes = Math.floor(timeLeft / 60);
            const seconds = timeLeft % 60;
            timerElement.textContent = `${minutes}:${seconds.toString().padStart(2, '0')}`;

            // Update progress ring
            const progress = (timeLeft / totalTime) * 100;
            progressRing.style.strokeDasharray = `${progress}, 100`;

            // Change color based on remaining time
            if (timeLeft <= 60) {
                progressRing.style.stroke = '#ef4444'; // red
                timerElement.classList.add('text-red-600');
            } else if (timeLeft <= 180) {
                progressRing.style.stroke = '#f59e0b'; // yellow
                timerElement.classList.add('text-yellow-600');
            }

            if (timeLeft > 0) {
                timeLeft--;
            } else {
                timerElement.textContent = '0:00';
                progressRing.style.stroke = '#ef4444';
                showNotification('Kode verifikasi telah kadaluarsa. Silakan minta kode baru.', 'error');

                // Disable inputs
                inputs.forEach(input => input.disabled = true);
                submitBtn.disabled = true;
                submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        }

        // Update timer every second
        const timerInterval = setInterval(updateTimer, 1000);
        updateTimer(); // Initial call

        // Resend code function
        let resendCooldown = false;

        function resendCode() {
            if (resendCooldown) return;

            const resendBtn = document.getElementById('resendBtn');
            const originalContent = resendBtn.innerHTML;

            resendBtn.innerHTML = `
                <span class="flex items-center justify-center">
                    <svg class="animate-spin w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                    </svg>
                    Mengirim...
                </span>
            `;
            resendBtn.disabled = true;
            resendCooldown = true;

            fetch('{{ route("2fa.resend") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    showNotification(data.message, 'success');
                    // Reset timer
                    timeLeft = 600;
                    progressRing.style.stroke = '#3b82f6';
                    timerElement.classList.remove('text-red-600', 'text-yellow-600');

                    // Clear and enable inputs
                    inputs.forEach(input => {
                        input.value = '';
                        input.disabled = false;
                    });
                    hiddenInput.value = '';
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    inputs[0].focus();
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
                    resendCooldown = false;
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
                    document.body.removeChild(notification);
                }, 300);
            }, 4000);
        }
    </script>
</x-guest-layout>