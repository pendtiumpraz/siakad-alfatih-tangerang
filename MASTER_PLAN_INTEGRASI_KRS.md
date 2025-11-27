# 🎯 MASTER PLAN: INTEGRASI KRS, JADWAL, NILAI, DAN PEMBAYARAN

**Dibuat:** 27 November 2025  
**Status:** Planning Phase  
**Target:** Production-Ready Integrated System

---

## 📊 CURRENT SITUATION ANALYSIS

### ✅ Yang Sudah Ada:
1. ✅ **KRS Basic** - Mahasiswa bisa isi KRS (BARU DIBUAT)
2. ✅ **Jadwal System** - Admin bisa buat jadwal
3. ✅ **Nilai System** - Dosen bisa input nilai
4. ✅ **Pembayaran System** - Operator input pembayaran manual
5. ✅ **Semester Model** - Ada field `is_active` untuk control
6. ✅ **Mahasiswa Model** - Ada field `angkatan`, `semester_aktif`
7. ✅ **Import CSV** - Mahasiswa, Dosen, Master Data (6 tables)

### ❌ Yang Belum Ada:
1. ❌ **KRS Approval** - Belum ada Admin/Operator approval workflow
2. ❌ **Auto Calculate Semester** - KRS belum auto-detect semester mahasiswa
3. ❌ **Integrasi Nilai → KRS** - Mengulang berdasarkan nilai tidak lulus
4. ❌ **Mass Generate Pembayaran** - Import CSV untuk generate pembayaran bulk
5. ❌ **Notifikasi Pembayaran** - Mahasiswa belum dapat notif otomatis
6. ❌ **Jadwal Mahasiswa** - View jadwal based on KRS yang diambil
7. ❌ **Kalender Akademik** - Master data untuk periode-periode penting
8. ❌ **Historical Data Strategy** - Cara upload data 2 tahun yang lalu

---

## 🎓 BUSINESS REQUIREMENTS CLARIFICATION

### A. Sistem Semester & KRS
**Requirement:**
> "Ketika semester disetting yang aktif misal 2023/2024 ganjil, maka yg semester 1 ambil matakuliah semester 1 seluruhnya, yg semester 3 ambil matakuliah seluruhnya..."

**Artinya:**
1. **Semester Aktif = Central Control**
   - Hanya ada 1 semester yang aktif dalam 1 waktu
   - Set di `semesters.is_active = true`
   - Ini menentukan periode akademik yang berjalan

2. **Auto-Calculate Semester Mahasiswa**
   ```
   Contoh:
   - Mahasiswa masuk 2024 (angkatan = 2024)
   - Semester aktif sekarang: 2024/2025 Ganjil
   - Maka mahasiswa semester berapa?
   
   Calculation:
   Tahun sekarang = 2024
   Tahun masuk = 2024
   Selisih tahun = 0
   Jenis semester aktif = Ganjil
   
   Semester mahasiswa = (0 * 2) + 1 = Semester 1
   
   Jika semester aktif = 2024/2025 Genap:
   Semester mahasiswa = (0 * 2) + 2 = Semester 2
   ```

3. **Auto-Populate KRS Berdasarkan Semester**
   - Semester 1 → Auto add semua MK semester 1 yang wajib
   - Semester 3 → Auto add semua MK semester 3 yang wajib
   - Semester 5 → Auto add semua MK semester 5 yang wajib
   - dst...

4. **Mengulang MK**
   - Hanya bisa mengulang MK yang **TIDAK LULUS** (status = 'tidak_lulus')
   - Tidak ada batasan semester (bisa mengulang kapan saja)
   - **TETAPI** harus cek jadwal bentrok!
   - Contoh: MK semester 1 tidak lulus → bisa diulang di semester 3 (jika ada jadwal & tidak bentrok)

### B. Approval Workflow
**Requirement:**
> "Cukup mahasiswa apply ke admin & operator"

**Workflow:**
```
Mahasiswa → Isi & Submit KRS
    ↓
System Check: Sudah bayar SPP?
    ↓
❌ Belum Bayar → Status: Pending (tidak bisa approve)
✅ Sudah Bayar → Bisa di-approve
    ↓
Admin/Operator → Review & Approve/Reject
    ↓
Status Approved → KRS Final (tidak bisa edit) + Jadwal MUNCUL
```

**SPP Payment Validation:**
```php
IF mahasiswa belum bayar SPP:
  ❌ TIDAK BISA DI-APPROVE
  Status tetap: Submitted (Pending)
  Jadwal: TIDAK MUNCUL
  Message: "Mahasiswa belum bayar SPP"
  
IF mahasiswa sudah bayar SPP:
  ✅ BISA DI-APPROVE
  Status: Submitted → Approved
  Jadwal: MUNCUL di menu mahasiswa
```

**Mass Approval Logic:**
- Hanya approve mahasiswa yang SUDAH BAYAR SPP
- Skip otomatis yang belum bayar
- Show notification: "150 KRS approved, 10 di-skip (belum bayar SPP)"

**Manual Override (Kasus Khusus):**
- Admin bisa force approve individual (misal: mahasiswa telat, beasiswa)
- Checkbox: "Force Approve (tanpa validasi SPP)"
- Keterangan otomatis: "[FORCE APPROVE] Approved meskipun belum bayar SPP"

**Need to Build:**
1. ✅ Admin/Operator view untuk lihat semua KRS pending (DONE)
2. ✅ Button Approve/Reject dengan keterangan (DONE)
3. ✅ SPP validation saat approve (DONE)
4. ✅ Force approve checkbox untuk override (DONE)
5. [ ] Email/notifikasi ke mahasiswa ketika approved/rejected (TODO)
6. [ ] Views untuk dashboard & detail (TODO)

### C. Integrasi Nilai dengan KRS
**Requirement:**
> "Mengulang itu hanya boleh untuk matakuliah yang tidak lulus / tidak sesuai KKM di penilaian matakuliahnya"

**Logic:**
```php
// Cek MK tidak lulus dari Nilai
$failedCourses = Nilai::where('mahasiswa_id', $mahasiswaId)
    ->where('status', 'tidak_lulus') // Status sudah ada di model
    ->get();

// Tampilkan di KRS sebagai pilihan mengulang
// Cek jadwal available di semester aktif
// Validate tidak bentrok dengan MK wajib semester ini
```

**Grade to Status Mapping:**
| Grade | Nilai | Status | Keterangan |
|-------|-------|--------|------------|
| A | 85-100 | lulus | Sangat Baik |
| A- | 80-84 | lulus | Baik Sekali |
| B+ | 75-79 | lulus | Baik |
| B | 70-74 | lulus | Cukup Baik |
| B- | 65-69 | lulus | Cukup |
| C+ | 60-64 | lulus | Cukup (Batas Lulus) |
| C | 55-59 | tidak_lulus | Tidak Lulus |
| D | 40-54 | tidak_lulus | Kurang |
| E | 0-39 | tidak_lulus | Sangat Kurang |

**KKM:** C+ (60) → Di atas ini LULUS, di bawah ini TIDAK LULUS

### D. Jadwal Mahasiswa
**Requirement:**
> "Integrasi jadwal mahasiswa dengan KRS"

**Fitur:**
1. **Halaman "Jadwal Saya"** di menu mahasiswa
2. **Source:** Dari KRS yang sudah approved
3. **Tampilan:** Calendar view (weekly) atau table
4. **Info:** Mata kuliah, Dosen, Ruangan, Hari, Jam
5. **Export:** Bisa export/print jadwal mingguan

### E. Mass Generate Pembayaran SPP
**Requirement:**
> "Import CSV yang isinya seluruh data mahasiswa semester terbaru, dengan status belum bayar"

**Problem Saat Ini:**
- Pembayaran di-input manual 1 per 1 oleh operator
- Mahasiswa banyak → tidak efisien
- Ketika semester baru, harus generate pembayaran SPP untuk SEMUA mahasiswa aktif

**Solution:**
1. **Auto-Generate Pembayaran**
   - Admin klik button "Generate Pembayaran SPP Semester [Nama Semester]"
   - System auto create pembayaran untuk ALL mahasiswa aktif
   - Status: `belum_lunas`
   - Nominal: Dari program studi atau default (e.g., 2,500,000)
   - Due date: Dari kalender akademik atau manual input

2. **Import CSV Pembayaran** (Alternative)
   - Format CSV: `nim, nominal, due_date, keterangan`
   - Bisa override nominal per mahasiswa (untuk beasiswa, keringanan, etc.)
   - Auto create pembayaran dengan status `belum_lunas`

3. **Notifikasi Mahasiswa**
   - Setelah pembayaran di-generate → kirim notifikasi
   - Tampilkan di dashboard mahasiswa (banner merah: "SPP Belum Dibayar")
   - Tampilkan di menu Pembayaran dengan highlight
   - Optional: Email/WhatsApp notification

### F. Kalender Akademik
**Requirement:**
> "Master data kalender akademik"

**Fitur:**
1. **Periode KRS**
   - Tanggal mulai - selesai KRS
   - Jika di luar periode → mahasiswa tidak bisa isi KRS
   
2. **Periode Pembayaran**
   - Deadline pembayaran SPP
   - Jika lewat deadline → tambahan denda?

3. **Periode Perkuliahan**
   - Tanggal mulai - selesai perkuliahan
   
4. **Periode UTS/UAS**
   - Jadwal ujian tengah semester & akhir semester
   
5. **Periode Input Nilai**
   - Deadline dosen input nilai
   - Jika lewat deadline → warning ke dosen

6. **Libur/Event**
   - Libur nasional, libur semester, wisuda, etc.

**Database Structure:**
```sql
kalender_akademik:
- id
- semester_id (FK)
- jenis_event (enum: krs, pembayaran, perkuliahan, uts, uas, input_nilai, libur, event)
- nama_event
- tanggal_mulai
- tanggal_selesai
- deskripsi
- is_active
```

### G. Historical Data Strategy
**Context:**
> "Sistem ini dibuat setelah sistem perkuliahan berjalan 2 tahun, sekarang ini semester 5"

**Problem:**
- Data semester 1, 2, 3, 4 belum masuk ke sistem
- Data KRS, Nilai, KHS belum ada untuk semester-semester lama
- Mahasiswa semester 5 sekarang, tapi history nya kosong

**Strategy:**

#### 1. **Buat Semester Historical**
```sql
INSERT INTO semesters:
- 2023/2024 Ganjil (Semester 1) - is_active = false
- 2023/2024 Genap (Semester 2) - is_active = false
- 2024/2025 Ganjil (Semester 3) - is_active = false
- 2024/2025 Genap (Semester 4) - is_active = false
- 2025/2026 Ganjil (Semester 5) - is_active = TRUE ← Current
```

#### 2. **Import Jadwal Historical**
- Import jadwal untuk semester 1, 2, 3, 4
- CSV format: `semester, kode_mk, nidn_dosen, kode_ruangan, hari, jam_mulai, jam_selesai, kelas`
- Ini penting karena KRS dan Nilai butuh jadwal

#### 3. **Import Nilai Historical**
- Import nilai mahasiswa untuk semester 1, 2, 3, 4
- CSV format: `nim, semester, kode_mk, nilai_tugas, nilai_uts, nilai_uas, nilai_akhir, grade, status`
- Ini akan auto-populate KHS juga

#### 4. **Generate KRS Historical (Optional)**
- Jika mau lengkap, bisa generate KRS untuk semester lama
- Tapi ini optional, karena yang penting adalah Nilai (untuk cek lulus/tidak)

#### 5. **Validation for Historical Data**
```php
// Mahasiswa masuk 2024, semester aktif 2023/2024 Ganjil
if ($mahasiswaAngkatan > $semesterTahunAkademik) {
    // Mahasiswa belum masuk, tidak bisa isi KRS
    return "Anda belum terdaftar pada semester ini";
}
```

---

## 🛠️ TECHNICAL IMPLEMENTATION PLAN

### **PHASE 1: FIX KRS SYSTEM** (Prioritas Tinggi)

#### 1.1. Improve Semester Calculation
**File:** `app/Http/Controllers/Mahasiswa/KrsController.php`

**Current Issue:**
```php
// Current logic: Pakai substring NIM
$tahunMasuk = (int) substr($mahasiswa->nim, 0, 4);
```

**New Logic:**
```php
// Use angkatan field (more reliable)
private function calculateMahasiswaSemester(Mahasiswa $mahasiswa, Semester $currentSemester)
{
    // Parse tahun akademik (format: "2024/2025")
    $tahunAkademik = (int) substr($currentSemester->tahun_akademik, 0, 4);
    
    // Angkatan mahasiswa
    $angkatan = $mahasiswa->angkatan;
    
    // Validasi: mahasiswa belum masuk
    if ($angkatan > $tahunAkademik) {
        return 0; // Mahasiswa belum terdaftar
    }
    
    // Calculate tahun studi (0 = tahun pertama)
    $tahunStudi = $tahunAkademik - $angkatan;
    
    // Calculate semester number
    if ($currentSemester->jenis === 'ganjil') {
        return ($tahunStudi * 2) + 1; // Semester 1, 3, 5, 7, ...
    } else {
        return ($tahunStudi * 2) + 2; // Semester 2, 4, 6, 8, ...
    }
}
```

**Example:**
```
Mahasiswa angkatan 2024
Semester aktif: 2024/2025 Ganjil

Tahun akademik = 2024
Angkatan = 2024
Tahun studi = 2024 - 2024 = 0
Jenis = ganjil
Semester = (0 * 2) + 1 = 1 ✅

---

Semester aktif: 2025/2026 Ganjil
Tahun akademik = 2025
Angkatan = 2024
Tahun studi = 2025 - 2024 = 1
Jenis = ganjil
Semester = (1 * 2) + 1 = 3 ✅
```

#### 1.2. Auto-Populate Based on Semester
**File:** `app/Http/Controllers/Mahasiswa/KrsController.php`

**Update Method:**
```php
private function autoPopulateMataKuliahWajib(Mahasiswa $mahasiswa, Semester $semester)
{
    try {
        $kurikulum = $mahasiswa->programStudi->kurikulums()
            ->where('is_active', true)
            ->first();

        if (!$kurikulum) {
            Log::warning("No active kurikulum for mahasiswa {$mahasiswa->id}");
            return;
        }

        // Calculate mahasiswa current semester
        $currentSemesterNumber = $this->calculateMahasiswaSemester($mahasiswa, $semester);
        
        // Validasi: mahasiswa belum terdaftar
        if ($currentSemesterNumber == 0) {
            Log::info("Mahasiswa {$mahasiswa->id} belum terdaftar di semester ini (angkatan lebih baru)");
            return;
        }

        // Get mata kuliah wajib for this semester number
        $mataKuliahsWajib = MataKuliah::where('kurikulum_id', $kurikulum->id)
            ->where('semester', $currentSemesterNumber) // ← KEY CHANGE
            ->where('jenis', 'wajib')
            ->get();

        foreach ($mataKuliahsWajib as $mataKuliah) {
            // Check if jadwal exists
            $jadwalExists = Jadwal::where('mata_kuliah_id', $mataKuliah->id)
                ->where('semester_id', $semester->id)
                ->exists();

            if (!$jadwalExists) {
                Log::info("No jadwal for MK {$mataKuliah->id}, skipping");
                continue;
            }

            // Create KRS entry
            Krs::create([
                'mahasiswa_id' => $mahasiswa->id,
                'semester_id' => $semester->id,
                'mata_kuliah_id' => $mataKuliah->id,
                'is_mengulang' => false,
                'status' => 'draft',
            ]);
        }

        Log::info("Auto-populated {$mataKuliahsWajib->count()} MK for mahasiswa {$mahasiswa->id} semester {$currentSemesterNumber}");
    } catch (\Exception $e) {
        Log::error("Error auto-populating: " . $e->getMessage());
    }
}
```

#### 1.3. Integrasi Nilai untuk Mengulang
**File:** `app/Http/Controllers/Mahasiswa/KrsController.php`

**Update index() method:**
```php
public function index(Request $request)
{
    // ... existing code ...
    
    // Get mata kuliah that failed (tidak lulus)
    $failedMataKuliahs = Nilai::where('mahasiswa_id', $mahasiswa->id)
        ->where('status', 'tidak_lulus') // ← From Nilai table
        ->with(['mataKuliah.kurikulum', 'mataKuliah.jadwals' => function($q) use ($activeSemester) {
            $q->where('semester_id', $activeSemester->id);
        }])
        ->get()
        ->pluck('mataKuliah')
        ->unique('id')
        ->filter(function($mk) use ($krsItems) {
            // Only show if not already in KRS
            return !$krsItems->pluck('mata_kuliah_id')->contains($mk->id);
        })
        ->filter(function($mk) use ($activeSemester) {
            // Only show if jadwal exists in current semester
            return $mk->jadwals->where('semester_id', $activeSemester->id)->count() > 0;
        })
        ->values();
    
    // ... rest of code ...
}
```

---

### **PHASE 2: ADMIN/OPERATOR APPROVAL** (Prioritas Tinggi)

#### 2.1. Create Admin KRS Controller
**File:** `app/Http/Controllers/Admin/KrsApprovalController.php`

```php
<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Mahasiswa;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KrsApprovalController extends Controller
{
    /**
     * Display all pending KRS for approval
     */
    public function index(Request $request)
    {
        $query = Mahasiswa::query()
            ->with([
                'programStudi',
                'krs' => function($q) {
                    $q->with(['mataKuliah', 'semester'])
                      ->where('status', 'submitted');
                }
            ])
            ->whereHas('krs', function($q) {
                $q->where('status', 'submitted');
            });
        
        // Filter by program studi
        if ($request->filled('program_studi_id')) {
            $query->where('program_studi_id', $request->program_studi_id);
        }
        
        // Filter by semester
        if ($request->filled('semester_id')) {
            $query->whereHas('krs', function($q) use ($request) {
                $q->where('semester_id', $request->semester_id);
            });
        }
        
        // Search by nim or nama
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('nim', 'like', "%{$search}%")
                  ->orWhere('nama_lengkap', 'like', "%{$search}%");
            });
        }
        
        $mahasiswas = $query->paginate(20)->withQueryString();
        
        $semesters = Semester::orderBy('tahun_akademik', 'desc')->get();
        $programStudis = \App\Models\ProgramStudi::all();
        
        return view('admin.krs.index', compact('mahasiswas', 'semesters', 'programStudis'));
    }
    
    /**
     * Show detail KRS for a mahasiswa
     */
    public function show($mahasiswaId, Request $request)
    {
        $mahasiswa = Mahasiswa::with(['programStudi', 'user'])->findOrFail($mahasiswaId);
        
        $semesterId = $request->semester_id ?? Semester::where('is_active', true)->first()->id;
        
        $krsItems = Krs::where('mahasiswa_id', $mahasiswaId)
            ->where('semester_id', $semesterId)
            ->with(['mataKuliah', 'semester', 'approvedBy'])
            ->get();
        
        $totalSks = $krsItems->sum(function($krs) {
            return $krs->mataKuliah->sks ?? 0;
        });
        
        $semester = Semester::find($semesterId);
        
        return view('admin.krs.show', compact('mahasiswa', 'krsItems', 'totalSks', 'semester'));
    }
    
    /**
     * Approve KRS
     */
    public function approve($mahasiswaId, Request $request)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'keterangan' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $krsItems = Krs::where('mahasiswa_id', $mahasiswaId)
                ->where('semester_id', $validated['semester_id'])
                ->where('status', 'submitted')
                ->get();
            
            if ($krsItems->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada KRS yang perlu di-approve.');
            }
            
            foreach ($krsItems as $krs) {
                $krs->update([
                    'status' => 'approved',
                    'approved_at' => now(),
                    'approved_by' => auth()->id(),
                    'keterangan' => $validated['keterangan'],
                ]);
            }
            
            DB::commit();
            
            // TODO: Send notification to mahasiswa
            
            return redirect()->route('admin.krs.index')
                ->with('success', 'KRS berhasil di-approve.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal approve KRS: ' . $e->getMessage());
        }
    }
    
    /**
     * Reject KRS
     */
    public function reject($mahasiswaId, Request $request)
    {
        $validated = $request->validate([
            'semester_id' => 'required|exists:semesters,id',
            'keterangan' => 'required|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $krsItems = Krs::where('mahasiswa_id', $mahasiswaId)
                ->where('semester_id', $validated['semester_id'])
                ->where('status', 'submitted')
                ->get();
            
            if ($krsItems->isEmpty()) {
                return redirect()->back()->with('error', 'Tidak ada KRS yang bisa di-reject.');
            }
            
            foreach ($krsItems as $krs) {
                $krs->update([
                    'status' => 'rejected',
                    'approved_at' => null,
                    'approved_by' => null,
                    'keterangan' => $validated['keterangan'],
                ]);
            }
            
            DB::commit();
            
            // TODO: Send notification to mahasiswa
            
            return redirect()->route('admin.krs.index')
                ->with('success', 'KRS berhasil di-reject. Mahasiswa bisa edit dan submit ulang.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal reject KRS: ' . $e->getMessage());
        }
    }
}
```

#### 2.2. Add Routes
**File:** `routes/web.php`

```php
// Admin KRS Approval
Route::prefix('admin')->name('admin.')->middleware(['auth', 'role:superadmin,admin'])->group(function () {
    Route::get('krs-approval', [KrsApprovalController::class, 'index'])->name('krs.index');
    Route::get('krs-approval/{mahasiswa}', [KrsApprovalController::class, 'show'])->name('krs.show');
    Route::post('krs-approval/{mahasiswa}/approve', [KrsApprovalController::class, 'approve'])->name('krs.approve');
    Route::post('krs-approval/{mahasiswa}/reject', [KrsApprovalController::class, 'reject'])->name('krs.reject');
});

// Operator juga bisa approve
Route::prefix('operator')->name('operator.')->middleware(['auth', 'role:operator'])->group(function () {
    Route::get('krs-approval', [KrsApprovalController::class, 'index'])->name('krs.index');
    Route::get('krs-approval/{mahasiswa}', [KrsApprovalController::class, 'show'])->name('krs.show');
    Route::post('krs-approval/{mahasiswa}/approve', [KrsApprovalController::class, 'approve'])->name('krs.approve');
    Route::post('krs-approval/{mahasiswa}/reject', [KrsApprovalController::class, 'reject'])->name('krs.reject');
});
```

---

### **PHASE 3: MASS GENERATE PEMBAYARAN** (Prioritas Tinggi)

#### 3.1. Auto-Generate Pembayaran SPP
**File:** `app/Http/Controllers/Admin/PembayaranController.php`

Add method:
```php
/**
 * Generate pembayaran SPP for all active mahasiswa
 */
public function generateSPP(Request $request)
{
    $validated = $request->validate([
        'semester_id' => 'required|exists:semesters,id',
        'nominal' => 'required|numeric|min:0',
        'tanggal_jatuh_tempo' => 'required|date',
        'keterangan' => 'nullable|string',
    ]);
    
    try {
        DB::beginTransaction();
        
        $semester = Semester::findOrFail($validated['semester_id']);
        
        // Get all active mahasiswa
        $mahasiswas = Mahasiswa::where('status', 'aktif')->get();
        
        $generated = 0;
        $skipped = 0;
        
        foreach ($mahasiswas as $mahasiswa) {
            // Check if already exists
            $exists = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester_id', $semester->id)
                ->where('jenis_pembayaran', 'spp')
                ->exists();
            
            if ($exists) {
                $skipped++;
                continue;
            }
            
            // Create pembayaran
            Pembayaran::create([
                'mahasiswa_id' => $mahasiswa->id,
                'semester_id' => $semester->id,
                'jenis_pembayaran' => 'spp',
                'nominal' => $validated['nominal'],
                'status' => 'belum_lunas',
                'tanggal_jatuh_tempo' => $validated['tanggal_jatuh_tempo'],
                'keterangan' => $validated['keterangan'] ?? "SPP Semester {$semester->nama_semester}",
            ]);
            
            $generated++;
        }
        
        DB::commit();
        
        // TODO: Send notification to all mahasiswa
        
        return redirect()->back()->with('success', "Berhasil generate {$generated} pembayaran SPP. {$skipped} sudah ada.");
        
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Gagal generate pembayaran: ' . $e->getMessage());
    }
}
```

#### 3.2. Import CSV Pembayaran
**File:** `app/Imports/PembayaranImport.php`

```php
<?php

namespace App\Imports;

use App\Models\Pembayaran;
use App\Models\Mahasiswa;
use App\Models\Semester;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class PembayaranImport implements ToModel, WithHeadingRow, WithValidation
{
    protected $semesterId;
    
    public function __construct($semesterId)
    {
        $this->semesterId = $semesterId;
    }
    
    public function model(array $row)
    {
        $mahasiswa = Mahasiswa::where('nim', $row['nim'])->first();
        
        if (!$mahasiswa) {
            throw new \Exception("Mahasiswa dengan NIM {$row['nim']} tidak ditemukan");
        }
        
        // Check if already exists
        $exists = Pembayaran::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $this->semesterId)
            ->where('jenis_pembayaran', 'spp')
            ->first();
        
        if ($exists) {
            // Update nominal if different
            if ($exists->nominal != $row['nominal']) {
                $exists->update(['nominal' => $row['nominal']]);
            }
            return null; // Skip creation
        }
        
        return new Pembayaran([
            'mahasiswa_id' => $mahasiswa->id,
            'semester_id' => $this->semesterId,
            'jenis_pembayaran' => 'spp',
            'nominal' => $row['nominal'],
            'status' => 'belum_lunas',
            'tanggal_jatuh_tempo' => $row['tanggal_jatuh_tempo'],
            'keterangan' => $row['keterangan'] ?? "SPP Semester",
        ]);
    }
    
    public function rules(): array
    {
        return [
            'nim' => 'required|exists:mahasiswas,nim',
            'nominal' => 'required|numeric|min:0',
            'tanggal_jatuh_tempo' => 'required|date',
            'keterangan' => 'nullable|string',
        ];
    }
}
```

#### 3.3. Template CSV Format
```csv
nim,nominal,tanggal_jatuh_tempo,keterangan
2024001,2500000,2025-09-30,SPP Semester Ganjil 2024/2025
2024002,2500000,2025-09-30,SPP Semester Ganjil 2024/2025
2024003,2000000,2025-09-30,SPP Semester Ganjil 2024/2025 (Beasiswa 20%)
```

---

### **PHASE 4: JADWAL MAHASISWA** (Prioritas Sedang)

#### 4.1. Create Jadwal Mahasiswa Controller
**File:** `app/Http/Controllers/Mahasiswa/JadwalMahasiswaController.php`

```php
<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\Krs;
use App\Models\Jadwal;
use App\Models\Semester;

class JadwalMahasiswaController extends Controller
{
    public function index()
    {
        $mahasiswa = auth()->user()->mahasiswa;
        
        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard')
                ->with('error', 'Data mahasiswa tidak ditemukan.');
        }
        
        $activeSemester = Semester::where('is_active', true)->first();
        
        if (!$activeSemester) {
            return view('mahasiswa.jadwal.empty')
                ->with('message', 'Tidak ada semester aktif saat ini.');
        }
        
        // Get approved KRS
        $approvedKrs = Krs::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester_id', $activeSemester->id)
            ->where('status', 'approved')
            ->pluck('mata_kuliah_id');
        
        if ($approvedKrs->isEmpty()) {
            return view('mahasiswa.jadwal.empty')
                ->with('message', 'KRS Anda belum di-approve. Jadwal akan tampil setelah KRS di-approve.');
        }
        
        // Get jadwal based on approved KRS
        $jadwals = Jadwal::whereIn('mata_kuliah_id', $approvedKrs)
            ->where('semester_id', $activeSemester->id)
            ->with(['mataKuliah', 'dosen', 'ruangan'])
            ->orderByRaw("FIELD(hari, 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu')")
            ->orderBy('jam_mulai')
            ->get();
        
        return view('mahasiswa.jadwal.index', compact('jadwals', 'mahasiswa', 'activeSemester'));
    }
}
```

#### 4.2. View: Calendar/Table
**File:** `resources/views/mahasiswa/jadwal/index.blade.php`

Options:
1. **Table View** (Simple) - Group by hari
2. **Calendar View** (Advanced) - Weekly grid like dosen

---

### **PHASE 5: KALENDER AKADEMIK** (Prioritas Sedang)

#### 5.1. Migration
**File:** `database/migrations/create_kalender_akademik_table.php`

```php
Schema::create('kalender_akademik', function (Blueprint $table) {
    $table->id();
    $table->foreignId('semester_id')->nullable()->constrained()->onDelete('cascade');
    $table->string('nama_event', 200);
    $table->enum('jenis_event', [
        'periode_krs',
        'periode_pembayaran',
        'periode_perkuliahan',
        'periode_uts',
        'periode_uas',
        'periode_input_nilai',
        'libur',
        'event_penting',
    ]);
    $table->text('deskripsi')->nullable();
    $table->date('tanggal_mulai');
    $table->date('tanggal_selesai');
    $table->enum('target_user', ['all', 'mahasiswa', 'dosen', 'admin'])->default('all');
    $table->string('warna', 7)->default('#3B82F6');
    $table->boolean('is_active')->default(true);
    $table->timestamps();
    
    $table->index(['semester_id', 'jenis_event']);
    $table->index('tanggal_mulai');
});
```

#### 5.2. Validation Logic in KRS
```php
// Check if within KRS period
$krsPeriod = KalenderAkademik::where('jenis_event', 'periode_krs')
    ->where('semester_id', $activeSemester->id)
    ->where('is_active', true)
    ->first();

if ($krsPeriod) {
    $now = now();
    if ($now < $krsPeriod->tanggal_mulai) {
        return "KRS belum dibuka. Periode KRS dimulai {$krsPeriod->tanggal_mulai->format('d M Y')}";
    }
    if ($now > $krsPeriod->tanggal_selesai) {
        return "Periode KRS sudah ditutup. Deadline: {$krsPeriod->tanggal_selesai->format('d M Y')}";
    }
}
```

---

### **PHASE 6: NOTIFICATION SYSTEM** (Prioritas Rendah)

#### 6.1. Notification for Pembayaran
```php
// After generate pembayaran
foreach ($mahasiswas as $mahasiswa) {
    Notification::create([
        'user_id' => $mahasiswa->user_id,
        'type' => 'pembayaran',
        'title' => 'Pembayaran SPP Semester ' . $semester->nama_semester,
        'message' => 'Anda memiliki tagihan SPP sebesar Rp ' . number_format($nominal, 0, ',', '.'),
        'url' => route('mahasiswa.pembayaran.index'),
        'is_read' => false,
    ]);
}
```

#### 6.2. Notification for KRS Approval
```php
// After approve/reject KRS
Notification::create([
    'user_id' => $mahasiswa->user_id,
    'type' => 'krs',
    'title' => 'KRS Anda telah ' . ($status == 'approved' ? 'disetujui' : 'ditolak'),
    'message' => $keterangan,
    'url' => route('mahasiswa.krs.index'),
    'is_read' => false,
]);
```

---

## 📅 IMPLEMENTATION TIMELINE

### Week 1: Core Fixes
- [ ] Fix KRS semester calculation (angkatan-based)
- [ ] Fix auto-populate based on semester number
- [ ] Integrate Nilai for mengulang validation
- [ ] Testing semester calculation logic

### Week 2: Admin Approval
- [ ] Create Admin/Operator KRS approval controller
- [ ] Create approval views (index, show)
- [ ] Add approve/reject functionality
- [ ] Testing approval workflow

### Week 3: Pembayaran System
- [ ] Mass generate pembayaran SPP
- [ ] Import CSV pembayaran
- [ ] Notifikasi pembayaran
- [ ] Testing pembayaran generation

### Week 4: Jadwal & Kalender
- [ ] Create jadwal mahasiswa view
- [ ] Create kalender akademik system
- [ ] Integrate kalender with KRS period
- [ ] Testing full integration

### Week 5: Historical Data
- [ ] Create semester historical (1-4)
- [ ] Import jadwal historical
- [ ] Import nilai historical
- [ ] Testing with real data

---

## 🧪 TESTING CHECKLIST

### KRS System:
- [ ] Mahasiswa semester 1 auto-populate MK semester 1
- [ ] Mahasiswa semester 3 auto-populate MK semester 3
- [ ] Mahasiswa angkatan 2026 di semester 2024/2025 → tidak bisa isi KRS
- [ ] Mengulang hanya tampil untuk MK tidak lulus (dari Nilai)
- [ ] Admin bisa approve/reject KRS
- [ ] Mahasiswa dapat notifikasi approve/reject

### Pembayaran:
- [ ] Generate pembayaran SPP untuk 100+ mahasiswa
- [ ] Import CSV pembayaran dengan nominal berbeda
- [ ] Mahasiswa dapat notifikasi pembayaran
- [ ] Mahasiswa tidak bisa isi KRS jika belum bayar

### Jadwal Mahasiswa:
- [ ] Jadwal tampil sesuai KRS approved
- [ ] Jadwal tidak tampil jika KRS belum approved
- [ ] Calendar view responsive

### Kalender Akademik:
- [ ] KRS tidak bisa diisi di luar periode KRS
- [ ] Periode pembayaran terintegrasi

---

## 🚀 DEPLOYMENT STRATEGY

### Production Readiness:
1. **Backup Database** sebelum deploy
2. **Run Migrations** untuk kalender_akademik
3. **Seed Historical Semesters** (semester 1-4)
4. **Generate Pembayaran** untuk semester aktif
5. **Notification Test** untuk 5-10 mahasiswa
6. **Full Testing** dengan real user
7. **Training** untuk Admin/Operator

---

## 📝 NOTES

1. **KKM Setting:** Default KKM = 60 (C+). Bisa di-config per mata kuliah jika perlu.
2. **Beasiswa:** Bisa override nominal pembayaran via CSV import.
3. **Denda:** Bisa tambahkan logic auto-calculate denda jika lewat deadline.
4. **WhatsApp Notification:** Bisa integrate dengan WhatsApp API untuk notifikasi.
5. **Dashboard Widget:** Tampilkan info penting (tagihan, KRS status, jadwal hari ini).

---

**END OF MASTER PLAN**

---

Apakah plan ini sudah sesuai? Ada yang perlu disesuaikan atau ditambahkan?
