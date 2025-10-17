<x-guest-layout>
    <!-- Islamic Greeting -->
    <div class="text-center mb-6">
        <p class="arabic-text" style="font-size: 1.5rem; font-family: 'Times New Roman', serif; direction: rtl;">
            بِسْمِ اللهِ الرَّحْمٰنِ الرَّحِيْمِ
        </p>
        <div class="gold-divider"></div>
    </div>

    <!-- Page Title -->
    <div class="text-center mb-6">
        <h3 class="text-2xl font-bold" style="color: #2D5F3F;">Pendaftaran Akun</h3>
        <p class="text-sm text-gray-600 mt-2">Daftarkan akun Anda untuk mengakses SIAKAD</p>
    </div>

    <!-- Multi-Step Form Container -->
    <div x-data="registrationForm()" x-cloak>
        <!-- Step Indicator -->
        <div class="step-indicator mb-8">
            <template x-for="(step, index) in steps" :key="index">
                <div class="step-dot"
                     :class="{
                         'active': currentStep === index + 1,
                         'completed': currentStep > index + 1
                     }">
                </div>
            </template>
        </div>

        <form method="POST" action="{{ route('register') }}" class="space-y-5">
            @csrf

            <!-- Step 1: Basic Account Information -->
            <div x-show="currentStep === 1" x-transition>
                <div class="space-y-5">
                    <!-- Username -->
                    <div>
                        <x-input-label for="username" value="Username *" />
                        <x-text-input id="username" class="block mt-2 w-full" type="text" name="username" :value="old('username')" required autofocus autocomplete="username" placeholder="Masukkan username" />
                        <x-input-error :messages="$errors->get('username')" class="mt-2" />
                        <p class="mt-1 text-xs text-gray-500">Username akan digunakan untuk login</p>
                    </div>

                    <!-- Name -->
                    <div>
                        <x-input-label for="name" value="Nama Lengkap *" />
                        <x-text-input id="name" class="block mt-2 w-full" type="text" name="name" :value="old('name')" required autocomplete="name" placeholder="Masukkan nama lengkap" />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" value="Email *" />
                        <x-text-input id="email" class="block mt-2 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="contoh@email.com" />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" value="Password *" />
                        <x-text-input id="password" class="block mt-2 w-full" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Password *" />
                        <x-text-input id="password_confirmation" class="block mt-2 w-full" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi password" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8">
                    <button type="button" @click="nextStep()" class="w-full islamic-button">
                        Lanjut ke Validasi NIM
                    </button>
                </div>
            </div>

            <!-- Step 2: NIM Validation -->
            <div x-show="currentStep === 2" x-transition>
                <div class="space-y-5">
                    <!-- NIM Input -->
                    <div>
                        <x-input-label for="nim" value="Nomor Induk Mahasiswa (NIM) *" />
                        <x-text-input id="nim" class="block mt-2 w-full" type="text" name="nim" :value="old('nim')" required placeholder="Masukkan NIM Anda" />
                        <x-input-error :messages="$errors->get('nim')" class="mt-2" />
                        <p class="mt-2 text-sm text-gray-600 bg-yellow-50 border border-yellow-200 rounded-md p-3">
                            <svg class="inline w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                            <strong>Penting:</strong> Masukkan Nomor Induk Mahasiswa (NIM) Anda yang terdaftar di STAI AL-FATIH. NIM ini akan digunakan untuk validasi data mahasiswa.
                        </p>
                    </div>

                    <!-- Program Studi -->
                    <div>
                        <x-input-label for="program_studi" value="Program Studi *" />
                        <select id="program_studi" name="program_studi" required class="block mt-2 w-full islamic-input" style="border-color: #d1d5db;">
                            <option value="">Pilih Program Studi</option>
                            <option value="PAI" {{ old('program_studi') == 'PAI' ? 'selected' : '' }}>Pendidikan Agama Islam (PAI)</option>
                            <option value="PIAUD" {{ old('program_studi') == 'PIAUD' ? 'selected' : '' }}>Pendidikan Islam Anak Usia Dini (PIAUD)</option>
                            <option value="MPI" {{ old('program_studi') == 'MPI' ? 'selected' : '' }}>Manajemen Pendidikan Islam (MPI)</option>
                            <option value="EI" {{ old('program_studi') == 'EI' ? 'selected' : '' }}>Ekonomi Islam (EI)</option>
                        </select>
                        <x-input-error :messages="$errors->get('program_studi')" class="mt-2" />
                    </div>

                    <!-- Angkatan -->
                    <div>
                        <x-input-label for="angkatan" value="Angkatan *" />
                        <x-text-input id="angkatan" class="block mt-2 w-full" type="text" name="angkatan" :value="old('angkatan')" required placeholder="Contoh: 2024" />
                        <x-input-error :messages="$errors->get('angkatan')" class="mt-2" />
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="mt-8 space-y-3">
                    <x-primary-button class="w-full">
                        Daftar Sekarang
                    </x-primary-button>
                    <button type="button" @click="prevStep()" class="w-full px-6 py-3 border-2 rounded-md text-sm font-semibold uppercase tracking-widest transition" style="border-color: #4A7C59; color: #4A7C59; background: white;">
                        Kembali
                    </button>
                </div>
            </div>
        </form>

        <!-- Login Link -->
        <div class="text-center mt-6">
            <div class="gold-divider" style="margin: 1.5rem 0;"></div>
            <p class="text-sm text-gray-600">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="islamic-link font-semibold">
                    Masuk di sini
                </a>
            </p>
        </div>
    </div>

    <!-- Alpine.js Script for Multi-Step Form -->
    <script>
        function registrationForm() {
            return {
                currentStep: 1,
                steps: [1, 2],

                nextStep() {
                    // Basic validation before moving to next step
                    const username = document.getElementById('username').value;
                    const name = document.getElementById('name').value;
                    const email = document.getElementById('email').value;
                    const password = document.getElementById('password').value;
                    const passwordConfirmation = document.getElementById('password_confirmation').value;

                    if (!username || !name || !email || !password || !passwordConfirmation) {
                        alert('Mohon lengkapi semua field yang wajib diisi');
                        return;
                    }

                    if (password !== passwordConfirmation) {
                        alert('Password dan konfirmasi password tidak cocok');
                        return;
                    }

                    if (password.length < 8) {
                        alert('Password minimal 8 karakter');
                        return;
                    }

                    if (this.currentStep < this.steps.length) {
                        this.currentStep++;
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                    }
                }
            }
        }
    </script>

    <style>
        [x-cloak] {
            display: none !important;
        }
    </style>
</x-guest-layout>
