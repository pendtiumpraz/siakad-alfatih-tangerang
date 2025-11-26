<x-layouts.public>
    <x-slot name="title">Pendaftaran Mahasiswa Baru</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Hero Section -->
        <div class="text-center mb-12">
            <div class="inline-block bg-islamic-gold/20 border-2 border-islamic-gold rounded-full px-6 py-2 mb-4">
                <span class="text-islamic-gold font-semibold">SPMB {{ date('Y') }}/{{ date('Y') + 1 }}</span>
            </div>
            <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">
                Seleksi Penerimaan Mahasiswa Baru
            </h1>
            <p class="text-xl text-white/90 mb-8 max-w-3xl mx-auto">
                Selamat datang di Sistem Pendaftaran Mahasiswa Baru STAI AL-FATIH. Wujudkan impian Anda untuk menuntut ilmu dan menjadi insan yang berakhlak mulia.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('public.spmb.register') }}" class="bg-islamic-gold hover:bg-yellow-500 text-islamic-green font-bold py-4 px-8 rounded-lg shadow-lg transform hover:scale-105 transition duration-200">
                    Daftar Sekarang
                </a>
                <a href="{{ route('public.spmb.check') }}" class="bg-white/10 hover:bg-white/20 backdrop-blur-sm text-white font-semibold py-4 px-8 rounded-lg border-2 border-white/30 hover:border-white/50 transition duration-200">
                    Cek Status Pendaftaran
                </a>
            </div>
        </div>

        <!-- Jalur Seleksi Section -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-islamic-green mb-2">Jalur Seleksi</h2>
                <div class="w-24 h-1 bg-islamic-gold mx-auto"></div>
            </div>

            @if($jalurSeleksis->count() > 0)
                <div class="flex justify-center">
                    @foreach($jalurSeleksis as $jalur)
                        <div class="max-w-2xl w-full border-2 border-islamic-green/20 rounded-xl p-8 hover:shadow-xl hover:border-islamic-gold transition duration-200">
                            <div class="text-center mb-6">
                                <div class="bg-islamic-green text-white rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <h3 class="text-2xl font-bold text-islamic-green mb-2">{{ $jalur->nama }}</h3>
                            </div>

                            <p class="text-gray-600 mb-6 text-center leading-relaxed">{{ $jalur->deskripsi }}</p>

                            <div class="bg-islamic-green/5 rounded-lg p-4 mb-6">
                                <div class="flex justify-center items-center">
                                    <span class="text-gray-600 mr-2">Biaya Pendaftaran:</span>
                                    <span class="text-2xl font-bold text-islamic-green">
                                        @if($jalur->biaya_pendaftaran == 0)
                                            GRATIS
                                        @else
                                            Rp {{ number_format($jalur->biaya_pendaftaran, 0, ',', '.') }}
                                        @endif
                                    </span>
                                </div>
                            </div>

                            <a href="{{ route('public.spmb.register') }}?jalur={{ $jalur->id }}" class="block text-center bg-islamic-green hover:bg-islamic-green-light text-white font-bold py-4 px-6 rounded-lg transition duration-200 transform hover:scale-105">
                                Daftar Sekarang
                            </a>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8">
                    <p class="text-gray-500">Jalur seleksi belum tersedia saat ini. Silakan hubungi admin.</p>
                </div>
            @endif
        </div>

        <!-- Timeline Section -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-islamic-green mb-2">Timeline Pendaftaran</h2>
                <div class="w-24 h-1 bg-islamic-gold mx-auto"></div>
            </div>

            <div class="max-w-3xl mx-auto relative">
                <!-- Timeline Line Background -->
                <div class="absolute left-5 top-5 bottom-5 w-0.5 bg-islamic-gold/20"></div>
                
                <!-- Timeline Items -->
                <div class="relative">
                    <div class="flex gap-4 mb-8">
                        <div class="flex-shrink-0">
                            <div class="bg-islamic-gold text-white rounded-full w-10 h-10 flex items-center justify-center font-bold relative z-10">1</div>
                        </div>
                        <div class="flex-1 pt-1">
                            <h4 class="font-bold text-islamic-green mb-1">Pendaftaran Online</h4>
                            <p class="text-gray-600 text-sm">Isi formulir pendaftaran dan upload dokumen yang diperlukan</p>
                        </div>
                    </div>

                    <div class="flex gap-4 mb-8">
                        <div class="flex-shrink-0">
                            <div class="bg-islamic-gold text-white rounded-full w-10 h-10 flex items-center justify-center font-bold relative z-10">2</div>
                        </div>
                        <div class="flex-1 pt-1">
                            <h4 class="font-bold text-islamic-green mb-1">Pembayaran</h4>
                            <p class="text-gray-600 text-sm">Lakukan pembayaran biaya pendaftaran sesuai jalur yang dipilih</p>
                        </div>
                    </div>

                    <div class="flex gap-4 mb-8">
                        <div class="flex-shrink-0">
                            <div class="bg-islamic-gold text-white rounded-full w-10 h-10 flex items-center justify-center font-bold relative z-10">3</div>
                        </div>
                        <div class="flex-1 pt-1">
                            <h4 class="font-bold text-islamic-green mb-1">Ujian/Seleksi</h4>
                            <p class="text-gray-600 text-sm">Mengikuti ujian atau seleksi sesuai jalur yang dipilih</p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="bg-islamic-gold text-white rounded-full w-10 h-10 flex items-center justify-center font-bold relative z-10">4</div>
                        </div>
                        <div class="flex-1 pt-1">
                            <h4 class="font-bold text-islamic-green mb-1">Pengumuman & Daftar Ulang</h4>
                            <p class="text-gray-600 text-sm">Cek hasil seleksi dan lakukan daftar ulang jika diterima</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Persyaratan Section -->
        <div class="bg-white rounded-2xl shadow-2xl p-8 mb-12">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-bold text-islamic-green mb-2">Persyaratan Pendaftaran</h2>
                <div class="w-24 h-1 bg-islamic-gold mx-auto"></div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto">
                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Lulusan SMA/MA/SMK atau sederajat</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Memiliki Ijazah atau Surat Keterangan Lulus</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Pas foto dengan background sesuai tahun (merah/biru)</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Transkrip nilai (khusus yang memilih prodi S2)</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Fotokopi KTP/Kartu Pelajar</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Fotokopi Kartu Keluarga</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Surat Bukti Mengajar dari sekolah (khusus guru)</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Surat Keterangan RT (khusus dhuafa)</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Surat Keterangan Yatim dari RT (khusus yatim)</p>
                </div>

                <div class="flex items-start gap-3">
                    <div class="bg-islamic-green text-white rounded-full w-6 h-6 min-w-[1.5rem] min-h-[1.5rem] flex items-center justify-center flex-shrink-0 mt-1">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                    </div>
                    <p class="text-gray-700">Sertifikat Quran minimal Juz 30 dan siap di test (khusus penghafal Quran)</p>
                </div>
            </div>
        </div>

        <!-- Contact/Help Section -->
        <div class="bg-islamic-gold rounded-2xl shadow-2xl p-8 text-center">
            <h3 class="text-2xl font-bold text-islamic-green mb-3">Butuh Bantuan?</h3>
            <p class="text-islamic-green/80 mb-6">Tim kami siap membantu Anda dalam proses pendaftaran</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="https://wa.me/{{ $spmbWhatsapp }}" target="_blank" class="bg-islamic-green hover:bg-islamic-green-light text-white font-semibold py-3 px-6 rounded-lg transition duration-200 inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                    </svg>
                    WhatsApp
                </a>
                <a href="mailto:{{ $spmbEmail }}" class="bg-white hover:bg-gray-100 text-islamic-green font-semibold py-3 px-6 rounded-lg transition duration-200 inline-flex items-center justify-center">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                    Email
                </a>
            </div>
        </div>
    </div>
</x-layouts.public>
