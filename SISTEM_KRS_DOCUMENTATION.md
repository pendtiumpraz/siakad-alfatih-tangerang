# 📚 SISTEM KRS (KARTU RENCANA STUDI)
## DOKUMENTASI LENGKAP & FLOW CHART

**Dibuat:** 27 November 2025  
**Updated:** 27 November 2025  
**Status:** ✅ PRODUCTION READY  
**Progress:** 95% Complete

---

## 🔗 STATUS INTEGRASI DENGAN SISTEM LAIN

### ✅ **FULLY INTEGRATED:**

#### **1. PEMBAYARAN (SPP) - 100% INTEGRATED** 🟢
- ✅ **Mahasiswa Side:**
  - Block KRS jika belum bayar SPP (`KrsController@index` line 41-45)
  - Query: `Pembayaran::where('status', 'lunas')`
  - Show blocked view dengan link ke pembayaran
  
- ✅ **Admin Side:**
  - Dashboard count: mahasiswa belum bayar SPP (`KrsApprovalController@index` line 68-75)
  - Filter by payment status (paid/unpaid) (`detail()` line 142-153)
  - SPP status badge per mahasiswa (`detail()` line 177-181)
  - Mass approve: Auto-skip belum bayar SPP (`massApproveProdi()` line 261-265)
  - Individual approve: Validasi SPP mandatory (`approve()` line 371-380)
  - Force approve: Override SPP validation (`approve()` line 392-394)

#### **2. NILAI (MENGULANG) - 100% INTEGRATED** 🟢
- ✅ **Query tidak lulus:**
  - `Nilai::where('status', 'tidak_lulus')` (`KrsController@index` line 80-81)
  - Display list mata kuliah mengulang di KRS form
  - Only show if jadwal exists di semester aktif
  
- ✅ **KRS Flag:**
  - `is_mengulang` column to differentiate wajib vs mengulang
  - Mata kuliah mengulang dapat dihapus, wajib tidak bisa

#### **3. JADWAL - 100% INTEGRATED** 🟢
- ✅ **Auto-populate validation:**
  - Check jadwal exists sebelum auto-populate (`autoPopulateMataKuliahWajib()` line 149-150)
  - Skip mata kuliah jika tidak ada jadwal
  
- ✅ **Schedule conflict detection:**
  - `checkScheduleConflict()` method (`KrsController` line 257-293)
  - Compare hari sama + time overlap
  - Block tambah mengulang jika bentrok
  - Error message with detail (hari, jam)
  
- ✅ **Display jadwal:**
  - Eager load jadwal di KRS form (`index()` line 57-59)
  - Show: Hari, Jam, Ruangan, Dosen
  - Print view dengan jadwal lengkap

#### **4. KHS - NO DIRECT INTEGRATION (BY DESIGN)** 🟡
- ⏸️ **Integration Flow (Berjenjang):**
  ```
  KRS (Rencana) → Jadwal → Perkuliahan → Nilai → KHS (Hasil)
  ```
  - KRS tidak langsung ke KHS
  - KHS dibuat dari Nilai (end of semester)
  - KHS = Summary: IP, IPK, Total SKS
  
- ⏸️ **Relationship:**
  - Mahasiswa → hasMany KHS ✅
  - Mahasiswa → hasMany Nilai ✅
  - Mahasiswa → hasMany KRS ✅ (BARU DITAMBAHKAN!)
  - No direct KRS → KHS (not needed)

---

## 🔧 FIXES APPLIED

### **FIX #1: Missing Relationship (CRITICAL)** ✅
**Problem:** `Call to undefined method App\Models\Mahasiswa::krs()`

**Solution:**
```php
// app/Models/Mahasiswa.php (line 189-192)
public function krs()
{
    return $this->hasMany(Krs::class);
}
```

**Impact:** All KRS queries using `$mahasiswa->krs()` now work!

---

---

## 📊 PROGRESS COMPLETION

### ✅ **COMPLETED (95%)**

#### **1. Database & Model (100%)**
- [x] Migration `create_krs_table.php`
- [x] Model `Krs.php` dengan relationships
- [x] Unique constraint (mahasiswa, semester, mata_kuliah)
- [x] Status enum (draft, submitted, approved, rejected)
- [x] Timestamps (submitted_at, approved_at)
- [x] Foreign keys (mahasiswa, semester, mata_kuliah, approved_by)

#### **2. Mahasiswa KRS Controller (100%)**
- [x] `index()` - Display KRS dengan auto-populate
- [x] `autoPopulateMataKuliahWajib()` - Auto add mata kuliah wajib
- [x] `calculateMahasiswaSemester()` - Calculate semester mahasiswa
- [x] `store()` - Add mata kuliah mengulang
- [x] `checkScheduleConflict()` - Validasi jadwal bentrok
- [x] `isTimeOverlap()` - Time overlap logic
- [x] `destroy()` - Remove mengulang (wajib cannot be removed)
- [x] `submit()` - Submit KRS (1-24 SKS validation)
- [x] `print()` - Print KRS view

#### **3. Admin KRS Approval Controller (100%)**
- [x] `index()` - Dashboard overview per prodi
- [x] `detail()` - List mahasiswa dengan filters
- [x] `show()` - Individual KRS detail
- [x] `massApproveProdi()` - Approve semua per prodi (auto-skip belum bayar SPP)
- [x] `massApproveSelected()` - Approve selected mahasiswa
- [x] `approve()` - Individual approve dengan SPP validation
- [x] `reject()` - Reject dengan keterangan
- [x] Force approve feature untuk override SPP validation

#### **4. Mahasiswa Views (100%)**
- [x] `krs/index.blade.php` - Form KRS dengan auto-populate info
- [x] `krs/blocked.blade.php` - Blocked jika belum bayar SPP
- [x] `krs/print.blade.php` - Print view dengan jadwal lengkap

#### **5. Admin Views (100%)**
- [x] `krs-approval/index.blade.php` - Dashboard overview per prodi
- [x] `krs-approval/detail.blade.php` - List dengan filter & checkbox
- [x] `krs-approval/show.blade.php` - Individual KRS dengan force approve

#### **6. Routes (100%)**
- [x] Mahasiswa routes (`krs.*`)
- [x] Admin routes (`krs-approval.*`)
- [x] Operator routes (`krs-approval.*`)

#### **7. Sidebar Menu (100%)**
- [x] Admin sidebar dengan notification badge
- [x] Operator sidebar dengan notification badge
- [x] Real-time pending count display

#### **8. Business Logic (100%)**
- [x] SPP payment validation
- [x] Auto-populate mata kuliah wajib
- [x] Schedule conflict detection
- [x] Semester calculation (angkatan-based)
- [x] Force approve untuk kasus khusus
- [x] Mass approve dengan auto-skip unpaid
- [x] Maximum 24 SKS validation
- [x] Mata kuliah wajib cannot be removed

---

### ⏳ **PENDING (5%)**

#### **1. Jadwal Mahasiswa (50% - Need Views)**
- [x] Controller logic planned
- [x] Integration with approved KRS
- [ ] View: `mahasiswa/jadwal/index.blade.php`
- [ ] View: `mahasiswa/jadwal/empty.blade.php`
- [ ] Menu link in mahasiswa sidebar

#### **2. Notification System (0% - Optional)**
- [ ] Email notification after approve
- [ ] Email notification after reject
- [ ] Dashboard notification banner
- [ ] WhatsApp integration (optional)

#### **3. Kalender Akademik (0% - Phase 2)**
- [ ] Database migration
- [ ] Model & Controller
- [ ] CRUD views
- [ ] Integration dengan KRS period validation

---

## 🔄 COMPLETE FLOW CHART

### **FLOW 1: MAHASISWA MENGISI KRS**

```
┌─────────────────────────────────────────────────────────────────┐
│ START: Mahasiswa Login                                          │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ Klik Menu "KRS"                                                 │
│ Route: /mahasiswa/krs                                           │
│ Controller: MahasiswaKrsController@index()                      │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ VALIDASI 1: Apakah ada semester aktif?                         │
│ Query: Semester::where('is_active', true)->first()             │
└────────────────────┬────────────────────────────────────────────┘
                     │
         ┌───────────┴────────────┐
         │                        │
    ❌ TIDAK                   ✅ ADA
         │                        │
         ▼                        ▼
┌─────────────────┐    ┌─────────────────────────────────────────┐
│ Show: Tidak ada │    │ VALIDASI 2: Sudah bayar SPP?            │
│ semester aktif  │    │ Query: Pembayaran::where([             │
│ (blocked view)  │    │   'mahasiswa_id' => $id,               │
└─────────────────┘    │   'semester_id' => $semester->id,      │
                       │   'jenis_pembayaran' => 'spp',         │
                       │   'status' => 'lunas'                  │
                       │ ])->exists()                           │
                       └──────────────┬──────────────────────────┘
                                      │
                          ┌───────────┴────────────┐
                          │                        │
                     ❌ BELUM                   ✅ SUDAH
                          │                        │
                          ▼                        ▼
                ┌─────────────────────┐  ┌────────────────────────┐
                │ Show: Blocked View  │  │ Check: KRS sudah ada?  │
                │ "Belum Bayar SPP"   │  │ Query: Krs::where([    │
                │ + Link Pembayaran   │  │   'mahasiswa_id',      │
                │                     │  │   'semester_id'        │
                └─────────────────────┘  │ ])->exists()           │
                                         └────────┬───────────────┘
                                                  │
                                      ┌───────────┴────────────┐
                                      │                        │
                                 ❌ BELUM                   ✅ SUDAH
                                      │                        │
                                      ▼                        ▼
                        ┌──────────────────────────┐  ┌────────────────┐
                        │ AUTO-POPULATE MK WAJIB!  │  │ Load KRS Exist │
                        │ Method:                   │  │ Skip populate  │
                        │ autoPopulateMataKuliahWajib()│  │            │
                        │                           │  └────────┬───────┘
                        │ 1. Get Kurikulum Aktif   │           │
                        │ 2. Calculate Semester Mhs│           │
                        │ 3. Get MK Wajib semester │           │
                        │ 4. Check Jadwal Exist    │           │
                        │ 5. Insert to KRS         │           │
                        │    Status: draft         │           │
                        │    is_mengulang: false   │           │
                        └────────────┬──────────────┘           │
                                     │                          │
                                     └──────────┬───────────────┘
                                                │
                                                ▼
┌─────────────────────────────────────────────────────────────────┐
│ DISPLAY KRS FORM                                                │
│                                                                 │
│ Components:                                                     │
│ 1. Info Mahasiswa (NIM, Nama, Prodi)                          │
│ 2. Total SKS Counter                                           │
│ 3. Status KRS Badge                                            │
│ 4. Table Mata Kuliah:                                          │
│    - Mata Kuliah Wajib (auto-added) ✅                        │
│    - Mata Kuliah Mengulang (pilihan) 🔄                       │
│    - Button Hapus (hanya untuk mengulang)                     │
│ 5. Section "Mata Kuliah Mengulang"                            │
│    Source: Nilai::where('status', 'tidak_lulus')              │
│    Show only if: Ada jadwal di semester ini                   │
│ 6. Button "Submit KRS" (jika status = draft)                  │
│                                                                 │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ USER ACTION: Tambah Mata Kuliah Mengulang (Optional)           │
│ Click: "Ambil Mengulang" button                                │
│ Action: POST /mahasiswa/krs                                    │
│ Controller: store()                                             │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ VALIDASI TAMBAH MENGULANG:                                     │
│                                                                 │
│ 1. Check KRS masih draft? ✓                                    │
│ 2. Check MK belum ada di KRS? ✓                               │
│ 3. Check Schedule Conflict:                                     │
│    - Get jadwal MK baru                                        │
│    - Get jadwal MK existing di KRS                            │
│    - Compare: Same hari + Time overlap?                        │
│    - If conflict → ERROR with detail                           │
│    - If OK → INSERT                                            │
│                                                                 │
└────────────────────┬────────────────────────────────────────────┘
                     │
         ┌───────────┴────────────┐
         │                        │
    ❌ CONFLICT               ✅ OK
         │                        │
         ▼                        ▼
┌─────────────────┐    ┌──────────────────────────┐
│ Show Error:     │    │ INSERT KRS:              │
│ "Jadwal bentrok │    │ - mata_kuliah_id         │
│  dengan MK X    │    │ - is_mengulang: true     │
│  pada Hari,     │    │ - status: draft          │
│  Jam-Jam"       │    │ Success! Reload page     │
└─────────────────┘    └────────────┬──────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────┐
│ USER ACTION: Hapus Mata Kuliah Mengulang (Optional)            │
│ Click: "Hapus" button                                          │
│ Action: DELETE /mahasiswa/krs/{id}                             │
│ Controller: destroy()                                           │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ VALIDASI HAPUS:                                                │
│                                                                 │
│ 1. Check is_mengulang = true? ✓                               │
│    (Mata kuliah wajib TIDAK BISA DIHAPUS!)                    │
│ 2. Check status = draft? ✓                                     │
│ 3. DELETE from KRS                                             │
│                                                                 │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ USER ACTION: SUBMIT KRS                                        │
│ Click: "📤 Submit KRS" button                                  │
│ Action: POST /mahasiswa/krs/submit                             │
│ Controller: submit()                                            │
│ Confirmation: "Submit KRS? Setelah submit tidak bisa edit"    │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ VALIDASI SUBMIT:                                               │
│                                                                 │
│ 1. Check ada KRS dengan status draft? ✓                       │
│ 2. Check Total SKS >= 1? ✓                                    │
│ 3. Check Total SKS <= 24? ✓                                   │
│ 4. UPDATE all KRS:                                             │
│    - status: draft → submitted                                 │
│    - submitted_at: now()                                       │
│                                                                 │
└────────────────────┬────────────────────────────────────────────┘
                     │
         ┌───────────┴────────────┐
         │                        │
    ❌ FAIL                    ✅ SUCCESS
         │                        │
         ▼                        ▼
┌─────────────────┐    ┌──────────────────────────┐
│ Show Error:     │    │ Success Message:         │
│ "Minimal 1 SKS" │    │ "KRS berhasil disubmit"  │
│ atau            │    │ "Menunggu persetujuan    │
│ "Maksimal 24"   │    │  Dosen PA"               │
└─────────────────┘    │                          │
                       │ Status KRS: ⏳ Pending   │
                       │ Button "Submit" → Hidden │
                       │ KRS LOCKED (tidak bisa   │
                       │ edit/tambah/hapus)       │
                       └────────────┬──────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────┐
│ WAITING: Admin/Operator Approval                               │
│ (Lanjut ke FLOW 2)                                             │
└─────────────────────────────────────────────────────────────────┘
```

---

### **FLOW 2: ADMIN/OPERATOR APPROVAL KRS**

```
┌─────────────────────────────────────────────────────────────────┐
│ START: Admin/Operator Login                                    │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ Klik Menu "📋 Approval KRS"                                    │
│ Notification Badge: [120] ← Pending count                      │
│ Route: /admin/krs-approval                                     │
│ Controller: KrsApprovalController@index()                      │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ DASHBOARD VIEW - Overview Per Program Studi                    │
│                                                                 │
│ Display untuk setiap Prodi:                                    │
│ ┌─── Program Studi: PAI ───────────────────────┐             │
│ │ Statistics:                                    │             │
│ │ • Total Mahasiswa: 150                         │             │
│ │ • Sudah Submit: 120 (80%)                     │             │
│ │ • Belum Submit: 20 (13%)                      │             │
│ │ • Belum Bayar SPP: 10 (7%)                    │             │
│ │                                                │             │
│ │ Status Approval:                               │             │
│ │ • ⏳ Pending: 120 KRS                          │             │
│ │ • ✅ Approved: 0 KRS                           │             │
│ │ • ❌ Rejected: 0 KRS                           │             │
│ │                                                │             │
│ │ Actions:                                       │             │
│ │ [✅ Approve Semua (120)] [📋 Detail]          │             │
│ └────────────────────────────────────────────────┘             │
│                                                                 │
└────────────┬────────────────────────────────────────────────────┘
             │
             ▼
┌─────────────────────────────────────────────────────────────────┐
│ ADMIN CHOICE: Pilih Action                                     │
└─────────┬───────────────────────────────┬───────────────────────┘
          │                               │
    ┌─────┴─────┐                  ┌──────┴──────┐
    │           │                  │             │
    ▼           ▼                  ▼             ▼
┌────────┐ ┌──────────┐    ┌──────────┐  ┌─────────────┐
│ Mass   │ │ Selective│    │ Individual│  │ Detail View │
│ Approve│ │ Approve  │    │ Approve   │  │ Only        │
└────┬───┘ └────┬─────┘    └─────┬─────┘  └──────┬──────┘
     │          │                 │               │
     │          │                 │               │
     │          │                 │               ▼
     │          │                 │        ┌──────────────┐
     │          │                 │        │ View Stats,  │
     │          │                 │        │ Export, etc  │
     │          │                 │        └──────────────┘
     │          │                 │
     ▼          ▼                 ▼
┌─────────────────────────────────────────────────────────────────┐
│ OPTION A: MASS APPROVE PER PRODI                               │
│ Click: "✅ Approve Semua (120)" button                         │
│ Confirmation: "Approve SEMUA KRS yang sudah bayar SPP?"        │
│ Action: POST /admin/krs-approval/prodi/{id}/mass-approve      │
│ Controller: massApproveProdi()                                  │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ MASS APPROVE LOGIC:                                            │
│                                                                 │
│ 1. Query Mahasiswa dengan:                                     │
│    ✓ program_studi_id = {prodi}                               │
│    ✓ status = 'aktif'                                         │
│    ✓ KRS status = 'submitted'                                 │
│    ✓ SPP status = 'lunas' ← PENTING!                         │
│                                                                 │
│ 2. UPDATE KRS for filtered mahasiswa:                          │
│    - status: submitted → approved                              │
│    - approved_at: now()                                        │
│    - approved_by: admin_id                                     │
│    - keterangan: "Approved via mass approval"                 │
│                                                                 │
│ 3. Count yang di-skip (belum bayar SPP)                       │
│                                                                 │
│ 4. Show Result:                                                │
│    "Berhasil approve 140 KRS untuk Program Studi PAI.         │
│     10 mahasiswa di-skip karena belum bayar SPP."             │
│                                                                 │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ├────────────► Go to Success (bottom)
                     │
┌─────────────────────────────────────────────────────────────────┐
│ OPTION B: SELECTIVE APPROVE                                    │
│ Click: "📋 Detail" button                                      │
│ Route: /admin/krs-approval/prodi/{id}                         │
│ Controller: detail()                                            │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ DETAIL VIEW - List Mahasiswa                                   │
│                                                                 │
│ Summary Stats: [Total: 150] [Pending: 120] [Approved: 0]      │
│                                                                 │
│ Filters:                                                        │
│ • Status KRS: [Semua/Submitted/Approved/Rejected/Belum] ▼     │
│ • Status SPP: [Semua/Sudah Bayar/Belum Bayar] ▼               │
│ • Search: [🔍 NIM atau Nama...]                               │
│                                                                 │
│ Actions:                                                        │
│ [☑ Pilih Semua] 3 dipilih                                     │
│ [✅ Approve Selected (3)]                                      │
│                                                                 │
│ Table:                                                          │
│ ┌───┬─────────┬────────────┬────┬─────┬──────────┬──────────┐│
│ │☑ │NIM      │Nama        │Smt │SKS  │Status KRS│Status SPP││
│ ├───┼─────────┼────────────┼────┼─────┼──────────┼──────────┤│
│ │☑ │2024001  │Ahmad Fauzi │ 1  │ 18  │⏳ Pending│✅ Lunas  ││
│ │☑ │2024002  │Budi        │ 1  │ 20  │⏳ Pending│❌ Belum  ││
│ │☑ │2024003  │Citra       │ 3  │ 16  │⏳ Pending│✅ Lunas  ││
│ │  │2024004  │Dani        │ 3  │ 18  │✅Approved│✅ Lunas  ││
│ └───┴─────────┴────────────┴────┴─────┴──────────┴──────────┘│
│                                                                 │
│ JavaScript: Auto-update selected count, enable/disable button  │
│                                                                 │
└────────────┬────────────────────────────────────────────────────┘
             │
   ┌─────────┴────────────┐
   │                      │
   ▼                      ▼
┌─────────────┐   ┌──────────────────┐
│ Checkbox    │   │ Click "Detail"   │
│ Selected    │   │ on Individual    │
└──────┬──────┘   └────────┬─────────┘
       │                   │
       ▼                   │
┌─────────────────────────────┐│
│ APPROVE SELECTED            ││
│ Action: POST mass-approve-  ││
│         selected            ││
│ Data: mahasiswa_ids[] array ││
└────────────┬─────────────────┘│
             │                  │
             ▼                  │
┌─────────────────────────────────────────────────────────────────┐
│ APPROVE SELECTED LOGIC:                                        │
│                                                                 │
│ 1. Get mahasiswa_ids from checkbox                             │
│ 2. Filter: Only submitted KRS                                  │
│ 3. UPDATE for each ID:                                         │
│    - status: submitted → approved                              │
│    - approved_at: now()                                        │
│    - approved_by: admin_id                                     │
│                                                                 │
│ Note: Mahasiswa yang belum bayar SPP TETAP DI-APPROVE         │
│       (karena admin sudah pilih manual)                        │
│                                                                 │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ├────────────► Go to Success (bottom)
                     │
┌─────────────────────────────────────────────────────────────────┐
│ OPTION C: INDIVIDUAL APPROVE                                   │
│ Click: "🔍 Detail" on specific mahasiswa                      │
│ Route: /admin/krs-approval/mahasiswa/{id}                     │
│ Controller: show()                                              │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ INDIVIDUAL KRS VIEW                                            │
│                                                                 │
│ Left Sidebar: Mahasiswa Info                                   │
│ ┌────────────────────────────┐                                │
│ │ NIM: 2024001               │                                │
│ │ Nama: Ahmad Fauzi          │                                │
│ │ Prodi: PAI - S1            │                                │
│ │ Semester: 1                │                                │
│ │                            │                                │
│ │ Status SPP:                │                                │
│ │ [✅ Lunas] atau [❌ Belum] │                                │
│ │                            │                                │
│ │ Total SKS: 18              │                                │
│ │                            │                                │
│ │ Status KRS: ⏳ Pending     │                                │
│ └────────────────────────────┘                                │
│                                                                 │
│ Right Panel: Table Mata Kuliah                                 │
│ ┌──────────────────────────────────────────────────┐          │
│ │ No│Kode│Nama MK│SKS│Jenis│Jadwal               │          │
│ ├───┼────┼───────┼───┼─────┼─────────────────────┤          │
│ │ 1 │PAI-│Pancas │ 2 │Wajib│Senin, 08:00-10:00  │          │
│ │   │1-01│       │   │     │R101 | Dr. Ahmad     │          │
│ │ 2 │PAI-│B.Arab │ 2 │Wajib│Selasa, 10:00-12:00 │          │
│ │ 3 │...                                          │          │
│ ├───┴────┴───────┴───┴─────┴─────────────────────┤          │
│ │ Total SKS: 18                                   │          │
│ └─────────────────────────────────────────────────┘          │
│                                                                 │
└────────────┬────────────────────────────────────────────────────┘
             │
   ┌─────────┴────────────┐
   │                      │
   ▼                      ▼
┌──────────────────┐  ┌──────────────────┐
│ IF Sudah Bayar   │  │ IF Belum Bayar   │
│ SPP              │  │ SPP              │
└─────┬────────────┘  └────────┬─────────┘
      │                        │
      ▼                        ▼
┌─────────────────┐   ┌────────────────────────────┐
│ Show:           │   │ Show:                      │
│ [✅ Approve KRS]│   │ ⚠️ Warning Box:            │
│ [❌ Reject KRS] │   │ "Mahasiswa belum bayar SPP"│
└────────┬────────┘   │                            │
         │            │ 🔓 Force Approve Section:  │
         │            │ ┌────────────────────────┐ │
         │            │ │ Alasan Force Approve:* │ │
         │            │ │ [________________]     │ │
         │            │ │ [🔓 Force Approve]    │ │
         │            │ └────────────────────────┘ │
         │            │ [❌ Reject KRS]            │
         │            └──────────┬─────────────────┘
         │                       │
         ▼                       ▼
┌─────────────────────────────────────────────────────────────────┐
│ APPROVE ACTION                                                 │
│ Action: POST /admin/krs-approval/mahasiswa/{id}/approve       │
│ Controller: approve()                                           │
│                                                                 │
│ Data:                                                           │
│ - semester_id                                                   │
│ - keterangan (optional)                                        │
│ - force_approve (boolean) ← TRUE jika dari force approve       │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ APPROVE VALIDATION:                                            │
│                                                                 │
│ IF force_approve = FALSE:                                      │
│   1. Check SPP Payment:                                        │
│      Query: Pembayaran::where([                               │
│        'mahasiswa_id',                                         │
│        'semester_id',                                          │
│        'jenis_pembayaran' => 'spp',                           │
│        'status' => 'lunas'                                    │
│      ])->exists()                                             │
│                                                                 │
│   2. IF belum bayar:                                          │
│      → ERROR: "Mahasiswa belum bayar SPP. Tidak bisa approve"│
│                                                                 │
│ IF force_approve = TRUE:                                       │
│   → SKIP SPP validation                                        │
│   → Prepend "[FORCE APPROVE]" to keterangan                   │
│                                                                 │
│ UPDATE KRS:                                                     │
│ - status: submitted → approved                                 │
│ - approved_at: now()                                           │
│ - approved_by: admin_id                                        │
│ - keterangan: with [FORCE APPROVE] if forced                  │
│                                                                 │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ├────────────► Go to Success (bottom)
                     │
┌─────────────────────────────────────────────────────────────────┐
│ REJECT ACTION                                                  │
│ Click: "❌ Reject KRS" button                                  │
│ Show: Modal with form                                          │
│ Action: POST /admin/krs-approval/mahasiswa/{id}/reject        │
│ Controller: reject()                                            │
│                                                                 │
│ Data:                                                           │
│ - semester_id                                                   │
│ - keterangan (required!) ← Alasan reject                      │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ REJECT LOGIC:                                                  │
│                                                                 │
│ 1. Validation: Keterangan must be filled                      │
│ 2. UPDATE KRS:                                                 │
│    - status: submitted → rejected                              │
│    - approved_at: NULL                                         │
│    - approved_by: NULL                                         │
│    - keterangan: alasan reject                                │
│                                                                 │
│ 3. Mahasiswa can edit & submit ulang                           │
│                                                                 │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ SUCCESS RESULT (All Approve Actions)                           │
│                                                                 │
│ 1. Database Updated                                            │
│ 2. Notification Badge Updated (-1 or more)                     │
│ 3. Success Message Displayed                                   │
│ 4. IF Approved:                                                │
│    → Mahasiswa can now see JADWAL                             │
│    → KRS LOCKED (tidak bisa edit)                             │
│    → Button "🖨️ Cetak KRS" available                          │
│ 5. IF Rejected:                                                │
│    → Mahasiswa can edit & submit ulang                        │
│    → Show keterangan reject                                    │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

### **FLOW 3: JADWAL MAHASISWA (After Approved)**

```
┌─────────────────────────────────────────────────────────────────┐
│ START: Mahasiswa Login (KRS Sudah Approved)                   │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ Klik Menu "📅 Jadwal Kuliah Saya"                             │
│ Route: /mahasiswa/jadwal                                       │
│ Controller: JadwalMahasiswaController@index() [PLANNED]        │
└────────────────────┬────────────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────────────┐
│ VALIDASI: KRS sudah di-approve?                               │
│ Query: Krs::where([                                            │
│   'mahasiswa_id' => $id,                                       │
│   'semester_id' => $activeSemester->id,                       │
│   'status' => 'approved'                                       │
│ ])->pluck('mata_kuliah_id')                                   │
└────────────────────┬────────────────────────────────────────────┘
                     │
         ┌───────────┴────────────┐
         │                        │
    ❌ BELUM                   ✅ SUDAH
         │                        │
         ▼                        ▼
┌─────────────────┐    ┌──────────────────────────────────────────┐
│ Show: Empty View│    │ Query Jadwal based on approved KRS MK:   │
│ "KRS Anda belum │    │                                           │
│  di-approve.    │    │ Jadwal::whereIn('mata_kuliah_id',        │
│  Jadwal akan    │    │   $approvedMataKuliahIds)                │
│  tampil setelah │    │ ->where('semester_id', $activeSemester)  │
│  KRS approved"  │    │ ->with(['mataKuliah', 'dosen',           │
│                 │    │         'ruangan'])                       │
└─────────────────┘    │ ->orderBy('hari')                        │
                       │ ->orderBy('jam_mulai')                   │
                       │ ->get()                                   │
                       └────────────┬──────────────────────────────┘
                                    │
                                    ▼
┌─────────────────────────────────────────────────────────────────┐
│ DISPLAY JADWAL VIEW                                            │
│                                                                 │
│ Format: Calendar View atau Table View                          │
│                                                                 │
│ Calendar View (Weekly):                                        │
│ ┌─────┬─────────┬─────────┬─────────┬─────────┬─────────┐    │
│ │Waktu│ Senin   │ Selasa  │ Rabu    │ Kamis   │ Jumat   │    │
│ ├─────┼─────────┼─────────┼─────────┼─────────┼─────────┤    │
│ │08:00│Pancasila│         │         │         │         │    │
│ │-10:00│Dr.Ahmad│         │         │         │         │    │
│ │     │ R101    │         │         │         │         │    │
│ ├─────┼─────────┼─────────┼─────────┼─────────┼─────────┤    │
│ │10:00│         │B.Arab   │         │         │         │    │
│ │-12:00│         │Ust.Budi│         │         │         │    │
│ │     │         │ R102    │         │         │         │    │
│ └─────┴─────────┴─────────┴─────────┴─────────┴─────────┘    │
│                                                                 │
│ Table View:                                                     │
│ ┌────┬──────────┬───────────┬──────────┬─────────┬─────────┐ │
│ │No  │Mata Kuliah│Dosen     │Hari      │Jam      │Ruangan  │ │
│ ├────┼──────────┼───────────┼──────────┼─────────┼─────────┤ │
│ │ 1  │Pancasila │Dr. Ahmad  │Senin     │08:00-10 │R101     │ │
│ │ 2  │B. Arab   │Ust. Budi  │Selasa    │10:00-12 │R102     │ │
│ └────┴──────────┴───────────┴──────────┴─────────┴─────────┘ │
│                                                                 │
│ Actions:                                                        │
│ [🖨️ Cetak Jadwal] [📥 Export PDF]                            │
│                                                                 │
└─────────────────────────────────────────────────────────────────┘
```

---

## 🗂️ SIDEBAR MENU STRUCTURE

### **ADMIN SIDEBAR**

```
┌──────────────────────────────────────────────────────────┐
│ 🏛️ STAI AL-FATIH                                        │
│ Sistem Akademik                                          │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ 📊 Dashboard                                             │
│                                                          │
│ 👥 Manajemen User                                        │
│                                                          │
│ 🛡️ Role & Permission                                    │
│                                                          │
│ 📢 Pengumuman                                            │
│                                                          │
│ 📋 Approval KRS                          [120] ← Badge  │
│    ↑                                       ↑             │
│    Menu Baru!                        Pending count       │
│    Icon: clipboard-check                                 │
│                                                          │
│ 🗄️ Master Data ▼                                        │
│    ├─ 🎓 Program Studi                                  │
│    ├─ 📚 Kurikulum                                      │
│    ├─ 📖 Mata Kuliah                                    │
│    ├─ 🚪 Ruangan                                        │
│    ├─ 📅 Semester                                       │
│    ├─ 🕐 Jadwal Perkuliahan                            │
│    ├─ 💰 Penggajian Dosen                              │
│    ├─ 🛤️ Jalur Seleksi                                 │
│    └─ 📥 Import CSV                                     │
│                                                          │
│ 💳 Pembayaran                                            │
│                                                          │
│ 🎓 SPMB                                                  │
│                                                          │
│ 📝 Daftar Ulang                                          │
│                                                          │
│ 📋 KHS                                                   │
│                                                          │
│ 👨‍🏫 Pengurus                                          │
│                                                          │
│ 📖 Dokumentasi                                           │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

### **OPERATOR SIDEBAR**

```
┌──────────────────────────────────────────────────────────┐
│ 🏛️ STAI AL-FATIH                                        │
│ Sistem Akademik                                          │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ 📊 Dashboard                                             │
│                                                          │
│ 💳 Pembayaran                                            │
│                                                          │
│ 📢 Pengumuman                                            │
│                                                          │
│ 📋 Approval KRS                          [45] ← Badge   │
│    ↑                                                     │
│    Menu Baru! (Same as Admin)                           │
│                                                          │
│ 🎓 SPMB                                                  │
│                                                          │
│ 📝 Daftar Ulang                                          │
│                                                          │
│ 🗄️ Master Data (Read-Only) ▼                           │
│    ├─ 🎓 Program Studi                                  │
│    ├─ 📚 Kurikulum                                      │
│    ├─ 📖 Mata Kuliah                                    │
│    └─ 📅 Semester                                       │
│                                                          │
│ 🛤️ Jalur Seleksi                                        │
│                                                          │
│ 📖 Dokumentasi                                           │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

### **MAHASISWA SIDEBAR**

```
┌──────────────────────────────────────────────────────────┐
│ 🏛️ STAI AL-FATIH                                        │
│ Sistem Akademik                                          │
├──────────────────────────────────────────────────────────┤
│                                                          │
│ 📊 Dashboard                                             │
│                                                          │
│ 👤 Profile                                               │
│                                                          │
│ 💳 Pembayaran                                            │
│                                                          │
│ 📋 KRS ← (Main Feature!)                                │
│    - Isi KRS                                             │
│    - Submit KRS                                          │
│    - Cetak KRS (jika approved)                          │
│                                                          │
│ 📅 Jadwal Kuliah Saya ← [PLANNED - 50% Complete]       │
│    - View jadwal based on approved KRS                  │
│    - Export/Print jadwal                                │
│                                                          │
│ 📊 KHS                                                   │
│                                                          │
│ 📚 Kurikulum                                             │
│                                                          │
│ 🎯 Nilai                                                 │
│                                                          │
│ 📢 Pengumuman                                            │
│                                                          │
└──────────────────────────────────────────────────────────┘
```

---

## 📁 FILE STRUCTURE

### **Models**
```
app/Models/
├── Krs.php                    ✅ Complete
│   ├── Fillable fields
│   ├── Relationships: mahasiswa, semester, mataKuliah, approvedBy
│   ├── Scopes: byMahasiswa, bySemester, draft, submitted, approved, mengulang
│   └── Static method: getTotalSks()
│
├── Mahasiswa.php              ✅ Updated
│   └── Relationship: krs()
│
├── Semester.php               ✅ Existing
│   └── Relationship: krs()
│
└── MataKuliah.php             ✅ Existing
    └── Relationship: krs()
```

### **Controllers**
```
app/Http/Controllers/
├── Mahasiswa/
│   └── KrsController.php                      ✅ Complete (372 lines)
│       ├── index()                            - Display & auto-populate
│       ├── autoPopulateMataKuliahWajib()     - Auto add wajib MK
│       ├── calculateMahasiswaSemester()      - Calculate semester
│       ├── store()                           - Add mengulang
│       ├── checkScheduleConflict()           - Validate bentrok
│       ├── isTimeOverlap()                   - Time overlap logic
│       ├── destroy()                         - Remove mengulang
│       ├── submit()                          - Submit KRS
│       └── print()                           - Print view
│
└── Admin/
    ├── KrsApprovalController.php              ✅ Complete (432 lines)
    │   ├── index()                            - Dashboard per prodi
    │   ├── detail()                           - List with filters
    │   ├── show()                             - Individual detail
    │   ├── massApproveProdi()                 - Mass approve (auto-skip unpaid)
    │   ├── massApproveSelected()              - Approve selected
    │   ├── approve()                          - Individual approve + force
    │   └── reject()                           - Reject with reason
    │
    └── [PLANNED] JadwalMahasiswaController.php   ⏳ 50% (Logic ready, views needed)
        └── index()                            - Show jadwal from approved KRS
```

### **Views**
```
resources/views/
├── mahasiswa/
│   ├── krs/
│   │   ├── index.blade.php        ✅ Complete (297 lines)
│   │   ├── blocked.blade.php      ✅ Complete
│   │   └── print.blade.php        ✅ Complete
│   │
│   └── jadwal/                    ⏳ PLANNED (50% - Need to create)
│       ├── index.blade.php        ⏳ Calendar/Table view
│       └── empty.blade.php        ⏳ Empty state
│
└── admin/
    └── krs-approval/
        ├── index.blade.php        ✅ Complete (273 lines)
        ├── detail.blade.php       ✅ Complete (323 lines)
        └── show.blade.php         ✅ Complete (291 lines)
```

### **Routes**
```
routes/web.php

Mahasiswa Routes:                              ✅ Complete
├── GET  /mahasiswa/krs                       - index
├── POST /mahasiswa/krs                       - store (add mengulang)
├── DELETE /mahasiswa/krs/{id}                - destroy (remove mengulang)
├── POST /mahasiswa/krs/submit                - submit
└── GET  /mahasiswa/krs/print                 - print

Mahasiswa Jadwal Routes:                       ⏳ PLANNED
└── GET  /mahasiswa/ja
