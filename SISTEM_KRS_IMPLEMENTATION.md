# 📚 Sistem KRS (Kartu Rencana Studi) - Implementation Plan

## ✅ SUDAH DIBUAT:

### 1. **Database Migration** ✅
File: `database/migrations/2025_11_25_153616_create_krs_table.php`

**Struktur Table `krs`:**
- `id` - Primary key
- `mahasiswa_id` - Foreign key ke mahasiswas
- `semester_id` - Foreign key ke semesters
- `mata_kuliah_id` - Foreign key ke mata_kuliahs
- `is_mengulang` - Boolean (true jika mengulang mata kuliah yang tidak lulus)
- `status` - Enum: draft, submitted, approved, rejected
- `keterangan` - Text (optional)
- `submitted_at` - Timestamp submit
- `approved_at` - Timestamp approve
- `approved_by` - Foreign key ke users (admin yang approve)
- `timestamps`

**Unique Constraint:** Mahasiswa tidak bisa ambil mata kuliah yang sama 2x di semester yang sama

---

### 2. **Model KRS** ✅
File: `app/Models/Krs.php`

**Features:**
- ✅ Relationships: mahasiswa, semester, mataKuliah, approvedBy
- ✅ Scopes: byMahasiswa, bySemester, draft, submitted, approved, mengulang
- ✅ Static method: getTotalSks()

---

### 3. **KRS Controller** ✅
File: `app/Http/Controllers/Mahasiswa/KrsController.php`

**Methods:**
1. `index()` - Display KRS form
   - ✅ Check pembayaran SPP (hanya yang sudah lunas bisa akses)
   - ✅ Get mata kuliah wajib semester ini
   - ✅ Get mata kuliah tidak lulus (untuk mengulang)
   - ✅ Calculate total SKS
   - ✅ Determine max SKS based on IPK

2. `store()` - Add mata kuliah to KRS
   - ✅ Validate pembayaran
   - ✅ Check duplicate
   - ✅ Check max SKS limit
   - ✅ Create KRS record

3. `destroy()` - Remove mata kuliah from KRS
   - ✅ Only draft status can be deleted

4. `submit()` - Submit KRS for approval
   - ✅ Update status to 'submitted'
   - ✅ Set submitted_at timestamp

5. `print()` - Print KRS

**Logic Batas SKS Berdasarkan IPK:**
- IPK >= 3.00 → Max 24 SKS
- IPK >= 2.50 → Max 21 SKS
- IPK >= 2.00 → Max 18 SKS
- IPK < 2.00 → Max 15 SKS

---

## 🔨 YANG PERLU DIBUAT:

### 4. **Routes** ⏳
File: `routes/web.php`

Tambahkan di section mahasiswa routes:

```php
// KRS Management (Mahasiswa)
Route::prefix('mahasiswa')->middleware(['auth', 'role:mahasiswa'])->name('mahasiswa.')->group(function () {
    Route::get('krs', [MahasiswaKrsController::class, 'index'])->name('krs.index');
    Route::post('krs', [MahasiswaKrsController::class, 'store'])->name('krs.store');
    Route::delete('krs/{id}', [MahasiswaKrsController::class, 'destroy'])->name('krs.destroy');
    Route::post('krs/submit', [MahasiswaKrsController::class, 'submit'])->name('krs.submit');
    Route::get('krs/print', [MahasiswaKrsController::class, 'print'])->name('krs.print');
});
```

---

### 5. **Views KRS Mahasiswa** ⏳

#### A) **Form KRS** - `resources/views/mahasiswa/krs/index.blade.php`

**Sections:**
1. **Header**
   - Semester aktif
   - Total SKS (current / max)
   - IPK terakhir

2. **List Mata Kuliah Wajib** (Auto checked, tidak bisa diubah)
   - Table dengan kolom: Kode MK, Nama MK, SKS, Jenis (Wajib)
   - Checkbox disabled (auto checked)

3. **List Mata Kuliah Mengulang** (Optional)
   - Table dengan kolom: Kode MK, Nama MK, SKS, Nilai Lama, Status
   - Checkbox untuk pilih mata kuliah yang mau diulang

4. **List KRS yang Sudah Diambil**
   - Table dengan kolom: Kode MK, Nama MK, SKS, Jenis, Action (Hapus)
   - Total SKS di footer

5. **Submit Button**
   - Button "Submit KRS" (disabled jika sudah submitted)
   - Status KRS (Draft / Submitted / Approved)

---

#### B) **Blocked Screen** - `resources/views/mahasiswa/krs/blocked.blade.php`

**Content:**
- Icon warning
- Pesan: "Anda belum bisa mengakses KRS"
- Alasan: "Pembayaran SPP semester ini belum lunas"
- Link ke halaman pembayaran

---

#### C) **Print KRS** - `resources/views/mahasiswa/krs/print.blade.php`

**Format:**
- Header: Logo, Nama Kampus, Judul "KARTU RENCANA STUDI (KRS)"
- Info Mahasiswa: NIM, Nama, Program Studi
- Info Semester: Semester, Tahun Akademik
- Table Mata Kuliah:
  - No, Kode MK, Nama MK, SKS, Kelas, Dosen
- Footer:
  - Total SKS
  - Tanda tangan mahasiswa & admin
  - Tanggal cetak

---

### 6. **Admin KRS Management** ⏳

**Controller:** `app/Http/Controllers/Admin/KrsController.php`

**Methods:**
1. `index()` - List semua KRS yang submitted (per semester)
2. `approve($id)` - Approve KRS
3. `reject($id)` - Reject KRS
4. `showMahasiswa($mahasiswaId, $semesterId)` - View KRS detail mahasiswa

**Views:**
- `resources/views/admin/krs/index.blade.php` - List KRS submitted
- `resources/views/admin/krs/show.blade.php` - Detail KRS mahasiswa

---

### 7. **Jadwal Management** ⏳

**IMPORTANT:** Jadwal dibuat oleh **SUPER ADMIN**, bukan dosen!

**Controller:** `app/Http/Controllers/Master/JadwalController.php` (sudah ada)

**Verify:**
- Routes jadwal hanya bisa diakses oleh super_admin/admin
- Dosen tidak bisa create/edit jadwal

---

## 🧪 TESTING WORKFLOW:

### **Test 1: Mahasiswa Belum Bayar**
1. Login sebagai mahasiswa
2. Akses `/mahasiswa/krs`
3. Expected: Blocked screen dengan pesan belum bayar SPP

### **Test 2: Mahasiswa Sudah Bayar - Isi KRS**
1. Buat pembayaran SPP lunas untuk mahasiswa
2. Login sebagai mahasiswa
3. Akses `/mahasiswa/krs`
4. Expected:
   - ✅ Muncul form KRS
   - ✅ Mata kuliah wajib semester ini ter-checklist
   - ✅ List mata kuliah mengulang (jika ada nilai tidak lulus)
   - ✅ Total SKS dihitung
   - ✅ Max SKS sesuai IPK

### **Test 3: Tambah Mata Kuliah Mengulang**
1. Checklist mata kuliah dari list "Mengulang"
2. Klik "Tambah ke KRS"
3. Expected:
   - ✅ Mata kuliah masuk list KRS
   - ✅ Total SKS bertambah
   - ✅ Jika melebihi max SKS → error

### **Test 4: Submit KRS**
1. Klik "Submit KRS"
2. Expected:
   - ✅ Status berubah jadi "Submitted"
   - ✅ Tidak bisa edit lagi
   - ✅ Button "Submit" disabled

### **Test 5: Admin Approve KRS**
1. Login sebagai admin
2. Akses `/admin/krs`
3. Expected:
   - ✅ List KRS yang submitted
   - ✅ Button "Approve" dan "Reject"
4. Klik "Approve"
5. Expected:
   - ✅ Status jadi "Approved"
   - ✅ Approved_at dan approved_by terisi

### **Test 6: Print KRS**
1. Login sebagai mahasiswa (yang sudah approved)
2. Akses `/mahasiswa/krs/print`
3. Expected:
   - ✅ KRS dalam format print-friendly
   - ✅ Ada semua mata kuliah yang diambil
   - ✅ Total SKS benar

---

## 📋 NEXT STEPS:

1. ✅ Migration sudah dibuat
2. ✅ Model sudah dibuat
3. ✅ Controller mahasiswa sudah dibuat
4. ⏳ **BUAT ROUTES** (prioritas tinggi)
5. ⏳ **BUAT VIEWS** (prioritas tinggi)
6. ⏳ **RUN MIGRATION**
7. ⏳ **TESTING**
8. ⏳ Buat admin KRS controller (opsional)
9. ⏳ Verify jadwal management (pastikan admin only)

---

## 🚀 QUICK START:

### **Step 1: Run Migration**
```bash
php artisan migrate
```

### **Step 2: Tambah Routes**
Edit `routes/web.php`, tambahkan routes KRS mahasiswa

### **Step 3: Buat Views**
- `resources/views/mahasiswa/krs/index.blade.php`
- `resources/views/mahasiswa/krs/blocked.blade.php`
- `resources/views/mahasiswa/krs/print.blade.php`

### **Step 4: Test**
1. Buat pembayaran SPP untuk test mahasiswa
2. Login dan akses KRS
3. Isi KRS
4. Submit dan print

---

## ⚠️ CATATAN PENTING:

1. **Pembayaran SPP Wajib:**
   - Mahasiswa harus lunas SPP dulu
   - Cek via model Pembayaran: `status = 'lunas'` dan `jenis_pembayaran = 'spp'`

2. **Mata Kuliah Wajib:**
   - Auto dimasukkan (tidak bisa dihapus)
   - Berdasarkan kurikulum program studi
   - Filter: `semester` dan `jenis = 'wajib'`

3. **Mata Kuliah Mengulang:**
   - Dari tabel Nilai dengan `status = 'tidak_lulus'`
   - Mahasiswa pilih manual (checkbox)

4. **Batas SKS:**
   - Dinamis berdasarkan IPK terakhir
   - Prevent over-taking

5. **Status KRS:**
   - Draft → bisa edit
   - Submitted → tidak bisa edit, waiting approval
   - Approved → final, bisa print
   - Rejected → bisa edit lagi

---

**File dokumentasi ini berisi plan lengkap untuk implementasi sistem KRS.**

Lanjutkan dengan:
1. Tambah routes
2. Buat views
3. Run migration
4. Testing

🎯 **Target:** Sistem KRS bisa digunakan mahasiswa untuk isi KRS semester aktif!
