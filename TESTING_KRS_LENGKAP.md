# 🧪 Testing Sistem KRS - Panduan Lengkap

## ✅ YANG SUDAH DIBUAT:

### **1. Database** ✅
- ✅ Migration `create_krs_table` - **SUDAH DIJALANKAN**
- ✅ Table `krs` sudah ada di database

### **2. Backend** ✅
- ✅ Model `Krs` dengan relationships
- ✅ Controller `Mahasiswa/KrsController` dengan 5 methods
- ✅ Routes KRS (5 routes)

### **3. Views** ✅
- ✅ `mahasiswa/krs/index.blade.php` - Form KRS
- ✅ `mahasiswa/krs/blocked.blade.php` - Blocked screen
- ✅ `mahasiswa/krs/print.blade.php` - Print KRS

---

## 🚀 LANGKAH TESTING:

### **PERSIAPAN DATA TEST:**

#### **Step 1: Pastikan Semester Aktif Ada**
```bash
php artisan tinker
>>> \App\Models\Semester::where('is_active', true)->first()
```

**Jika NULL, buat semester aktif:**
```bash
>>> \App\Models\Semester::create([
    'semester' => 5,
    'tahun_akademik' => '2024/2025',
    'is_active' => true,
    'tanggal_mulai' => '2024-09-01',
    'tanggal_selesai' => '2025-01-31'
])
```

---

#### **Step 2: Buat Test Mahasiswa (jika belum ada)**
```bash
>>> $user = \App\Models\User::where('role', 'mahasiswa')->first()
>>> $mahasiswa = \App\Models\Mahasiswa::where('user_id', $user->id)->first()
>>> echo "NIM: " . $mahasiswa->nim . "\nNama: " . $mahasiswa->nama_lengkap
```

**Username dan password mahasiswa (default dari seeder):**
- Username: `{NIM}`
- Password: `mahasiswa_staialfatih`

---

#### **Step 3: Buat Mata Kuliah Wajib (jika belum ada)**

Cek mata kuliah wajib:
```bash
>>> $prodi = \App\Models\ProgramStudi::first()
>>> $kurikulum = \App\Models\Kurikulum::where('program_studi_id', $prodi->id)->first()
>>> \App\Models\MataKuliah::where('kurikulum_id', $kurikulum->id)->where('semester', 5)->where('jenis', 'wajib')->get()
```

**Jika kosong, buat mata kuliah wajib:**
```bash
>>> $kurikulum = \App\Models\Kurikulum::where('program_studi_id', 1)->first()
>>> \App\Models\MataKuliah::create([
    'kurikulum_id' => $kurikulum->id,
    'kode_mk' => 'PAI-S1-L-501',
    'nama_mk' => 'Tafsir Tarbawi',
    'sks' => 3,
    'semester' => 5,
    'jenis' => 'wajib'
])
>>> \App\Models\MataKuliah::create([
    'kurikulum_id' => $kurikulum->id,
    'kode_mk' => 'PAI-S1-L-502',
    'nama_mk' => 'Metodologi Pembelajaran PAI',
    'sks' => 3,
    'semester' => 5,
    'jenis' => 'wajib'
])
```

---

#### **Step 4: Buat Nilai Tidak Lulus (untuk test mengulang)**

```bash
>>> $mahasiswa = \App\Models\Mahasiswa::first()
>>> $semester = \App\Models\Semester::where('semester', 3)->first()
>>> $mk = \App\Models\MataKuliah::where('semester', 3)->first()
>>> $dosen = \App\Models\Dosen::first()

>>> \App\Models\Nilai::create([
    'mahasiswa_id' => $mahasiswa->id,
    'semester_id' => $semester->id,
    'mata_kuliah_id' => $mk->id,
    'dosen_id' => $dosen->id,
    'grade' => 'E',
    'nilai_angka' => 45,
    'status' => 'tidak_lulus'
])
```

---

### **TEST 1: Mahasiswa Belum Bayar SPP** 🔴

#### **Skenario:**
Mahasiswa belum bayar SPP, harus tampil blocked screen.

#### **Langkah:**
1. **Login** sebagai mahasiswa
   ```
   http://127.0.0.1:8000/login
   Username: {NIM}
   Password: mahasiswa_staialfatih
   ```

2. **Akses KRS**
   ```
   http://127.0.0.1:8000/mahasiswa/krs
   ```

3. **Expected Result:**
   - ✅ Muncul halaman **blocked screen**
   - ✅ Pesan: "Anda belum melunasi pembayaran SPP untuk semester ini"
   - ✅ Button "Lihat Pembayaran" dan "Kembali ke Dashboard"
   - ✅ Info semester dan data mahasiswa muncul

#### **Screenshot Points:**
- Halaman blocked dengan warning icon
- Pesan error yang jelas
- Button aksi tersedia

---

### **TEST 2: Buat Pembayaran SPP** 💰

#### **Via Tinker:**
```bash
php artisan tinker
>>> $mahasiswa = \App\Models\Mahasiswa::where('nim', '{NIM_TEST}')->first()
>>> $semester = \App\Models\Semester::where('is_active', true)->first()

>>> \App\Models\Pembayaran::create([
    'mahasiswa_id' => $mahasiswa->id,
    'semester_id' => $semester->id,
    'jenis_pembayaran' => 'spp',
    'jumlah' => 1500000,
    'tanggal_jatuh_tempo' => now()->addDays(30),
    'tanggal_bayar' => now(),
    'status' => 'lunas'
])
```

#### **Expected Result:**
- ✅ Pembayaran SPP dengan status **lunas** tercreate

---

### **TEST 3: Akses Form KRS** ✅

#### **Skenario:**
Setelah pembayaran lunas, mahasiswa bisa akses form KRS.

#### **Langkah:**
1. **Refresh** halaman KRS
   ```
   http://127.0.0.1:8000/mahasiswa/krs
   ```

2. **Expected Result:**
   - ✅ Muncul **form KRS** (bukan blocked screen)
   - ✅ Sidebar kiri menampilkan:
     - Info mahasiswa (NIM, Nama, Prodi)
     - Total SKS (0 dari max 24 SKS)
     - IPK terakhir
     - Status KRS: Draft
   - ✅ Section "Mata Kuliah Wajib Semester Ini" muncul
   - ✅ List mata kuliah wajib dengan button "Tambah"
   - ✅ Section "Mata Kuliah Mengulang" muncul (jika ada nilai tidak lulus)

#### **Screenshot Points:**
- Layout 2 kolom (sidebar + form)
- List mata kuliah wajib
- List mata kuliah mengulang (jika ada)
- Total SKS dan progress bar

---

### **TEST 4: Tambah Mata Kuliah Wajib** ➕

#### **Skenario:**
Mahasiswa menambahkan mata kuliah wajib ke KRS.

#### **Langkah:**
1. Di section "Mata Kuliah Wajib", klik button **"+ Tambah"** pada salah satu mata kuliah

2. **Expected Result:**
   - ✅ Halaman refresh
   - ✅ Success message: "Mata kuliah berhasil ditambahkan ke KRS"
   - ✅ Mata kuliah muncul di table "Mata Kuliah yang Diambil"
   - ✅ Total SKS bertambah
   - ✅ Progress bar total SKS bergerak
   - ✅ Button "Tambah" berubah jadi "✓ Sudah ditambah"

3. **Tambah mata kuliah wajib lainnya** (repeat step 1)

#### **Screenshot Points:**
- Success message
- Table "Mata Kuliah yang Diambil" terisi
- Total SKS update
- Progress bar bergerak

---

### **TEST 5: Tambah Mata Kuliah Mengulang** 🔄

#### **Skenario:**
Mahasiswa menambahkan mata kuliah yang tidak lulus (mengulang).

#### **Langkah:**
1. Di section "Mata Kuliah Mengulang", klik button **"+ Ambil Mengulang"**

2. **Expected Result:**
   - ✅ Halaman refresh
   - ✅ Success message
   - ✅ Mata kuliah muncul di table dengan badge **"Mengulang"** (warna orange)
   - ✅ Total SKS bertambah sesuai SKS mata kuliah

#### **Validation Test:**
- Coba tambah mata kuliah yang sudah ditambahkan
- Expected: Error "Mata kuliah sudah ditambahkan ke KRS"

---

### **TEST 6: Validasi Max SKS** ⚠️

#### **Skenario:**
Mahasiswa mencoba mengambil SKS melebihi batas maksimal.

#### **Setup:**
Tambahkan mata kuliah sampai mendekati/melebihi max SKS (24 SKS)

#### **Expected Result:**
- ✅ Error message: "Total SKS melebihi batas maksimal (24 SKS). Saat ini: {current} SKS"
- ✅ Mata kuliah TIDAK ditambahkan
- ✅ Total SKS tetap

#### **Screenshot Points:**
- Error message muncul
- Total SKS tidak berubah

---

### **TEST 7: Hapus Mata Kuliah** ❌

#### **Skenario:**
Mahasiswa menghapus mata kuliah mengulang dari KRS (mata kuliah wajib tidak bisa dihapus).

#### **Langkah:**
1. Di table "Mata Kuliah yang Diambil", klik **"Hapus"** pada mata kuliah mengulang

2. **Confirm** popup: "Hapus mata kuliah ini dari KRS?"

3. **Expected Result:**
   - ✅ Halaman refresh
   - ✅ Success message: "Mata kuliah berhasil dihapus dari KRS"
   - ✅ Mata kuliah hilang dari table
   - ✅ Total SKS berkurang
   - ✅ Progress bar update

#### **Test Hapus Mata Kuliah Wajib:**
- Mata kuliah wajib **tidak ada button "Hapus"**
- Hanya tampil text "Wajib" (gray/disabled)

---

### **TEST 8: Submit KRS** 📤

#### **Skenario:**
Mahasiswa submit KRS setelah selesai memilih mata kuliah.

#### **Langkah:**
1. Pastikan ada minimal 1 mata kuliah dalam KRS

2. Klik button **"📤 Submit KRS"** di pojok kanan atas table

3. **Confirm** popup: "Submit KRS? Setelah submit, KRS tidak bisa diubah lagi."

4. **Expected Result:**
   - ✅ Halaman refresh
   - ✅ Success message: "KRS berhasil disubmit. Menunggu persetujuan admin."
   - ✅ Status KRS berubah jadi **"⏳ Menunggu Persetujuan"** (badge biru)
   - ✅ Button "Submit KRS" hilang
   - ✅ Button "Hapus" pada setiap mata kuliah **HILANG** (tidak bisa edit lagi)
   - ✅ Section "Tambah Mata Kuliah" **HILANG**

#### **Validation:**
- Coba akses halaman KRS lagi → Tetap read-only, tidak bisa edit

---

### **TEST 9: Print KRS** 🖨️

#### **Skenario:**
Mahasiswa mencetak KRS yang sudah submitted/approved.

#### **Langkah:**
1. Klik button **"🖨️ Cetak KRS"** (muncul setelah KRS approved)
   
   **ATAU langsung akses:**
   ```
   http://127.0.0.1:8000/mahasiswa/krs/print
   ```

2. **Expected Result:**
   - ✅ Halaman baru (print-friendly)
   - ✅ Header: Logo, Nama Kampus, "KARTU RENCANA STUDI"
   - ✅ Info semester dan tahun akademik
   - ✅ Status badge: "✓ DISETUJUI" atau "⏳ MENUNGGU PERSETUJUAN"
   - ✅ Info mahasiswa (NIM, Nama, Prodi, Semester, Tanggal Cetak)
   - ✅ Table mata kuliah:
     - No, Kode MK, Nama MK, SKS, Keterangan
   - ✅ Total SKS di footer table
   - ✅ Catatan penting (4 poin)
   - ✅ Section tanda tangan (Mahasiswa & Admin)
   - ✅ Footer: "Printed: {tanggal}"
   - ✅ Button **"🖨️ Cetak KRS"** (floating kanan atas)

3. **Klik button "Cetak KRS"**
   - Expected: Browser print dialog muncul
   - Button print **TIDAK IKUT TERCETAK** (class no-print)

#### **Screenshot Points:**
- Tampilan print preview
- Format rapi dan profesional
- Semua data terisi lengkap

---

## 🔍 EDGE CASES TESTING:

### **Test 1: KRS Kosong**
- Mahasiswa belum ambil mata kuliah apapun
- Expected: Warning "KRS Masih Kosong" dengan icon

### **Test 2: Tidak Ada Mata Kuliah Wajib**
- Semester tidak punya mata kuliah wajib di kurikulum
- Expected: Message "Tidak Ada Mata Kuliah Tersedia"

### **Test 3: Tidak Ada Mata Kuliah Mengulang**
- Mahasiswa tidak punya nilai tidak lulus
- Expected: Section "Mata Kuliah Mengulang" **TIDAK MUNCUL**

### **Test 4: Submit KRS Kosong**
- Coba submit KRS tanpa ambil mata kuliah
- Expected: Error "Tidak ada mata kuliah dalam KRS untuk disubmit"

### **Test 5: Double Click Button Tambah**
- Klik button "Tambah" 2x cepat
- Expected: Tetap hanya 1 mata kuliah yang masuk (unique constraint di database)

---

## 📊 CHECKLIST TESTING:

### **Fungsionalitas Dasar:**
- [ ] Mahasiswa belum bayar → Blocked screen
- [ ] Mahasiswa sudah bayar → Form KRS muncul
- [ ] Tambah mata kuliah wajib → Berhasil
- [ ] Tambah mata kuliah mengulang → Berhasil
- [ ] Total SKS dihitung benar
- [ ] Progress bar SKS update
- [ ] Hapus mata kuliah → Berhasil
- [ ] Submit KRS → Berhasil
- [ ] Setelah submit → Read-only (tidak bisa edit)
- [ ] Print KRS → Format benar

### **Validasi:**
- [ ] Max SKS tervalidasi
- [ ] Duplicate mata kuliah terdeteksi
- [ ] Submit KRS kosong → Error
- [ ] Mata kuliah wajib tidak bisa dihapus

### **UI/UX:**
- [ ] Layout responsive
- [ ] Success/error message muncul
- [ ] Button state update (Tambah → Sudah ditambah)
- [ ] Status badge sesuai (Draft/Submitted/Approved)
- [ ] Print preview rapi

---

## 🐛 TROUBLESHOOTING:

### **Error 1: "Table 'krs' doesn't exist"**
**Solution:**
```bash
php artisan migrate --path=database/migrations/2025_11_25_153616_create_krs_table.php
```

### **Error 2: "Class Krs not found"**
**Solution:**
```bash
composer dump-autoload
php artisan config:clear
```

### **Error 3: "Route [mahasiswa.krs.index] not defined"**
**Solution:**
```bash
php artisan route:clear
php artisan config:clear
```

### **Error 4: Blocked screen terus muncul (padahal sudah bayar)"**
**Cek:**
```bash
php artisan tinker
>>> $mahasiswa = \App\Models\Mahasiswa::where('nim', '{NIM}')->first()
>>> $semester = \App\Models\Semester::where('is_active', true)->first()
>>> \App\Models\Pembayaran::where('mahasiswa_id', $mahasiswa->id)
    ->where('semester_id', $semester->id)
    ->where('jenis_pembayaran', 'spp')
    ->where('status', 'lunas')
    ->first()
```

Jika NULL, buat pembayaran (lihat TEST 2).

### **Error 5: Tidak ada mata kuliah wajib"**
**Solution:** Buat mata kuliah wajib di kurikulum (lihat Step 3 Persiapan Data).

---

## ✅ SUCCESS CRITERIA:

KRS System dianggap **SUKSES** jika:

1. ✅ Mahasiswa yang belum bayar **TIDAK BISA** akses KRS
2. ✅ Mahasiswa yang sudah bayar **BISA** akses dan isi KRS
3. ✅ Mata kuliah wajib otomatis muncul
4. ✅ Mata kuliah tidak lulus bisa dipilih untuk mengulang
5. ✅ Total SKS terhitung dan tervalidasi
6. ✅ Max SKS based on IPK tervalidasi
7. ✅ Submit KRS → Status berubah jadi "Submitted"
8. ✅ Setelah submit → KRS read-only (tidak bisa edit)
9. ✅ Print KRS → Format rapi dan profesional
10. ✅ Tidak ada error di console

---

## 📝 NEXT FEATURES (Optional):

1. **Admin KRS Management**
   - Approve/reject KRS mahasiswa
   - View list KRS per semester
   - Export KRS to Excel

2. **Notification**
   - Email notification setelah KRS approved/rejected
   - Alert jika mendekati deadline KRS

3. **Deadline KRS**
   - Setting tanggal deadline pengisian KRS
   - Auto-lock KRS setelah deadline

---

**Selamat Testing!** 🚀

Jika ada bug atau error, capture screenshot dan error message untuk debugging.
