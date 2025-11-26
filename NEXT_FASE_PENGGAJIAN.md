# FASE 3: Implementasi Controllers, Routes & Views
## Sistem Penggajian Dosen

---

## ✅ Progress So Far

### Commit: 51e59eb0 - Models DONE
- ✅ Database migration (dosens + penggajian_dosens)
- ✅ PenggajianDosen model complete
- ✅ Dosen model updated (rekening + relationship)
- ✅ Documentation SISTEM_PENGGAJIAN_DOSEN.md

---

## 🚀 Fase 3: Yang Perlu Dikerjakan

Karena sistem penggajian ini kompleks dan membutuhkan banyak file, berikut roadmap lengkapnya:

### **A. Controllers (Priority HIGH)**

#### 1. Dosen\PenggajianController.php
**Path**: `app/Http/Controllers/Dosen/PenggajianController.php`

**Methods Needed:**
```php
public function index()
{
    // List semua pengajuan dosen yang login
    // Order by: terbaru dulu
    // Dengan pagination
}

public function create()
{
    // Form pengajuan baru
    // Load semesters untuk dropdown
    // Check rekening sudah diisi atau belum
}

public function store(Request $request)
{
    // Validasi:
    // - periode required, format YYYY-MM
    // - total_jam_diajukan required, numeric, min:0
    // - link_rps required, URL, contains 'drive.google.com'
    // - link_materi_ajar required, URL, contains 'drive.google.com'
    // - link_absensi required, URL, contains 'drive.google.com'
    // - Check duplicate periode per dosen
    
    // Create pengajuan
    // Status default: pending
    // Redirect ke index dengan success message
}

public function show($id)
{
    // Detail pengajuan
    // Check authorization (hanya dosen pemilik)
    // Load relationships: semester, verifier, payer
}

public function edit($id)
{
    // Form edit
    // Check: hanya bisa edit jika status = pending
    // Load semesters
}

public function update(Request $request, $id)
{
    // Sama seperti store validation
    // Check status = pending
    // Update data
}

public function destroy($id)
{
    // Soft delete
    // Check status = pending only
}
```

#### 2. Admin\PenggajianDosenController.php
**Path**: `app/Http/Controllers/Admin/PenggajianDosenController.php`

**Methods Needed:**
```php
public function index(Request $request)
{
    // List semua pengajuan dari semua dosen
    // Filters: status, periode, dosen_id
    // Search by dosen name
    // Order by: pending first, then terbaru
    // With pagination
}

public function show($id)
{
    // Detail lengkap untuk verifikasi
    // Load relationships: dosen (with user), semester
    // Display all links (RPS, Materi, Absensi)
}

public function verify(Request $request, $id)
{
    // Validasi:
    // - action required, in:approve,reject
    // - total_jam_disetujui required if approve, numeric
    // - catatan_verifikasi optional
    
    // If approve:
    //   - status = verified
    //   - set total_jam_disetujui
    //   - set verified_by = auth()->id()
    //   - set verified_at = now()
    
    // If reject:
    //   - status = rejected
    //   - set catatan_verifikasi
    //   - set verified_by = auth()->id()
    //   - set verified_at = now()
}

public function payment($id)
{
    // Form pembayaran
    // Check status = verified
    // Display dosen info + rekening
}

public function storePayment(Request $request, $id)
{
    // Validasi:
    // - jumlah_dibayar required, numeric, min:0
    // - bukti_pembayaran required, URL, contains 'drive.google.com'
    
    // Update:
    //   - status = paid
    //   - jumlah_dibayar
    //   - bukti_pembayaran
    //   - paid_by = auth()->id()
    //   - paid_at = now()
}
```

---

### **B. Routes (Priority HIGH)**

**Path**: `routes/web.php`

```php
// Dosen Routes
Route::middleware(['auth', 'role:dosen'])->prefix('dosen')->name('dosen.')->group(function() {
    Route::resource('penggajian', \App\Http\Controllers\Dosen\PenggajianController::class);
});

// Admin/Operator Routes
Route::middleware(['auth', 'role:super_admin,operator'])->prefix('admin')->name('admin.')->group(function() {
    Route::prefix('penggajian-dosen')->name('penggajian.')->group(function() {
        Route::get('/', [App\Http\Controllers\Admin\PenggajianDosenController::class, 'index'])->name('index');
        Route::get('/{id}', [App\Http\Controllers\Admin\PenggajianDosenController::class, 'show'])->name('show');
        Route::post('/{id}/verify', [App\Http\Controllers\Admin\PenggajianDosenController::class, 'verify'])->name('verify');
        Route::get('/{id}/payment', [App\Http\Controllers\Admin\PenggajianDosenController::class, 'payment'])->name('payment');
        Route::post('/{id}/payment', [App\Http\Controllers\Admin\PenggajianDosenController::class, 'storePayment'])->name('storePayment');
    });
});
```

---

### **C. Views (Priority HIGH)**

#### 1. Dosen Views
**Path**: `resources/views/dosen/penggajian/`

##### index.blade.php
```
┌─────────────────────────────────────────┐
│ 📊 Riwayat Penggajian                   │
│                                         │
│ [+ Ajukan Pencairan Gaji Baru]         │
│                                         │
│ Table:                                  │
│ | Periode | Jam | Status | Dibayar | Aksi │
│ |---------|-----|--------|---------|------|
│ | Nov'25  | 24  | Paid   | 2.4jt   | 👁   │
│ | Okt'25  | 20  | Verif  | -       | 👁✏️ │
│ | Sep'25  | 18  | Pending| -       | 👁✏️🗑 │
└─────────────────────────────────────────┘
```

##### create.blade.php
```
┌─────────────────────────────────────────┐
│ 📝 Ajukan Pencairan Gaji               │
│                                         │
│ Periode*: [Bulan ▼] [Tahun ▼]         │
│ Semester: [Select Semester ▼]          │
│ Total Jam Mengajar*: [____] jam        │
│                                         │
│ 📎 Link Dokumen (Google Drive)         │
│ RPS*: [https://drive.google.com/...]   │
│ Materi Ajar*: [https://drive.google... │
│ Absensi Mahasiswa*: [https://drive...  │
│                                         │
│ Catatan: [Text area]                   │
│                                         │
│ ⚠️ Pastikan semua link sudah di-share  │
│ dengan "anyone with link can view"     │
│                                         │
│ 💳 Data Rekening Anda:                 │
│ Bank: BRI                              │
│ No. Rek: 1234567890                    │
│ ✏️ Edit Rekening                       │
│                                         │
│ [Batalkan] [Ajukan Pencairan]         │
└─────────────────────────────────────────┘
```

##### show.blade.php
```
┌─────────────────────────────────────────┐
│ 📋 Detail Pengajuan                     │
│                                         │
│ Status: [Badge: Menunggu Verifikasi]   │
│ Periode: November 2025                  │
│ Total Jam: 24 jam                       │
│                                         │
│ 📎 Dokumen:                             │
│ [🔗 Buka RPS] [🔗 Buka Materi]         │
│ [🔗 Buka Absensi]                       │
│                                         │
│ 📝 Timeline:                            │
│ ✅ Diajukan: 01 Nov 2025, 10:00        │
│ ⏳ Menunggu Verifikasi...              │
│ ⬜ Pembayaran                           │
│                                         │
│ [Kembali] [Edit] [Hapus]               │
└─────────────────────────────────────────┘

(Jika sudah paid, tampilkan bukti pembayaran)
```

#### 2. Admin Views
**Path**: `resources/views/admin/penggajian/`

##### index.blade.php
```
┌─────────────────────────────────────────┐
│ 💰 Penggajian Dosen                     │
│                                         │
│ Filters:                                │
│ Status: [All ▼] Periode: [2025-11 ▼]  │
│ Dosen: [Search...]                      │
│                                         │
│ Table:                                  │
│ | Dosen | NIDN | Periode | Jam | Status | Aksi │
│ |-------|------|---------|-----|--------|------|
│ | Dr.A  | 123  | Nov'25  | 24  | Pending| Verif│
│ | Dr.B  | 456  | Nov'25  | 20  | Verif  | Bayar│
│ | Dr.C  | 789  | Okt'25  | 18  | Paid   | Lihat│
└─────────────────────────────────────────┘
```

##### show.blade.php (Verifikasi)
```
┌─────────────────────────────────────────┐
│ ✅ Verifikasi Pengajuan Penggajian      │
│                                         │
│ 👨‍🏫 Dosen: Dr. Ahmad, M.Pd            │
│ NIDN: 0123456789                        │
│ Bank: BRI | Rek: 1234567890            │
│                                         │
│ 📊 Pengajuan:                           │
│ Periode: November 2025                  │
│ Semester: Ganjil 2025/2026             │
│ Total Jam: 24 jam                       │
│                                         │
│ 📎 Dokumen untuk Verifikasi:           │
│ [📄 Buka RPS di Google Drive]          │
│ [📚 Buka Materi Ajar]                  │
│ [✍️ Buka Absensi Mahasiswa]            │
│                                         │
│ 📝 Catatan Dosen:                       │
│ "Mengajar 3 kelas, total 24 jam"       │
│                                         │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━    │
│ FORM VERIFIKASI:                        │
│                                         │
│ ⚪ Setujui ⚪ Tolak                     │
│                                         │
│ Jam yang Disetujui: [24] jam           │
│ Catatan Verifikasi: [Text area]        │
│                                         │
│ [Batalkan] [Simpan Verifikasi]         │
└─────────────────────────────────────────┘
```

##### payment.blade.php
```
┌─────────────────────────────────────────┐
│ 💸 Proses Pembayaran Gaji              │
│                                         │
│ 👨‍🏫 Dosen: Dr. Ahmad, M.Pd            │
│ NIDN: 0123456789                        │
│                                         │
│ 💳 Data Transfer:                       │
│ Bank: BRI                               │
│ No. Rekening: 1234567890                │
│ Atas Nama: Ahmad                        │
│                                         │
│ 📊 Detail Pengajuan:                    │
│ Periode: November 2025                  │
│ Total Jam Disetujui: 24 jam            │
│                                         │
│ ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━    │
│ FORM PEMBAYARAN:                        │
│                                         │
│ Jumlah Dibayar*: [Rp _________]        │
│                                         │
│ Link Bukti Transfer* (Google Drive):   │
│ [https://drive.google.com/...]          │
│                                         │
│ ⚠️ Pastikan link bukti pembayaran      │
│ sudah di-share "anyone with link"      │
│                                         │
│ [Batalkan] [Tandai Sudah Dibayar]      │
└─────────────────────────────────────────┘
```

---

### **D. Sidebar Updates (Priority HIGH)**

#### 1. Dosen Sidebar
**Path**: `resources/views/layouts/dosen.blade.php`

Tambahkan di bagian menu (sekitar line 150-200):
```blade
<a href="{{ route('dosen.penggajian.index') }}" 
   class="flex items-center px-6 py-3 {{ request()->routeIs('dosen.penggajian.*') ? 'bg-green-700 text-white' : 'text-gray-300 hover:bg-green-700 hover:text-white' }} transition duration-200">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
    </svg>
    Penggajian
</a>
```

#### 2. Admin Sidebar
**Path**: `resources/views/layouts/admin.blade.php`

Tambahkan di bagian menu (sekitar line 200-250):
```blade
<a href="{{ route('admin.penggajian.index') }}" 
   class="flex items-center px-4 py-3 {{ request()->routeIs('admin.penggajian.*') ? 'bg-[#D4AF37] text-[#2D5F3F]' : 'text-gray-700 hover:bg-gray-100' }} rounded-lg transition">
    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
    </svg>
    Penggajian Dosen
</a>
```

---

### **E. Validation Rules**

#### Google Drive Link Validation
```php
'link_rps' => [
    'required',
    'url',
    function ($attribute, $value, $fail) {
        if (!str_contains($value, 'drive.google.com')) {
            $fail('Link harus dari Google Drive.');
        }
    },
],
```

#### Duplicate Periode Check
```php
// In store method
$exists = PenggajianDosen::where('dosen_id', auth()->user()->dosen->id)
    ->where('periode', $request->periode)
    ->exists();

if ($exists) {
    return redirect()->back()
        ->withErrors(['periode' => 'Anda sudah mengajukan pencairan untuk periode ini.'])
        ->withInput();
}
```

---

### **F. Authorization Checks**

#### Dosen Authorization
```php
// Check if dosen owns this penggajian
public function show($id)
{
    $penggajian = PenggajianDosen::findOrFail($id);
    
    if ($penggajian->dosen_id !== auth()->user()->dosen->id) {
        abort(403, 'Unauthorized');
    }
    
    return view('dosen.penggajian.show', compact('penggajian'));
}
```

---

## 🎯 Execution Plan

### Step 1: Complete Controllers
```bash
# Implement Dosen\PenggajianController
# Implement Admin\PenggajianDosenController
```

### Step 2: Add Routes
```bash
# Update routes/web.php
# Add dosen.penggajian.* routes
# Add admin.penggajian.* routes
```

### Step 3: Create Views
```bash
# Create resources/views/dosen/penggajian/
# Create index, create, show, edit views
# Create resources/views/admin/penggajian/
# Create index, show, payment views
```

### Step 4: Update Sidebars
```bash
# Update layouts/dosen.blade.php
# Update layouts/admin.blade.php
```

### Step 5: Testing
```bash
# Test dosen flow: ajukan → lihat → edit → delete
# Test admin flow: verifikasi → pembayaran
# Test authorization & validation
```

---

## 📋 Quick Commands

```bash
# Create Admin Controller
php artisan make:controller Admin/PenggajianDosenController

# Create Views Directories
mkdir -p resources/views/dosen/penggajian
mkdir -p resources/views/admin/penggajian

# Test Routes
php artisan route:list --name=penggajian

# Clear Cache
php artisan view:clear
php artisan route:clear
php artisan cache:clear
```

---

## 🔥 Next Session Command

To continue this implementation, say:

**"lanjutkan fase 3 penggajian: buat controllers lengkap"**

or

**"lanjutkan fase 3: buat views untuk dosen penggajian"**

---

**Status**: Models & Database COMPLETE ✅
**Next**: Controllers, Routes, Views, Sidebars

Token remaining: ~60k - cukup untuk 1-2 controller + routes
