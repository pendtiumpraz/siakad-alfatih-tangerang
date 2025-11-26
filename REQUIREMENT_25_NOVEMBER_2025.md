# REQUIREMENT DOCUMENT
## SISTEM AKADEMIK STAI AL-FATIH
### Tanggal: 25 November 2025

---

## 📋 DAFTAR ISI

1. [Sistem KRS (Kartu Rencana Studi)](#1-sistem-krs)
2. [Sistem Jadwal Perkuliahan](#2-sistem-jadwal-perkuliahan) (DONE)
3. [Auto-Assignment Dosen](#3-auto-assignment-dosen) (DONE)
4. [Integrasi Jadwal dengan KRS](#4-integrasi-jadwal-dengan-krs)
5. [Kalender Akademik](#5-kalender-akademik)
6. [Import Master Data (CSV)](#6-import-master-data-csv) (DONE)
7. [Perbaikan Pagination dengan Filter] (#7-perbaikan-pagination-dengan-filter) (DONE)

---

## 1. SISTEM KRS

### 1.1 Overview
Sistem KRS (Kartu Rencana Studi) adalah fitur untuk mahasiswa mengambil mata kuliah per semester.

### 1.2 Business Rules

**A. Auto-populate Mata Kuliah Wajib**
- Ketika mahasiswa buka KRS untuk semester aktif, sistem OTOMATIS memasukkan SEMUA mata kuliah wajib
- Mata kuliah wajib: Berdasarkan kurikulum program studi untuk semester bersangkutan
- Status awal: `draft`
- Field `is_mengulang` = `false`

**B. Pilihan Mata Kuliah Mengulang**
- Sistem menampilkan daftar mata kuliah yang tidak lulus dari semester sebelumnya
- Source: Table `nilais` dengan `status = 'tidak_lulus'`
- Mahasiswa bisa centang untuk mengulang
- Field `is_mengulang` = `true`
- Mahasiswa bisa hapus mata kuliah mengulang (tapi TIDAK bisa hapus mata kuliah wajib)

**C. Validasi Pembayaran**
- Mahasiswa HANYA bisa mengisi KRS jika sudah membayar SPP
- Query: `pembayaran` WHERE `jenis_pembayaran='spp'` AND `status='lunas'`
- Jika belum bayar: Tampilkan halaman blocked

**D. Validasi Jadwal Bentrok**
- Cek bentrok saat pilih mata kuliah mengulang
- Bentrok = Hari sama DAN jam overlapping
- Tampilkan error dengan detail bentrokan

**E. Submit & Approval**
- Submit: Status `draft` → `submitted`, `submitted_at` = now()
- Setelah submit: Tidak bisa edit
- Approval: Admin/Dosen PA approve → Status = `approved`, `approved_at` = now()

**F. Cetak KRS**
- Include: Daftar mata kuliah, SKS, jadwal, total SKS, status
- Format: PDF/print view

### 1.3 Technical Implementation

**Files to Edit:**
- `app/Http/Controllers/Mahasiswa/KrsController.php`
- `resources/views/mahasiswa/krs/index.blade.php`
- `resources/views/mahasiswa/krs/print.blade.php`

**Changes:**
- Auto-populate mata kuliah wajib di `index()`
- Tampilkan jadwal per mata kuliah
- Enhance UI untuk pilih mengulang dengan checkbox + info jadwal

---

## 2. SISTEM JADWAL PERKULIAHAN

### 2.1 Overview
Perubahan: SUPER ADMIN yang membuat SEMUA jadwal, bukan dosen.

### 2.2 Business Rules

**A. Pembuat Jadwal**
- ❌ BUKAN Dosen
- ✅ SUPER ADMIN yang membuat semua jadwal
- Admin assign: Semester, Mata Kuliah, Dosen, Ruangan, Hari, Jam, Kelas

**B. Validasi Jadwal**
- Tidak bentrok ruangan (jika ruangan offline)
- Tidak bentrok dosen (satu dosen tidak bisa di 2 tempat bersamaan)
- Jam operasional: 07:00 - 21:00
- Ruangan online: Bisa overlap (kecuali mata kuliah sama)

**C. View Jadwal**
- **Super Admin**: CRUD semua jadwal
- **Dosen**: VIEW ONLY jadwal mengajar (read-only)
- **Mahasiswa**: VIEW jadwal berdasarkan KRS

### 2.3 Technical Implementation

**A. Hapus Menu Jadwal dari Sidebar Dosen**
- File: `resources/views/layouts/dosen.blade.php`
- Hapus link CRUD jadwal

**B. Update Routes**
```php
// HAPUS dari grup dosen:
Route::resource('jadwal', JadwalController::class);

// PINDAH ke grup admin:
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('jadwal', JadwalController::class);
});

// TAMBAH untuk dosen (view only):
Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::get('jadwal-mengajar', [DosenJadwalViewController::class, 'index']);
});
```

**C. Pindahkan Controller**
- FROM: `app/Http/Controllers/Dosen/JadwalController.php`
- TO: `app/Http/Controllers/Admin/JadwalController.php`
- Update namespace

**D. Buat Controller Baru untuk Dosen (View Only)**
- File: `app/Http/Controllers/Dosen/JadwalViewController.php`
- Hanya fungsi `index()` (read-only)

**E. Pindahkan Views**
- FROM: `resources/views/dosen/jadwal/*`
- TO: `resources/views/admin/jadwal/*`

**F. Buat View Baru untuk Dosen**
- File: `resources/views/dosen/jadwal-mengajar/index.blade.php`
- Table read-only, tidak ada button create/edit/delete

---

## 3. AUTO-ASSIGNMENT DOSEN

### 3.1 Overview
Ketika admin buat jadwal, sistem otomatis assign dosen ke mata kuliah dan program studi (jika belum di-assign).

### 3.2 Business Rules

**A. Auto-assign ke Mata Kuliah**
- Cek: Apakah dosen sudah di-assign ke mata kuliah ini?
- Table: `dosen_mata_kuliah`
- Jika **BELUM**: Insert (dosen_id, mata_kuliah_id)
- Jika **SUDAH**: Skip

**B. Auto-assign ke Program Studi**
- Ambil `program_studi_id` dari mata kuliah (via kurikulum)
- Cek: Apakah dosen sudah di-assign ke prodi ini?
- Table: `dosen_program_studi`
- Jika **BELUM**: Insert (dosen_id, program_studi_id)
- Jika **SUDAH**: Skip

**Benefit:**
- Admin tidak perlu manual assign di User Management
- Assignment otomatis terisi dari jadwal
- Lebih efisien, tidak ada duplikasi

### 3.3 Technical Implementation

**File:** `app/Http/Controllers/Admin/JadwalController.php`

```php
// Method untuk auto-assign dosen
private function autoAssignDosen($dosenId, $mataKuliahId)
{
    $dosen = Dosen::findOrFail($dosenId);
    $mataKuliah = MataKuliah::with('kurikulum')->findOrFail($mataKuliahId);
    
    // 1. Auto-assign ke Mata Kuliah (jika belum)
    if (!$dosen->mataKuliahs()->where('mata_kuliah_id', $mataKuliahId)->exists()) {
        $dosen->mataKuliahs()->attach($mataKuliahId);
    }
    
    // 2. Auto-assign ke Program Studi (jika belum)
    if ($mataKuliah->kurikulum && $mataKuliah->kurikulum->program_studi_id) {
        $prodiId = $mataKuliah->kurikulum->program_studi_id;
        
        if (!$dosen->programStudis()->where('program_studi_id', $prodiId)->exists()) {
            $dosen->programStudis()->attach($prodiId);
        }
    }
}

// Panggil di store()
public function store(Request $request)
{
    // ... validation ...
    $jadwal = Jadwal::create($validated);
    
    // AUTO-ASSIGN DOSEN
    $this->autoAssignDosen($validated['dosen_id'], $validated['mata_kuliah_id']);
    
    return redirect()->route('admin.jadwal.index')
        ->with('success', 'Jadwal berhasil ditambahkan dan dosen berhasil di-assign');
}

// Panggil di update()
public function update(Request $request, $id)
{
    // ... validation ...
    $jadwal->update($validated);
    
    // AUTO-ASSIGN DOSEN
    $this->autoAssignDosen($validated['dosen_id'], $validated['mata_kuliah_id']);
    
    return redirect()->route('admin.jadwal.index')
        ->with('success', 'Jadwal berhasil diperbarui dan dosen berhasil di-assign');
}
```

**Logic Flow:**
1. Admin buat jadwal: Pilih dosen X, mata kuliah Y
2. Sistem cek: Dosen X sudah di-assign ke MK Y? → Jika belum, assign!
3. MK Y milik prodi Z → Dosen X sudah di-assign ke prodi Z? → Jika belum, assign!
4. Jika sudah exist, skip (unique constraint di database)

**Database Tables (Existing):**
- ✅ `dosen_mata_kuliah` (pivot)
- ✅ `dosen_program_studi` (pivot)
- ✅ Unique constraint ada

---

## 4. INTEGRASI JADWAL DENGAN KRS

### 4.1 Overview
Mahasiswa melihat jadwal berdasarkan KRS yang diambil.

### 4.2 Implementation
- Di halaman KRS: Tampilkan jadwal per mata kuliah
- Di halaman Jadwal Mahasiswa: Filter berdasarkan mata kuliah di KRS
- Cetak KRS: Include jadwal lengkap (hari, jam, ruangan, dosen)

---

## 5. KALENDER AKADEMIK

### 5.1 Overview
Fitur untuk mengatur periode-periode penting dalam tahun akademik.

### 5.2 Jenis Event
- Periode KRS
- Periode Pembayaran
- Periode Perkuliahan
- Periode UTS/UAS
- Periode Input Nilai
- Libur (nasional, semester)
- Event Penting (wisuda, orientasi)

### 5.3 Visibility
- **Super Admin**: CRUD semua event
- **Admin/Operator**: View all
- **Dosen**: View event relevan (UTS/UAS, deadline nilai)
- **Mahasiswa**: View event relevan (periode KRS, jadwal ujian, libur)

### 5.4 Database Structure

**Migration:** `database/migrations/YYYY_MM_DD_create_kalender_akademik_table.php`

```php
Schema::create('kalender_akademik', function (Blueprint $table) {
    $table->id();
    $table->foreignId('semester_id')->nullable()->constrained()->onDelete('cascade');
    $table->string('judul', 200);
    $table->enum('jenis_event', [
        'periode_krs',
        'periode_pembayaran',
        'periode_perkuliahan',
        'periode_uts',
        'periode_uas',
        'periode_input_nilai',
        'libur',
        'event_penting',
        'lainnya'
    ]);
    $table->text('deskripsi')->nullable();
    $table->date('tanggal_mulai');
    $table->date('tanggal_selesai');
    $table->time('jam_mulai')->nullable();
    $table->time('jam_selesai')->nullable();
    $table->enum('target_user', ['all', 'mahasiswa', 'dosen', 'admin'])->default('all');
    $table->string('warna', 7)->default('#3B82F6'); // Hex color
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index(['semester_id', 'jenis_event']);
    $table->index('tanggal_mulai');
});
```

### 5.5 Model

**File:** `app/Models/KalenderAkademik.php`

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KalenderAkademik extends Model
{
    protected $table = 'kalender_akademik';
    
    protected $fillable = [
        'semester_id', 'judul', 'jenis_event', 'deskripsi',
        'tanggal_mulai', 'tanggal_selesai', 'jam_mulai', 'jam_selesai',
        'target_user', 'warna', 'is_active',
    ];
    
    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
        'is_active' => 'boolean',
    ];
    
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
    
    // Scopes
    public function scopeForMahasiswa($query)
    {
        return $query->whereIn('target_user', ['all', 'mahasiswa']);
    }
    
    public function scopeForDosen($query)
    {
        return $query->whereIn('target_user', ['all', 'dosen']);
    }
    
    public function scopeForAdmin($query)
    {
        return $query->whereIn('target_user', ['all', 'admin']);
    }
}
```

### 5.6 Controllers

**Admin (CRUD):** `app/Http/Controllers/Admin/KalenderAkademikController.php`
- index, create, store, edit, update, destroy

**Mahasiswa (View):** `app/Http/Controllers/Mahasiswa/KalenderAkademikController.php`
- index only (filter by `target_user`)

**Dosen (View):** `app/Http/Controllers/Dosen/KalenderAkademikController.php`
- index only (filter by `target_user`)

### 5.7 Routes

```php
// Admin (CRUD)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::resource('kalender-akademik', KalenderAkademikController::class);
});

// Mahasiswa (View)
Route::prefix('mahasiswa')->name('mahasiswa.')->group(function () {
    Route::get('kalender-akademik', [MahasiswaKalenderController::class, 'index']);
});

// Dosen (View)
Route::prefix('dosen')->name('dosen.')->group(function () {
    Route::get('kalender-akademik', [DosenKalenderController::class, 'index']);
});
```

### 5.8 Views

**Admin:**
- `resources/views/admin/kalender-akademik/index.blade.php` (table + calendar)
- `resources/views/admin/kalender-akademik/create.blade.php`
- `resources/views/admin/kalender-akademik/edit.blade.php`

**Mahasiswa:**
- `resources/views/mahasiswa/kalender-akademik/index.blade.php` (calendar only)

**Dosen:**
- `resources/views/dosen/kalender-akademik/index.blade.php` (calendar only)

### 5.9 UI Library
**Recommendation:** FullCalendar.js (https://fullcalendar.io/)
- Mature, powerful, banyak fitur
- Support event colors, drag & drop
- Mobile responsive

---

## 6. IMPORT MASTER DATA (CSV)

### 6.1 Overview
Semua master data harus bisa di-import melalui file CSV untuk efisiensi input data bulk.

### 6.2 Master Data yang Perlu Import Feature

**Existing:**
✅ Mahasiswa (sudah ada: `MahasiswaImport.php`)
✅ Dosen (sudah ada: `DosenImport.php`)

**Need to Create:**
1. ❌ Program Studi
2. ❌ Mata Kuliah
3. ❌ Kurikulum
4. ❌ Ruangan
5. ❌ Semester
6. ❌ Jadwal
7. ❌ Nilai
8. ❌ Pembayaran
9. ❌ Pengumuman
10. ❌ Jalur Seleksi (SPMB)

### 6.3 Technical Implementation

**A. Laravel Excel Package**
- Already installed: `maatwebsite/excel`
- Use: `Maatwebsite\Excel\Concerns\ToModel`
- Use: `Maatwebsite\Excel\Concerns\WithHeadingRow`
- Use: `Maatwebsite\Excel\Concerns\WithValidation`

**B. Import Class Structure**

Template untuk setiap import class:

```php
<?php

namespace App\Imports;

use App\Models\ModelName;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\Rule;

class ModelNameImport implements ToModel, WithHeadingRow, WithValidation
{
    public function model(array $row)
    {
        return new ModelName([
            'field1' => $row['field1'],
            'field2' => $row['field2'],
            // ...
        ]);
    }
    
    public function rules(): array
    {
        return [
            'field1' => 'required|string|max:255',
            'field2' => 'required|string',
            // ...
        ];
    }
    
    public function customValidationMessages()
    {
        return [
            'field1.required' => 'Field 1 harus diisi',
            // ...
        ];
    }
}
```

**C. Controller Implementation**

Tambahkan method di controller masing-masing:

```php
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ModelNameImport;

public function import(Request $request)
{
    $request->validate([
        'file' => 'required|mimes:csv,xlsx,xls|max:2048',
    ]);
    
    try {
        Excel::import(new ModelNameImport, $request->file('file'));
        
        return redirect()->back()
            ->with('success', 'Data berhasil di-import!');
            
    } catch (\Maatwebsite\Excel\Validators\ValidationException $e) {
        $failures = $e->failures();
        
        return redirect()->back()
            ->with('error', 'Import gagal. Cek format file CSV.')
            ->with('failures', $failures);
    } catch (\Exception $e) {
        return redirect()->back()
            ->with('error', 'Import gagal: ' . $e->getMessage());
    }
}

public function downloadTemplate()
{
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="template_model_name.csv"',
    ];
    
    $columns = ['field1', 'field2', 'field3']; // Header CSV
    
    $callback = function() use ($columns) {
        $file = fopen('php://output', 'w');
        fputcsv($file, $columns);
        
        // Optional: Add example row
        fputcsv($file, ['Contoh 1', 'Contoh 2', 'Contoh 3']);
        
        fclose($file);
    };
    
    return response()->stream($callback, 200, $headers);
}
```

**D. Routes**

Tambahkan routes untuk setiap master data:

```php
Route::prefix('admin')->name('admin.')->group(function () {
    // Program Studi
    Route::post('program-studi/import', [ProgramStudiController::class, 'import'])->name('program-studi.import');
    Route::get('program-studi/template', [ProgramStudiController::class, 'downloadTemplate'])->name('program-studi.template');
    
    // Mata Kuliah
    Route::post('mata-kuliah/import', [MataKuliahController::class, 'import'])->name('mata-kuliah.import');
    Route::get('mata-kuliah/template', [MataKuliahController::class, 'downloadTemplate'])->name('mata-kuliah.template');
    
    // Ruangan
    Route::post('ruangan/import', [RuanganController::class, 'import'])->name('ruangan.import');
    Route::get('ruangan/template', [RuanganController::class, 'downloadTemplate'])->name('ruangan.template');
    
    // Semester
    Route::post('semester/import', [SemesterController::class, 'import'])->name('semester.import');
    Route::get('semester/template', [SemesterController::class, 'downloadTemplate'])->name('semester.template');
    
    // Jadwal
    Route::post('jadwal/import', [JadwalController::class, 'import'])->name('jadwal.import');
    Route::get('jadwal/template', [JadwalController::class, 'downloadTemplate'])->name('jadwal.template');
    
    // Nilai
    Route::post('nilai/import', [NilaiController::class, 'import'])->name('nilai.import');
    Route::get('nilai/template', [NilaiController::class, 'downloadTemplate'])->name('nilai.template');
    
    // Pembayaran
    Route::post('pembayaran/import', [PembayaranController::class, 'import'])->name('pembayaran.import');
    Route::get('pembayaran/template', [PembayaranController::class, 'downloadTemplate'])->name('pembayaran.template');
    
    // Pengumuman
    Route::post('pengumuman/import', [PengumumanController::class, 'import'])->name('pengumuman.import');
    Route::get('pengumuman/template', [PengumumanController::class, 'downloadTemplate'])->name('pengumuman.template');
});
```

**E. View Implementation**

Tambahkan form import di setiap index view:

```blade
<!-- Import Section -->
<div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
    <div class="flex items-center justify-between">
        <div>
            <h3 class="text-lg font-semibold text-blue-800">Import Data</h3>
            <p class="text-sm text-blue-600">Upload file CSV untuk import data bulk</p>
        </div>
        <div class="flex space-x-2">
            <a href="{{ route('admin.model-name.template') }}" 
               class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                <i class="fas fa-download mr-2"></i>Download Template
            </a>
            <button type="button" onclick="document.getElementById('importForm').classList.toggle('hidden')" 
                    class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                <i class="fas fa-upload mr-2"></i>Import Data
            </button>
        </div>
    </div>
    
    <!-- Import Form (Hidden by default) -->
    <form id="importForm" action="{{ route('admin.model-name.import') }}" method="POST" 
          enctype="multipart/form-data" class="mt-4 hidden">
        @csrf
        <div class="flex items-center space-x-2">
            <input type="file" name="file" accept=".csv,.xlsx,.xls" required
                   class="flex-1 px-3 py-2 border border-gray-300 rounded-lg">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Upload & Import
            </button>
        </div>
        <p class="text-xs text-gray-500 mt-2">Format: CSV, XLSX, XLS (Max: 2MB)</p>
    </form>
    
    <!-- Show Import Errors -->
    @if(session('failures'))
        <div class="mt-4 p-3 bg-red-100 border border-red-300 rounded">
            <p class="font-semibold text-red-800">Import Errors:</p>
            <ul class="list-disc list-inside text-sm text-red-700">
                @foreach(session('failures') as $failure)
                    <li>Row {{ $failure->row() }}: {{ implode(', ', $failure->errors()) }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</div>
```

### 6.4 CSV Template Format

**Example: Program Studi**
```csv
kode_prodi,nama_prodi,jenjang,akreditasi,ketua_prodi_nidn
S1-TI,Teknik Informatika,S1,A,0123456789
S1-SI,Sistem Informasi,S1,B,0987654321
```

**Example: Mata Kuliah**
```csv
kode_mk,nama_mk,sks,semester,jenis,program_studi_kode
MK001,Pemrograman Dasar,3,1,wajib,S1-TI
MK002,Basis Data,3,2,wajib,S1-TI
```

**Example: Ruangan**
```csv
kode_ruangan,nama_ruangan,kapasitas,jenis,is_available
R101,Ruang 101,40,offline,1
R-ZOOM1,Zoom Room 1,100,online,1
```

**Example: Jadwal**
```csv
semester_tahun_akademik,mata_kuliah_kode,dosen_nidn,ruangan_kode,hari,jam_mulai,jam_selesai,kelas
2025/2026 Ganjil,MK001,0123456789,R101,Senin,08:00,10:00,A
2025/2026 Ganjil,MK002,0987654321,R102,Selasa,10:00,12:00,A
```

### 6.5 Files to Create

**Import Classes:**
1. `app/Imports/ProgramStudiImport.php`
2. `app/Imports/MataKuliahImport.php`
3. `app/Imports/KurikulumImport.php`
4. `app/Imports/RuanganImport.php`
5. `app/Imports/SemesterImport.php`
6. `app/Imports/JadwalImport.php`
7. `app/Imports/NilaiImport.php`
8. `app/Imports/PembayaranImport.php`
9. `app/Imports/PengumumanImport.php`
10. `app/Imports/JalurSeleksiImport.php`

**Controller Updates:**
- Tambahkan method `import()` dan `downloadTemplate()` ke setiap controller

**View Updates:**
- Tambahkan section import di setiap index view

---

## 7. PERBAIKAN PAGINATION DENGAN FILTER

### 7.1 Overview
Ketika user melakukan filter lalu pindah halaman pagination, filter harus tetap aktif (tidak reset).

### 7.2 Problem

**Current Issue:**
```php
// Pagination TANPA preserve filter
$users = $query->paginate(15);

// Result: Ketika klik page 2, filter hilang
```

### 7.3 Solution

**Fix: Gunakan `withQueryString()`**
```php
// Pagination DENGAN preserve filter
$users = $query->paginate(15)->withQueryString();

// Result: Ketika klik page 2, filter tetap aktif
```

### 7.4 Controllers yang Perlu Diperbaiki

**Analysis dari existing code:**

✅ **Already Fixed:**
- `SPMBController.php` → `->paginate(15)->withQueryString()`
- `DaftarUlangController.php` → `->paginate(20)->withQueryString()`

❌ **Need to Fix:**
1. `SuperAdminController.php` → `->paginate(15)` (User management)
2. `PengurusController.php` → `->paginate(20)` (Mahasiswa list)
3. `PengumumanController.php` → `->paginate(15)` (Pengumuman)
4. `KhsController.php` → `->paginate(20)` (KHS)
5. `JalurSeleksiController.php` → `->paginate(10)` (Jalur Seleksi)

**Plus all other admin controllers:**
- `ProgramStudiController.php`
- `MataKuliahController.php`
- `KurikulumController.php`
- `RuanganController.php`
- `SemesterController.php`
- `JadwalController.php` (new admin)
- `NilaiController.php`
- `PembayaranController.php`
- `DosenController.php` (if any)
- `MahasiswaController.php` (if any)

### 7.5 Implementation

**Before:**
```php
public function index(Request $request)
{
    $query = Model::query();
    
    // Filters
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    $items = $query->paginate(15); // ❌ Filter hilang saat pagination
    
    return view('admin.model.index', compact('items'));
}
```

**After:**
```php
public function index(Request $request)
{
    $query = Model::query();
    
    // Filters
    if ($request->filled('search')) {
        $query->where('name', 'like', '%' . $request->search . '%');
    }
    
    if ($request->filled('status')) {
        $query->where('status', $request->status);
    }
    
    $items = $query->paginate(15)->withQueryString(); // ✅ Filter preserved
    
    return view('admin.model.index', compact('items'));
}
```

### 7.6 Verification

**Test Steps:**
1. Buka halaman dengan filter (e.g., User Management)
2. Apply filter (e.g., search "Ahmad", role "mahasiswa")
3. Klik pagination (page 2, 3, dst)
4. **Expected**: Filter tetap aktif (masih search "Ahmad", role "mahasiswa")
5. **Before fix**: Filter reset (kembali show all users)

### 7.7 Files to Edit

**Controllers:**
1. `app/Http/Controllers/Admin/SuperAdminController.php`
2. `app/Http/Controllers/Admin/PengurusController.php`
3. `app/Http/Controllers/Admin/PengumumanController.php`
4. `app/Http/Controllers/Admin/KhsController.php`
5. `app/Http/Controllers/Admin/JalurSeleksiController.php`
6. Plus: All other admin controllers with pagination

**Changes:**
- Find: `->paginate(X)`
- Replace: `->paginate(X)->withQueryString()`

**Total Estimated Files:** 15-20 controllers

---

## 📝 SUMMARY

### Total Features: 7

1. ✅ **Sistem KRS** - Auto-populate mata kuliah wajib + pilih mengulang
2. ✅ **Sistem Jadwal** - Super Admin yang buat, bukan dosen
3. ✅ **Auto-Assignment Dosen** - Otomatis assign ke MK & Prodi dari jadwal
4. ✅ **Integrasi Jadwal-KRS** - Mahasiswa lihat jadwal dari KRS
5. ✅ **Kalender Akademik** - Periode akademik untuk semua user
6. ✅ **Import Master Data** - CSV import untuk 10 master data
7. ✅ **Perbaikan Pagination** - Filter preserved saat pagination

### Files Summary

**New Files (Create):**
- 1 migration (kalender_akademik)
- 1 model (KalenderAkademik)
- 3 controllers (Admin, Mahasiswa, Dosen kalender)
- 10 import classes (ProgramStudi, MataKuliah, Kurikulum, etc.)
- 1 controller (DosenJadwalViewController)
- 5 views (kalender akademik)
- 1 view (dosen jadwal-mengajar)

**Total New Files:** ~22 files

**Files to Move:**
- 1 controller (Dosen/JadwalController → Admin/JadwalController)
- 3 views (dosen/jadwal/* → admin/jadwal/*)

**Files to Edit:**
- 3 KRS files (controller + 2 views)
- 1 Jadwal controller (tambah auto-assign logic)
- 3 layout files (admin, dosen, mahasiswa sidebars)
- 1 routes file
- 15-20 controller files (tambah ->withQueryString())
- 10 admin index views (tambah import section)

**Total Edit Files:** ~35-40 files

---

## 🔄 URUTAN IMPLEMENTASI

### Phase 1: Jadwal ke Admin + Auto-Assignment (HIGH PRIORITY)
**Estimasi: 2-3 jam**

1. Pindahkan JadwalController dari Dosen ke Admin
2. Tambah logic auto-assignment dosen
3. Pindahkan views jadwal
4. Update routes
5. Buat JadwalViewController untuk Dosen (view only)
6. Update sidebar Dosen & Admin
7. Testing auto-assignment

### Phase 2: Enhance KRS System (HIGH PRIORITY)
**Estimasi: 2-3 jam**

1. Update KrsController (auto-populate mata kuliah wajib)
2. Enhance views (jadwal per mata kuliah, UI mengulang)
3. Improve print KRS
4. Testing validasi pembayaran & bentrok

### Phase 3: Perbaikan Pagination (QUICK WIN)
**Estimasi: 1 jam**

1. Find & replace `->paginate(X)` dengan `->paginate(X)->withQueryString()`
2. Test di semua halaman admin dengan filter
3. Verify filter preserved

### Phase 4: Import Master Data (MEDIUM PRIORITY)
**Estimasi: 4-5 jam**

1. Buat 10 import classes
2. Update 10 controllers (tambah method import & downloadTemplate)
3. Update 10 index views (tambah import section)
4. Buat CSV templates
5. Testing import untuk setiap master data

### Phase 5: Kalender Akademik (MEDIUM PRIORITY)
**Estimasi: 3-4 jam**

1. Buat migration kalender_akademik
2. Buat model KalenderAkademik
3. Buat 3 controllers (Admin CRUD, Mahasiswa & Dosen view)
4. Buat 5 views dengan FullCalendar.js
5. Update routes & sidebar
6. Testing

---

## ✅ CHECKLIST TESTING

### KRS System:
- [ ] Mahasiswa belum bayar SPP → Tidak bisa buka KRS
- [ ] Mata kuliah wajib auto-populated
- [ ] Daftar mata kuliah tidak lulus muncul
- [ ] Validasi jadwal bentrok berfungsi
- [ ] Submit KRS berhasil
- [ ] Setelah submit tidak bisa edit
- [ ] Admin/Dosen PA bisa approve
- [ ] Cetak KRS tampil jadwal lengkap
- [ ] Total SKS benar

### Jadwal System:
- [ ] Menu jadwal CRUD hilang dari sidebar dosen
- [ ] Dosen hanya view jadwal (read-only)
- [ ] Admin bisa CRUD semua jadwal
- [ ] Validasi bentrok ruangan
- [ ] Validasi bentrok dosen
- [ ] Mahasiswa view jadwal dari KRS

### Auto-Assignment:
- [ ] Admin buat jadwal → Dosen auto-assign ke mata kuliah
- [ ] Admin buat jadwal → Dosen auto-assign ke prodi
- [ ] Tidak duplicate jika sudah di-assign
- [ ] Admin update jadwal → Dosen baru auto-assign
- [ ] User Management ter-update otomatis

### Pagination:
- [ ] Apply filter → Klik page 2 → Filter tetap aktif
- [ ] Test di semua halaman admin
- [ ] Query string preserved di URL

### Import Master Data:
- [ ] Download template CSV untuk setiap master data
- [ ] Import data valid → Berhasil
- [ ] Import data invalid → Error dengan detail
- [ ] Validation berfungsi
- [ ] Data masuk ke database dengan benar

### Kalender Akademik:
- [ ] Admin bisa CRUD event
- [ ] Event tampil di calendar
- [ ] Mahasiswa lihat event relevan
- [ ] Dosen lihat event relevan
- [ ] Validasi tanggal
- [ ] Warna event tampil benar

---

## 📞 NOTES & QUESTIONS

1. **Approval KRS**: Dosen PA atau Admin?
   - Recommendation: Dosen PA

2. **Batas SKS**: Ada batas maksimal SKS per semester?
   - Recommendation: 24 SKS

3. **Calendar UI**: FullCalendar.js atau custom?
   - Recommendation: FullCalendar.js

4. **Import Validation**: Strict atau lenient?
   - Recommendation: Strict (validate semua field)

5. **CSV Encoding**: UTF-8 atau UTF-8 BOM?
   - Recommendation: UTF-8 BOM (Excel compatible)

6. **Import Duplicate**: Skip atau Update?
   - Recommendation: Config per entity (some skip, some update)

---

**DOKUMEN DIBUAT:** 25 November 2025  
**DIBUAT OLEH:** Factory AI Assistant  
**VERSION:** 1.0  
**STATUS:** Ready for Implementation

---

**END OF DOCUMENT**
