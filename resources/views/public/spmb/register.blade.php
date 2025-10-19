<x-layouts.public>
    <x-slot name="title">Formulir Pendaftaran</x-slot>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <!-- Header -->
            <div class="bg-islamic-green text-white px-8 py-6">
                <h1 class="text-3xl font-bold">Formulir Pendaftaran Mahasiswa Baru</h1>
                <p class="text-islamic-gold mt-2">Tahun Akademik {{ date('Y') }}/{{ date('Y') + 1 }}</p>
            </div>

            <div class="p-8" x-data="registrationForm(@json($draft))">
                <!-- Progress Bar -->
                <div class="mb-8">
                    <div class="flex justify-between items-center mb-2">
                        <span class="text-sm font-semibold text-gray-600">Step <span x-text="currentStep"></span> of 8</span>
                        <span class="text-sm font-semibold text-islamic-green" x-text="Math.round((currentStep / 8) * 100) + '%'"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2">
                        <div class="bg-islamic-green h-2 rounded-full transition-all duration-300" :style="`width: ${(currentStep / 8) * 100}%`"></div>
                    </div>
                </div>

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
                                <h3 class="text-sm font-medium text-red-800">Terdapat beberapa kesalahan:</h3>
                                <div class="mt-2 text-sm text-red-700">
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if (session('success'))
                    <div class="bg-green-50 border-l-4 border-green-500 p-4 mb-6">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-green-700">{{ session('success') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                <form action="{{ route('public.spmb.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $draft->id ?? '' }}">
                    <input type="hidden" name="old_foto" value="{{ $draft->foto ?? '' }}">

                    <!-- Step 1: Pilih Jalur Seleksi -->
                    <div x-show="currentStep === 1" x-transition>
                        <h2 class="text-2xl font-bold text-islamic-green mb-6">Pilih Jalur Seleksi</h2>

                        <div class="space-y-4">
                            @foreach($jalurSeleksis as $jalur)
                                <label class="block cursor-pointer">
                                    <div class="border-2 rounded-lg p-4 transition hover:border-islamic-gold" :class="formData.jalur_seleksi_id == {{ $jalur->id }} ? 'border-islamic-green bg-islamic-cream' : 'border-gray-300'">
                                        <div class="flex items-start">
                                            <input type="radio" name="jalur_seleksi_id" value="{{ $jalur->id }}"
                                                   x-model="formData.jalur_seleksi_id"
                                                   class="mt-1 h-4 w-4 text-islamic-green focus:ring-islamic-green" required>
                                            <div class="ml-3 flex-1">
                                                <div class="font-bold text-islamic-green text-lg">{{ $jalur->nama }}</div>
                                                <p class="text-gray-600 text-sm mt-1">{{ $jalur->deskripsi }}</p>
                                                <div class="mt-2 flex flex-wrap gap-4">
                                                    <span class="text-sm"><strong>Biaya:</strong> Rp {{ number_format($jalur->biaya_pendaftaran, 0, ',', '.') }}</span>
                                                    @if($jalur->kuota_total)
                                                        <span class="text-sm"><strong>Kuota:</strong> {{ $jalur->kuota_total }} orang</span>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    <!-- Step 2: Data Pribadi -->
                    <div x-show="currentStep === 2" x-transition>
                        <h2 class="text-2xl font-bold text-islamic-green mb-6">Data Pribadi</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <input type="text" name="nama" x-model="formData.nama" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">NIK <span class="text-red-500">*</span></label>
                                <input type="text" name="nik" x-model="formData.nik" required maxlength="16" pattern="[0-9]{16}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent"
                                       placeholder="16 digit">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Kelamin <span class="text-red-500">*</span></label>
                                <select name="jenis_kelamin" x-model="formData.jenis_kelamin" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                    <option value="">Pilih</option>
                                    <option value="L">Laki-laki</option>
                                    <option value="P">Perempuan</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tempat Lahir <span class="text-red-500">*</span></label>
                                <input type="text" name="tempat_lahir" x-model="formData.tempat_lahir" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Lahir <span class="text-red-500">*</span></label>
                                <input type="date" name="tanggal_lahir" x-model="formData.tanggal_lahir" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Agama <span class="text-red-500">*</span></label>
                                <select name="agama" x-model="formData.agama" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                    <option value="">Pilih</option>
                                    <option value="Islam">Islam</option>
                                    <option value="Kristen">Kristen</option>
                                    <option value="Katolik">Katolik</option>
                                    <option value="Hindu">Hindu</option>
                                    <option value="Buddha">Buddha</option>
                                    <option value="Konghucu">Konghucu</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="email" x-model="formData.email" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon <span class="text-red-500">*</span></label>
                                <input type="tel" name="phone" x-model="formData.phone" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent"
                                       placeholder="08xx-xxxx-xxxx">
                            </div>
                        </div>
                    </div>

                    <!-- Step 3: Data Alamat -->
                    <div x-show="currentStep === 3" x-transition>
                        <h2 class="text-2xl font-bold text-islamic-green mb-6">Data Alamat</h2>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Alamat Lengkap <span class="text-red-500">*</span></label>
                                <textarea name="alamat" x-model="formData.alamat" required rows="3"
                                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent"></textarea>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kelurahan/Desa <span class="text-red-500">*</span></label>
                                    <input type="text" name="kelurahan" x-model="formData.kelurahan" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kecamatan <span class="text-red-500">*</span></label>
                                    <input type="text" name="kecamatan" x-model="formData.kecamatan" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kota/Kabupaten <span class="text-red-500">*</span></label>
                                    <input type="text" name="kota_kabupaten" x-model="formData.kota_kabupaten" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Provinsi <span class="text-red-500">*</span></label>
                                    <input type="text" name="provinsi" x-model="formData.provinsi" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Kode Pos</label>
                                    <input type="text" name="kode_pos" x-model="formData.kode_pos" maxlength="10"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 4: Data Orang Tua -->
                    <div x-show="currentStep === 4" x-transition>
                        <h2 class="text-2xl font-bold text-islamic-green mb-6">Data Orang Tua</h2>

                        <div class="space-y-6">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ayah <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_ayah" x-model="formData.nama_ayah" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ayah <span class="text-red-500">*</span></label>
                                    <input type="text" name="pekerjaan_ayah" x-model="formData.pekerjaan_ayah" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Ibu <span class="text-red-500">*</span></label>
                                    <input type="text" name="nama_ibu" x-model="formData.nama_ibu" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Pekerjaan Ibu <span class="text-red-500">*</span></label>
                                    <input type="text" name="pekerjaan_ibu" x-model="formData.pekerjaan_ibu" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                </div>

                                <div class="md:col-span-2">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">No. Telepon Orang Tua <span class="text-red-500">*</span></label>
                                    <input type="tel" name="phone_orangtua" x-model="formData.phone_orangtua" required
                                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent"
                                           placeholder="08xx-xxxx-xxxx">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Step 5: Data Pendidikan -->
                    <div x-show="currentStep === 5" x-transition>
                        <h2 class="text-2xl font-bold text-islamic-green mb-6">Data Pendidikan</h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Asal Sekolah <span class="text-red-500">*</span></label>
                                <input type="text" name="asal_sekolah" x-model="formData.asal_sekolah" required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent"
                                       placeholder="SMA/MA/SMK...">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Tahun Lulus <span class="text-red-500">*</span></label>
                                <input type="number" name="tahun_lulus" x-model="formData.tahun_lulus" required
                                       min="1990" max="{{ date('Y') + 1 }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nilai Rata-rata <span class="text-red-500">*</span></label>
                                <input type="number" name="nilai_rata_rata" x-model="formData.nilai_rata_rata" required
                                       min="0" max="100" step="0.01"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent"
                                       placeholder="0.00 - 100.00">
                            </div>
                        </div>
                    </div>

                    <!-- Step 6: Pilihan Program Studi -->
                    <div x-show="currentStep === 6" x-transition>
                        <h2 class="text-2xl font-bold text-islamic-green mb-6">Pilihan Program Studi</h2>

                        <div class="space-y-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan 1 <span class="text-red-500">*</span></label>
                                <select name="program_studi_pilihan_1" x-model="formData.program_studi_pilihan_1" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($programStudis as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Pilihan 2 (Opsional)</label>
                                <select name="program_studi_pilihan_2" x-model="formData.program_studi_pilihan_2"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach($programStudis as $prodi)
                                        <option value="{{ $prodi->id }}">{{ $prodi->nama_prodi }} ({{ $prodi->jenjang }})</option>
                                    @endforeach
                                </select>
                                <p class="text-xs text-gray-500 mt-1">Pilihan 2 harus berbeda dari Pilihan 1</p>
                            </div>
                        </div>
                    </div>

                    <!-- Step 7: Upload Foto -->
                    <div x-show="currentStep === 7" x-transition>
                        <h2 class="text-2xl font-bold text-islamic-green mb-6">Upload Foto</h2>

                        <div class="space-y-6">
                            <div class="bg-blue-50 border-l-4 border-blue-500 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <h3 class="text-sm font-medium text-blue-800">Persyaratan Foto:</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc list-inside space-y-1">
                                                <li>Format: JPG, JPEG, atau PNG</li>
                                                <li>Ukuran maksimal: 500KB</li>
                                                <li>Rasio: 4x6 (portrait)</li>
                                                <li>Pas foto berwarna dengan latar belakang merah/biru</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Upload Pas Foto <span class="text-red-500">*</span></label>
                                <input type="file" name="foto" accept="image/jpeg,image/jpg,image/png"
                                       @change="previewPhoto($event)"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-islamic-green focus:border-transparent">
                            </div>

                            <!-- Photo Preview -->
                            <div x-show="photoPreview" class="text-center">
                                <p class="text-sm font-medium text-gray-700 mb-2">Preview:</p>
                                <img :src="photoPreview" alt="Photo preview" class="max-w-xs mx-auto border-2 border-gray-300 rounded-lg">
                            </div>

                            @if($draft && $draft->foto)
                                <div class="text-center">
                                    <p class="text-sm font-medium text-gray-700 mb-2">Foto Tersimpan:</p>
                                    <img src="{{ Storage::url($draft->foto) }}" alt="Saved photo" class="max-w-xs mx-auto border-2 border-gray-300 rounded-lg">
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Step 8: Review & Submit -->
                    <div x-show="currentStep === 8" x-transition>
                        <h2 class="text-2xl font-bold text-islamic-green mb-6">Review & Konfirmasi</h2>

                        <div class="space-y-6">
                            <div class="bg-yellow-50 border-l-4 border-yellow-500 p-4">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-yellow-400" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm text-yellow-700">
                                            Pastikan semua data yang Anda isi sudah benar. Setelah submit, data tidak dapat diubah kecuali melalui admin.
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="border rounded-lg p-6 space-y-4">
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <span class="font-semibold text-gray-600">Nama:</span>
                                        <p class="text-gray-800" x-text="formData.nama"></p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Email:</span>
                                        <p class="text-gray-800" x-text="formData.email"></p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">NIK:</span>
                                        <p class="text-gray-800" x-text="formData.nik"></p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">No. Telepon:</span>
                                        <p class="text-gray-800" x-text="formData.phone"></p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Asal Sekolah:</span>
                                        <p class="text-gray-800" x-text="formData.asal_sekolah"></p>
                                    </div>
                                    <div>
                                        <span class="font-semibold text-gray-600">Tahun Lulus:</span>
                                        <p class="text-gray-800" x-text="formData.tahun_lulus"></p>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-start">
                                <input type="checkbox" x-model="agreedToTerms"
                                       class="mt-1 h-4 w-4 text-islamic-green focus:ring-islamic-green border-gray-300 rounded">
                                <label class="ml-2 text-sm text-gray-700">
                                    Saya menyatakan bahwa data yang saya isi adalah benar dan dapat dipertanggungjawabkan. Saya bersedia menerima sanksi apabila dikemudian hari terdapat pemalsuan data.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Navigation Buttons -->
                    <div class="flex justify-between mt-8 pt-6 border-t">
                        <button type="button" @click="prevStep"
                                x-show="currentStep > 1"
                                class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-700 font-semibold rounded-lg transition">
                            Sebelumnya
                        </button>

                        <div class="flex gap-3 ml-auto">
                            <button type="button" @click="saveDraft"
                                    x-show="currentStep < 8"
                                    class="px-6 py-2 bg-blue-500 hover:bg-blue-600 text-white font-semibold rounded-lg transition">
                                Simpan Draft
                            </button>

                            <button type="button" @click="nextStep"
                                    x-show="currentStep < 8"
                                    class="px-6 py-2 bg-islamic-green hover:bg-islamic-green-light text-white font-semibold rounded-lg transition">
                                Selanjutnya
                            </button>

                            <button type="submit"
                                    x-show="currentStep === 8"
                                    :disabled="!agreedToTerms"
                                    :class="agreedToTerms ? 'bg-islamic-gold hover:bg-yellow-500' : 'bg-gray-300 cursor-not-allowed'"
                                    class="px-8 py-2 text-islamic-green font-bold rounded-lg transition">
                                Submit Pendaftaran
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function registrationForm(draft) {
            return {
                currentStep: 1,
                agreedToTerms: false,
                photoPreview: null,
                formData: {
                    jalur_seleksi_id: draft?.jalur_seleksi_id || '',
                    nama: draft?.nama || '',
                    email: draft?.email || '',
                    phone: draft?.phone || '',
                    nik: draft?.nik || '',
                    jenis_kelamin: draft?.jenis_kelamin || '',
                    tempat_lahir: draft?.tempat_lahir || '',
                    tanggal_lahir: draft?.tanggal_lahir || '',
                    agama: draft?.agama || '',
                    alamat: draft?.alamat || '',
                    kelurahan: draft?.kelurahan || '',
                    kecamatan: draft?.kecamatan || '',
                    kota_kabupaten: draft?.kota_kabupaten || '',
                    provinsi: draft?.provinsi || '',
                    kode_pos: draft?.kode_pos || '',
                    nama_ayah: draft?.nama_ayah || '',
                    nama_ibu: draft?.nama_ibu || '',
                    pekerjaan_ayah: draft?.pekerjaan_ayah || '',
                    pekerjaan_ibu: draft?.pekerjaan_ibu || '',
                    phone_orangtua: draft?.phone_orangtua || '',
                    asal_sekolah: draft?.asal_sekolah || '',
                    tahun_lulus: draft?.tahun_lulus || '',
                    nilai_rata_rata: draft?.nilai_rata_rata || '',
                    program_studi_pilihan_1: draft?.program_studi_pilihan_1 || '',
                    program_studi_pilihan_2: draft?.program_studi_pilihan_2 || '',
                },

                nextStep() {
                    if (this.currentStep < 8) {
                        this.currentStep++;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                prevStep() {
                    if (this.currentStep > 1) {
                        this.currentStep--;
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                    }
                },

                previewPhoto(event) {
                    const file = event.target.files[0];
                    if (file) {
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.photoPreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                },

                saveDraft() {
                    const form = document.querySelector('form');
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'save_as_draft';
                    input.value = '1';
                    form.appendChild(input);
                    form.submit();
                }
            };
        }
    </script>
    @endpush
</x-layouts.public>
