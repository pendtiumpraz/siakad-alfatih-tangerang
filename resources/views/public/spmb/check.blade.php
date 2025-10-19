<x-layouts.public>
    <x-slot name="title">Cek Status Pendaftaran</x-slot>

    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-islamic-green text-white px-8 py-6 text-center">
                <div class="inline-block bg-islamic-gold/20 rounded-full p-4 mb-4">
                    <svg class="w-12 h-12 text-islamic-gold" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h1 class="text-3xl font-bold">Cek Status Pendaftaran</h1>
                <p class="text-islamic-gold mt-2">Masukkan nomor pendaftaran Anda untuk melihat status</p>
            </div>

            <div class="p-8">
                <!-- Error Display -->
                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">{{ $errors->first() }}</h3>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Check Form -->
                <form action="{{ route('public.spmb.check.post') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="nomor_pendaftaran" class="block text-sm font-medium text-gray-700 mb-2">
                            Nomor Pendaftaran <span class="text-red-500">*</span>
                        </label>
                        <input type="text"
                               id="nomor_pendaftaran"
                               name="nomor_pendaftaran"
                               value="{{ old('nomor_pendaftaran') }}"
                               required
                               placeholder="REG20250001"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent text-lg">
                        <p class="text-xs text-gray-500 mt-1">Format: REG{Tahun}{Nomor Urut}, contoh: REG20250001</p>
                    </div>

                    <button type="submit"
                            class="w-full bg-islamic-green hover:bg-islamic-green-light text-white font-bold py-4 px-6 rounded-lg shadow-lg transform hover:scale-105 transition duration-200">
                        Cek Status Pendaftaran
                    </button>
                </form>

                <!-- Info Section -->
                <div class="mt-8 pt-8 border-t border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Informasi</h3>
                    <div class="space-y-3 text-sm text-gray-600">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-islamic-green mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p>Nomor pendaftaran dikirimkan melalui email setelah Anda menyelesaikan pendaftaran online.</p>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-islamic-green mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p>Jika Anda lupa nomor pendaftaran, silakan hubungi admin melalui kontak yang tersedia.</p>
                        </div>
                        <div class="flex items-start">
                            <svg class="w-5 h-5 text-islamic-green mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p>Anda dapat mencetak kartu pendaftaran setelah mengecek status.</p>
                        </div>
                    </div>
                </div>

                <!-- Alternative: Continue Draft -->
                <div class="mt-8 pt-8 border-t border-gray-200" x-data="{ showDraftForm: false }">
                    <button @click="showDraftForm = !showDraftForm"
                            class="text-islamic-green hover:text-islamic-green-light font-semibold text-sm flex items-center">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Lanjutkan Draft Pendaftaran
                    </button>

                    <div x-show="showDraftForm" x-transition class="mt-4">
                        <form action="{{ route('public.spmb.register') }}" method="GET" class="space-y-3">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                                <input type="email"
                                       name="email"
                                       required
                                       placeholder="email@example.com"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                            </div>
                            <input type="hidden" name="draft" value="1">
                            <button type="submit"
                                    class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 px-4 rounded-lg transition">
                                Lanjutkan
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Help Section -->
                <div class="mt-8 bg-islamic-cream rounded-lg p-6 text-center">
                    <h3 class="text-lg font-semibold text-islamic-green mb-2">Butuh Bantuan?</h3>
                    <p class="text-gray-700 text-sm mb-4">Tim kami siap membantu Anda</p>
                    <div class="flex flex-col sm:flex-row gap-3 justify-center">
                        <a href="https://wa.me/6281234567890" target="_blank"
                           class="inline-flex items-center justify-center bg-islamic-green hover:bg-islamic-green-light text-white font-semibold py-2 px-4 rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </a>
                        <a href="mailto:info@staialfatih.ac.id"
                           class="inline-flex items-center justify-center bg-white hover:bg-gray-100 text-islamic-green border border-islamic-green font-semibold py-2 px-4 rounded-lg transition">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                            </svg>
                            Email
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
