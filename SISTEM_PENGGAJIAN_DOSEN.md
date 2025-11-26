# SISTEM PENGGAJIAN DOSEN - Implementation Plan

## ✅ Status: Database Schema DONE

### Commit: 31adb080
- ✅ Migration: Add rekening to dosens table
- ✅ Migration: Create penggajian_dosens table
- ✅ Database deployed

---

## 📋 Requirements Summary

### A. Dosen Side
1. **Menu Sidebar**: "Penggajian" atau "Pencairan Gaji"
2. **Fitur Upload**:
   - Link Google Drive RPS (everyone can view)
   - Link Google Drive Materi Ajar (everyone can view)
   - Link Google Drive Absensi Mahasiswa (everyone can view)
3. **Input**: Total jam mengajar yang mau dicairkan
4. **Data Rekening**: Nama Bank, Nomor Rekening
5. **History**: Lihat semua pengajuan (pending/verified/paid)
6. **Detail Pembayaran**: Lihat bukti transfer yang diupload operator

### B. Operator/Super Admin Side
1. **Menu Sidebar**: "Penggajian Dosen"
2. **Fitur Verifikasi**:
   - Cek RPS (buka link Google Drive)
   - Cek Materi Ajar (buka link Google Drive)
   - Cek Absensi (buka link Google Drive)
   - Approve/Reject pengajuan
   - Edit jumlah jam yang disetujui (jika berbeda dengan pengajuan)
3. **Fitur Pembayaran**:
   - Input jumlah yang dibayar
   - Upload bukti pembayaran (Google Drive link)
   - Mark as paid
4. **History**: Lihat semua pengajuan dari semua dosen

### C. Flow
```
1. Dosen → Ajukan Pencairan (pending)
2. Operator/Admin → Verifikasi (verified)
3. Operator/Admin → Bayar + Upload Bukti (paid)
4. Semua pihak → Lihat History
```

---

## 🗄️ Database Schema

### Table: `dosens`
```sql
+ nama_bank VARCHAR(100) NULL
+ nomor_rekening VARCHAR(50) NULL
```

### Table: `penggajian_dosens`
```sql
- id BIGINT PRIMARY KEY
- dosen_id BIGINT FK → dosens
- periode VARCHAR(20) // Format: 2025-01
- semester_id BIGINT FK → semesters (nullable)

-- Pengajuan dari dosen
- total_jam_diajukan DECIMAL(8,2)
- link_rps TEXT
- link_materi_ajar TEXT
- link_absensi TEXT
- catatan_dosen TEXT

-- Verifikasi
- status ENUM('pending','verified','paid','rejected')
- total_jam_disetujui DECIMAL(8,2)
- catatan_verifikasi TEXT
- verified_by BIGINT FK → users
- verified_at TIMESTAMP

-- Pembayaran
- jumlah_dibayar DECIMAL(15,2)
- bukti_pembayaran TEXT (Google Drive link)
- paid_by BIGINT FK → users
- paid_at TIMESTAMP

- created_at, updated_at TIMESTAMP
```

---

## 🎯 Next Steps (TO DO)

### 1. Create Model
```bash
php artisan make:model PenggajianDosen
```
**File**: `app/Models/PenggajianDosen.php`
- Fillable fields
- Relationships: dosen(), semester(), verifiedBy(), paidBy()
- Accessors for formatted data

### 2. Update Dosen Model
**File**: `app/Models/Dosen.php`
- Add 'nama_bank', 'nomor_rekening' to fillable
- Add relationship: penggajians()

### 3. Create Controllers

#### A. DosenPenggajianController
**File**: `app/Http/Controllers/Dosen/PenggajianController.php`
```php
- index()           // List pengajuan
- create()          // Form pengajuan baru
- store()           // Simpan pengajuan
- show($id)         // Detail pengajuan
- edit($id)         // Edit (hanya jika pending)
- update($id)       // Update
- destroy($id)      // Cancel (hanya jika pending)
```

#### B. AdminPenggajianDosenController
**File**: `app/Http/Controllers/Admin/PenggajianDosenController.php`
```php
- index()           // List semua pengajuan
- show($id)         // Detail untuk verifikasi
- verify($id)       // Approve/Reject
- payment($id)      // Form pembayaran
- storePayment($id) // Proses pembayaran
- history()         // History semua
```

### 4. Create Routes

#### A. Dosen Routes
```php
Route::middleware(['auth', 'role:dosen'])->group(function() {
    Route::prefix('dosen/penggajian')->name('dosen.penggajian.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
});
```

#### B. Admin/Operator Routes
```php
Route::middleware(['auth', 'role:super_admin,operator'])->group(function() {
    Route::prefix('admin/penggajian-dosen')->name('admin.penggajian.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
        Route::post('/{id}/verify', 'verify')->name('verify');
        Route::get('/{id}/payment', 'payment')->name('payment');
        Route::post('/{id}/payment', 'storePayment')->name('storePayment');
        Route::get('/history', 'history')->name('history');
    });
});
```

### 5. Create Views

#### A. Dosen Views
**Path**: `resources/views/dosen/penggajian/`

1. **index.blade.php** - List pengajuan
   - Table: Periode, Jam, Status, Jumlah Dibayar, Aksi
   - Button: Ajukan Pencairan Baru
   - Badge status: pending (yellow), verified (green), paid (blue), rejected (red)

2. **create.blade.php** - Form pengajuan
   - Input: Periode (bulan/tahun)
   - Select: Semester
   - Input: Total Jam Mengajar
   - Input: Link RPS (Google Drive)
   - Input: Link Materi Ajar (Google Drive)
   - Input: Link Absensi (Google Drive)
   - Textarea: Catatan
   - Info: Data Rekening (nama_bank, nomor_rekening)
   - Warning: "Pastikan semua link Google Drive sudah di-share 'anyone with link can view'"

3. **show.blade.php** - Detail pengajuan
   - Info pengajuan
   - Status timeline
   - Links untuk buka Google Drive
   - Catatan verifikasi (jika ada)
   - Bukti pembayaran (jika sudah paid)

4. **edit.blade.php** - Edit pengajuan (only pending)

#### B. Admin/Operator Views
**Path**: `resources/views/admin/penggajian/`

1. **index.blade.php** - List semua pengajuan
   - Filters: Status, Periode, Dosen
   - Table: Dosen, Periode, Jam Diajukan, Status, Aksi
   - Actions: Lihat Detail, Verifikasi, Bayar

2. **show.blade.php** - Detail untuk verifikasi
   - Info dosen (nama, NIDN, rekening)
   - Info pengajuan (periode, jam, links)
   - Buttons: Buka RPS, Buka Materi, Buka Absensi
   - Form verifikasi:
     - Approve/Reject
     - Edit jam yang disetujui
     - Catatan verifikasi

3. **payment.blade.php** - Form pembayaran
   - Info pengajuan yang sudah verified
   - Input: Jumlah yang dibayar
   - Input: Link bukti pembayaran (Google Drive)
   - Button: Tandai Sudah Dibayar

4. **history.blade.php** - History semua pembayaran

### 6. Update Sidebar Menu

#### A. Dosen Sidebar
**File**: `resources/views/layouts/dosen.blade.php`
```html
<a href="{{ route('dosen.penggajian.index') }}" 
   class="sidebar-link">
    <i class="fas fa-money-bill-wave"></i>
    Penggajian
</a>
```

#### B. Admin Sidebar
**File**: `resources/views/layouts/admin.blade.php`
```html
<a href="{{ route('admin.penggajian.index') }}" 
   class="sidebar-link">
    <i class="fas fa-wallet"></i>
    Penggajian Dosen
</a>
```

### 7. Features to Implement

#### A. Validations
- Link harus format Google Drive yang valid
- Periode tidak boleh duplicate per dosen
- Total jam > 0
- Status transitions:
  - pending → verified/rejected
  - verified → paid
  - paid = final (no changes)

#### B. Notifications (Optional)
- Email ke dosen saat status berubah
- Email ke admin saat ada pengajuan baru

#### C. Reports (Future)
- Export laporan pembayaran per periode
- Summary per dosen
- Total pengeluaran per bulan/semester

---

## 🎨 UI/UX Design Notes

### Status Badges
```php
@if($penggajian->status === 'pending')
    <span class="badge bg-yellow-500">Menunggu Verifikasi</span>
@elseif($penggajian->status === 'verified')
    <span class="badge bg-green-500">Disetujui</span>
@elseif($penggajian->status === 'paid')
    <span class="badge bg-blue-500">Sudah Dibayar</span>
@else
    <span class="badge bg-red-500">Ditolak</span>
@endif
```

### Timeline Status
```
1. ✅ Diajukan (2025-11-26 10:00)
2. ⏳ Menunggu Verifikasi
3. ⬜ Pembayaran
```

---

## 🔐 Security & Validation

1. **Google Drive Links**:
   - Validate format: `https://drive.google.com/...`
   - Warning if not public/shareable
   
2. **Authorization**:
   - Dosen hanya bisa lihat/edit pengajuan sendiri
   - Admin/Operator bisa lihat semua
   
3. **Status Protection**:
   - Pending: bisa edit/delete
   - Verified: tidak bisa edit
   - Paid: final, tidak bisa ubah

---

## 📊 Testing Checklist

### Dosen Flow
- [ ] Dosen bisa mengajukan pencairan
- [ ] Dosen bisa lihat history
- [ ] Dosen bisa edit pengajuan yang pending
- [ ] Dosen tidak bisa edit yang sudah verified/paid
- [ ] Dosen bisa lihat bukti pembayaran

### Admin/Operator Flow
- [ ] Admin bisa lihat semua pengajuan
- [ ] Admin bisa verifikasi (approve/reject)
- [ ] Admin bisa edit jam yang disetujui
- [ ] Admin bisa upload bukti pembayaran
- [ ] Admin bisa filter by status/periode/dosen

### Edge Cases
- [ ] Duplicate periode validation
- [ ] Invalid Google Drive link
- [ ] Status transition validation
- [ ] Authorization checks

---

## 🚀 Deployment

1. Run migrations (DONE ✅)
2. Create models and controllers
3. Create routes
4. Create views
5. Update sidebars
6. Test thoroughly
7. Deploy to production

---

## 📝 Notes

- Sistem ini menggunakan Google Drive untuk storage (no local upload)
- Dosen bertanggung jawab share link dengan permission yang benar
- Operator/Admin harus verify link bisa diakses sebelum approve
- History pembayaran permanent (soft delete recommended)
- Consider adding tarif per jam di system settings untuk auto-calculate

---

**Status**: Database schema complete, ready for implementation phase 2
**Next**: Create models, controllers, routes, and views
