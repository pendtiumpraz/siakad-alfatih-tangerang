# 🔄 Perubahan Sistem KRS

## ⚡ PERUBAHAN MAJOR:

### **1. HAPUS Batas Maksimal SKS** ❌
**Sebelumnya:** 
- Ada batas max SKS berdasarkan IPK (15-24 SKS)
- Mahasiswa dibatasi ambil mata kuliah

**Sekarang:**
- ❌ **TIDAK ADA** batas max SKS
- ✅ Mahasiswa **WAJIB ambil SEMUA** mata kuliah semester berjalan
- ✅ Total SKS = informational saja (tidak ada validasi)

---

### **2. Sistem Mengulang yang Lebih Fleksibel** 🔄
**Sebelumnya:**
- Hanya bisa mengulang mata kuliah semester sebelumnya

**Sekarang:**
- ✅ Bisa mengulang mata kuliah **KAPAN SAJA** dari semester 1-14
- ✅ Semua mata kuliah yang tidak lulus dari **SEMUA semester sebelumnya** bisa diulang
- ✅ Contoh: Semester 5 tidak lulus MK semester 1 → bisa ulang di semester 6, 7, 8, ... s/d 14
- ✅ **WAJIB lulus semua** pada akhirnya (semester 14)

---

### **3. Validasi Jadwal Bentrok** ⚠️
**Baru ditambahkan:**
- ✅ Sistem cek jadwal otomatis
- ✅ Mahasiswa **TIDAK BISA** ambil mata kuliah mengulang jika jadwalnya **BENTROK** dengan mata kuliah semester berjalan
- ✅ Validasi: Hari dan Jam
- ✅ Error message: "Jadwal bentrok! Mata kuliah ini bertabrakan dengan: {nama_mk} ({hari} {jam})"

---

### **4. Auto-Add Semua Mata Kuliah Wajib** ⚡
**Sebelumnya:**
- Mahasiswa klik button "Tambah" untuk setiap mata kuliah wajib

**Sekarang:**
- ✅ Sistem **TIDAK PERLU** button tambah untuk mata kuliah wajib
- ✅ Mahasiswa hanya perlu **SUBMIT KRS**
- ✅ Semua mata kuliah wajib semester berjalan **OTOMATIS** masuk KRS
- ✅ Yang optional: **Mata kuliah mengulang** (pilih manual)

---

## 📋 FLOW BARU:

### **Step 1: Mahasiswa Akses KRS**
- Cek pembayaran SPP
- Jika lunas → Tampil form KRS

### **Step 2: Sistem Auto List Mata Kuliah Wajib**
- ✅ List semua mata kuliah wajib semester ini
- ✅ **Tidak perlu button tambah**
- ✅ Mahasiswa hanya lihat list (read-only)

### **Step 3: Mahasiswa Pilih Mata Kuliah Mengulang (Opsional)**
- ✅ List semua mata kuliah tidak lulus dari **SEMUA semester sebelumnya**
- ✅ Mahasiswa klik button "Ambil Mengulang"
- ✅ Sistem cek jadwal bentrok
- ✅ Jika jadwal OK → Mata kuliah masuk KRS
- ✅ Jika jadwal bentrok → Error, tidak bisa tambah

### **Step 4: Mahasiswa Submit KRS**
- ✅ Klik button "Submit KRS"
- ✅ Status: Draft → Submitted
- ✅ KRS tidak bisa edit lagi

### **Step 5: Admin Approve**
- Admin approve KRS
- Status: Submitted → Approved

### **Step 6: Mahasiswa Cetak KRS**
- ✅ KRS approved → Bisa cetak

---

## 🎯 RULES SISTEM KRS BARU:

### **Mata Kuliah Wajib:**
1. ✅ **WAJIB diambil SEMUA** setiap semester
2. ✅ **TIDAK BISA dihapus** dari KRS
3. ✅ **OTOMATIS masuk** (tidak perlu button tambah)

### **Mata Kuliah Mengulang:**
1. ✅ **Opsional** (boleh ambil, boleh tidak)
2. ✅ Bisa diambil **KAPAN SAJA** (semester 2-14)
3. ✅ **HARUS cek jadwal** (tidak boleh bentrok)
4. ✅ **BISA dihapus** dari KRS (sebelum submit)
5. ✅ **WAJIB lulus semua** pada akhirnya

### **Validasi:**
1. ❌ **TIDAK ADA** batas max SKS
2. ✅ **CEK jadwal bentrok** untuk mata kuliah mengulang
3. ✅ **TIDAK BISA** submit KRS kosong
4. ✅ **TIDAK BISA** edit setelah submit

---

## 🔧 TECHNICAL CHANGES:

### **Controller (`KrsController.php`):**
```php
// REMOVED: Max SKS validation
- getMaxSks()
- Validation: ($currentSks + $mataKuliah->sks) > $maxSks

// ADDED: Jadwal conflict validation
+ checkJadwalConflict($mahasiswaId, $semesterId, $newMataKuliahId)
+ Check hari dan jam
+ Return conflict info or false

// CHANGED: Mata kuliah tidak lulus query
- where('semester_id', $previousSemesterId)
+ No semester filter (ambil dari semua semester)
```

### **Views:**
```blade
// REMOVED: Max SKS display
- <div>Max SKS: {{ $maxSks }}</div>
- Progress bar dengan max limit

// ADDED: Total SKS (informational only)
+ <div>Total SKS: {{ $totalSks }}</div>
+ No progress bar with limit

// CHANGED: Mata kuliah wajib section
- Button "Tambah" untuk setiap MK
+ Read-only list (otomatis masuk)

// ADDED: Validasi jadwal bentrok message
+ Error: "Jadwal bentrok! ..."
```

---

## ⚠️ MIGRATION NOTE:

**TIDAK PERLU migration baru!**

Table `krs` sudah support:
- ✅ `is_mengulang` column
- ✅ `status` column
- ✅ All necessary fields

**Yang berubah hanya LOGIC:**
- Validasi max SKS dihapus
- Validasi jadwal bentrok ditambahkan
- Query mata kuliah tidak lulus diubah

---

## 🧪 TESTING CHECKLIST:

### **Test 1: Auto Mata Kuliah Wajib**
- [ ] Akses KRS
- [ ] List mata kuliah wajib muncul (tanpa button tambah)
- [ ] Submit KRS
- [ ] Semua mata kuliah wajib masuk ke KRS

### **Test 2: Mengulang Tanpa Bentrok**
- [ ] Pilih mata kuliah mengulang
- [ ] Jadwal tidak bentrok
- [ ] Mata kuliah berhasil ditambahkan

### **Test 3: Mengulang Dengan Bentrok**
- [ ] Pilih mata kuliah mengulang
- [ ] Jadwal bentrok dengan mata kuliah semester berjalan
- [ ] Error: "Jadwal bentrok! ..."
- [ ] Mata kuliah TIDAK ditambahkan

### **Test 4: Tidak Ada Batas SKS**
- [ ] Tambah banyak mata kuliah mengulang
- [ ] Total SKS bisa > 24 SKS
- [ ] Tidak ada error max SKS

### **Test 5: Mata Kuliah Lama Bisa Diulang**
- [ ] Semester 5 mahasiswa
- [ ] Ada mata kuliah tidak lulus di semester 1, 2, 3
- [ ] Semua muncul di list mengulang
- [ ] Bisa dipilih kapan saja

---

## 📊 COMPARISON:

| Feature | Before | After |
|---------|--------|-------|
| Max SKS | ✅ Ada (15-24 SKS) | ❌ Tidak ada |
| Mata Kuliah Wajib | Button "Tambah" | Auto list (read-only) |
| Mengulang | Semester sebelumnya | Semua semester (1-14) |
| Validasi Jadwal | ❌ Tidak ada | ✅ Cek bentrok |
| Batas Mengulang | - | Semester 14 |
| Hapus MK Wajib | ❌ Tidak bisa | ❌ Tetap tidak bisa |
| Hapus MK Mengulang | ✅ Bisa | ✅ Tetap bisa |

---

## ✅ COMPLETED:

1. ✅ Tambah menu KRS di sidebar mahasiswa
2. ✅ Update controller: Hapus validasi max SKS
3. ✅ Update controller: Tambah validasi jadwal bentrok
4. ✅ Update controller: Query mata kuliah tidak lulus dari semua semester
5. ⏳ Update views: Hapus max SKS display (next step)

---

**Dokumentasi ini menjelaskan semua perubahan major di sistem KRS.**

Next: Update views untuk reflect logic baru.
