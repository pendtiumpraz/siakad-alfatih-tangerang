# 📝 PANDUAN INPUT NILAI BATCH (KOLEKTIF)
## Untuk Admin STAI AL-FATIH

**Tujuan:** Input nilai historical data untuk mahasiswa angkatan lama (2022, 2023) yang belum punya data nilai semester lalu.

**Benefit:** Mahasiswa bisa mengulang mata kuliah yang tidak lulus di semester sebelumnya.

---

## 🎯 KAPAN MENGGUNAKAN FITUR INI?

### **Use Case 1: Sistem Baru Deploy**
```
Situasi:
├── Sistem baru aktif semester Ganjil 2024/2025
├── Mahasiswa angkatan 2022 sekarang semester 5
├── Data nilai semester 1-4: BELUM ADA ❌
└── Perlu input historical data

Solusi:
└── Gunakan Input Nilai Batch untuk input nilai semester 1-4
```

### **Use Case 2: Mahasiswa Perlu Mengulang**
```
Situasi:
├── Mahasiswa tidak lulus beberapa MK semester lalu
├── Data nilai semester lalu: BELUM ADA ❌
└── KRS mengulang tidak muncul (no data)

Solusi:
└── Input nilai semester lalu → Status "tidak_lulus" → Otomatis muncul di KRS mengulang
```

---

## 🚀 CARA MENGGUNAKAN (STEP-BY-STEP)

### **STEP 1: Akses Menu**

1. Login sebagai **Admin**
2. Klik menu: **"📝 Input Nilai Batch"** (di sidebar, setelah "Approval KRS")
3. Akan muncul form filter

---

### **STEP 2: Pilih Filter**

Form filter memiliki 3 dropdown:

```
┌─────────────────────────────────────────┐
│ 📚 Pilih Program Studi, Angkatan & Sem │
├─────────────────────────────────────────┤
│ Program Studi: [Pilih...]              │
│ Angkatan:      [Pilih...]              │
│ Semester:      [Pilih...]              │
│                                         │
│ [🔍 Tampilkan Mahasiswa & Mata Kuliah] │
└─────────────────────────────────────────┘
```

**Contoh:**
- **Program Studi:** Pendidikan Agama Islam (PAI)
- **Angkatan:** 2022
- **Semester:** Semester 1

**Klik:** 🔍 Tampilkan Mahasiswa & Mata Kuliah

---

### **STEP 3: Lihat Preview**

System akan menampilkan:

```
┌─────────────────────────────────────────┐
│ Summary:                                │
│ ├─ Program Studi: PAI                  │
│ ├─ Angkatan: 2022                      │
│ ├─ Semester: Semester 1                │
│ └─ Total Input: 25 × 9 = 225 nilai    │
└─────────────────────────────────────────┘
```

**Validasi:**
- ✅ Jumlah mahasiswa benar?
- ✅ Jumlah mata kuliah sesuai?
- ✅ Semester number benar?

Jika ada yang salah, klik **"← Kembali"** dan pilih ulang filter.

---

### **STEP 4: Input Nilai di Grid**

Grid berbentuk table dengan:
- **Baris (Rows):** Mahasiswa (25 orang)
- **Kolom (Columns):** Mata Kuliah (9 MK)
- **Cells:** Input nilai angka (0-100)

```
┌───────────┬──────────┬──────────┬─────────┐
│ Mahasiswa │Pancasila │ B.Inggris│ Aqidah  │
├───────────┼──────────┼──────────┼─────────┤
│ 2022010001│ [  95  ] │ [  85  ] │ [  72 ] │
│ Ahmad     │ A (4.00)✓│ A (4.00)✓│ B (3.00)✓│
├───────────┼──────────┼──────────┼─────────┤
│ 2022010002│ [  78  ] │ [  65  ] │ [  45 ] │
│ Budi      │ B (3.00)✓│ C (2.00)✓│ E (0.00)✗│
└───────────┴──────────┴──────────┴─────────┘
```

**Cara Input:**

1. **Klik cell** yang mau diisi
2. **Ketik nilai angka** (0-100)
3. **Tekan Tab** atau **Enter** untuk pindah ke cell berikutnya
4. **Otomatis muncul:**
   - Grade (A, B, C, D, E)
   - Bobot (4.00, 3.00, 2.00, 1.00, 0.00)
   - Status (✓ Lulus / ✗ Tidak Lulus)
   - Warna background:
     - 🟢 **Hijau:** Lulus (Grade A, B, C)
     - 🔴 **Merah / Oranye:** Tidak Lulus (Grade D & E)

**Tips:**
- ✅ Scroll **horizontal** untuk lihat MK lainnya
- ✅ Kosongkan cell jika tidak ada nilai
- ✅ System auto-calculate grade & status (tidak perlu manual)

---

### **STEP 5: Verifikasi Visual**

**Check warna background:**
- ✅ **Hijau:** Mahasiswa lulus MK ini
- ⚠️ **Merah:** Mahasiswa tidak lulus (akan muncul di KRS mengulang)

**Example Visualization:**
```
Ahmad:
├─ Pancasila: 85 [A] ✓ [🟢 Hijau]
├─ B.Inggris: 75 [B] ✓ [🟢 Hijau]
└─ Fiqih: 45 [E] ✗ [🔴 Merah] ← Akan muncul di mengulang!

Budi:
├─ Pancasila: 94 [A] ✓ [🟢 Hijau]
├─ B.Inggris: 72 [B] ✓ [🟢 Hijau]
└─ Semua lulus!
```

---

### **STEP 6: Simpan Data**

**Klik:** 💾 **Simpan Semua Nilai**

**Konfirmasi Popup:**
```
Simpan 225 nilai?

Nilai akan disimpan dan KHS akan di-generate otomatis.

[OK] [Cancel]
```

**Klik:** **OK**

---

### **STEP 7: Verifikasi Hasil**

**Success Message:**
```
✅ Berhasil menyimpan nilai! 
Created: 200, Updated: 15, Skipped: 10
```

**Artinya:**
- **Created 200:** 200 nilai baru disimpan
- **Updated 15:** 15 nilai existing di-update
- **Skipped 10:** 10 cell kosong di-skip

**Otomatis Terjadi:**
1. ✅ **Nilai tersimpan** ke database (tabel `nilais`)
2. ✅ **KHS generated** (IP & IPK dihitung otomatis)
3. ✅ **MK tidak lulus** otomatis muncul di list "Mengulang" mahasiswa

---

## 📊 GRADE REFERENCE

**Patokan Nilai:**

| Nilai Angka | Grade | Bobot | Keterangan | Status | Warna |
|-------------|-------|-------|------------|--------|-------|
| 80 - 100 | A | 4.00 | Sangat Baik | ✓ Lulus | 🟢 Hijau |
| 70 - 79  | B | 3.00 | Baik        | ✓ Lulus | 🟢 Hijau |
| 60 - 69  | C | 2.00 | Cukup       | ✓ Lulus | 🟡 Kuning |
| 50 - 59  | D | 1.00 | Tidak Lulus | ✗ Tidak Lulus | 🟠 Oranye |
| 0 - 49   | E | 0.00 | Tidak Lulus | ✗ Tidak Lulus | 🔴 Merah |

**Bobot Komponen Nilai Akhir:**

| Komponen | Bobot |
|----------|-------|
| Kehadiran | 15% |
| Tugas Individu / Kelompok / Presentasi | 15% |
| UTS | 30% |
| UAS | 40% |

**Catatan Penting:**
- **Hanya 5 grade**: A, B, C, D, E (tidak ada plus/minus)
- **LULUS**: Grade A, B, C
- **TIDAK LULUS**: Grade D, E (mahasiswa wajib mengulang mata kuliah)

---

## 🔄 WORKFLOW LENGKAP

### **Week 1: Input Semester 1**
```
1. Filter: PAI, 2022, Semester 1
2. Input 25 mahasiswa × 9 MK = 225 nilai
3. Save → KHS generated
4. Time: ±15-20 menit
```

### **Week 2: Input Semester 2**
```
1. Filter: PAI, 2022, Semester 2
2. Input 25 mahasiswa × 9 MK = 225 nilai
3. Save → KHS updated (IPK kumulatif)
4. Time: ±15-20 menit
```

### **Week 3: Input Semester 3**
```
(Repeat sama seperti di atas)
```

### **Week 4: Input Semester 4**
```
(Repeat sama seperti di atas)
```

**Result after 4 weeks:**
✅ Complete historical data untuk angkatan 2022 (semester 1-4)

---

## 🎯 TIPS & BEST PRACTICES

### **1. Persiapan Data**

**Sebelum Input:**
- ✅ Siapkan data nilai dalam Excel/Spreadsheet
- ✅ Format: NIM | Nama | MK1 | MK2 | MK3 | ...
- ✅ Validasi: Semua nilai 0-100
- ✅ Check: Tidak ada data kosong yang mandatory

### **2. Input Strategy**

**Prioritas:**
1. **Semester 1 dulu** (foundation courses)
2. **Semester 2** (sequential)
3. **Semester 3 & 4** (jika mahasiswa semester 5+)

**Per Prodi:**
- Input PAI complete (semester 1-4) → baru pindah prodi lain
- Jangan campuraduk prodi (bikin bingung)

### **3. Quality Check**

**After Save:**
1. ✅ **Check KHS**: Login sebagai mahasiswa → lihat KHS
   - IP & IPK correct?
   - Total SKS correct?
2. ✅ **Check Mengulang**: Login mahasiswa → buka KRS
   - MK tidak lulus muncul di list mengulang?
3. ✅ **Test Flow**: Mahasiswa bisa add mengulang ke KRS?

### **4. Error Handling**

**Jika Ada Error:**
- ❌ **"Tidak ada mahasiswa aktif"** → Check filter angkatan correct?
- ❌ **"Kurikulum aktif tidak ditemukan"** → Set kurikulum as active di Master Data
- ❌ **"Tidak ada mata kuliah wajib"** → Check mata kuliah di kurikulum exist?

**Fix:**
1. Back to filter page
2. Double-check filter selection
3. Verify master data (kurikulum, mata kuliah)

---

## 🔍 VERIFIKASI HASIL

### **Check 1: Database**

**Via phpMyAdmin/MySQL:**
```sql
-- Check nilai tersimpan
SELECT * FROM nilais 
WHERE mahasiswa_id = 10 
AND semester_id = 1;

-- Check KHS generated
SELECT * FROM khs 
WHERE mahasiswa_id = 10 
AND semester_id = 1;
```

### **Check 2: UI Mahasiswa**

**Login sebagai Mahasiswa:**
1. Menu: **KHS** → Lihat IP & IPK semester 1
2. Menu: **KRS** → Cek list "Mata Kuliah Mengulang"
   - MK dengan nilai E/D harus muncul
3. Try add mengulang → Submit KRS

### **Check 3: Admin Report**

**Via Admin Panel:**
1. Menu: **Nilai** (jika ada) → List nilai per mahasiswa
2. Menu: **KHS** (jika ada) → List KHS per semester
3. Check consistency: Nilai → KHS → KRS Mengulang

---

## ⚠️ TROUBLESHOOTING

### **Problem 1: Grid Tidak Muncul**

**Symptom:** Setelah klik "Tampilkan", grid kosong

**Possible Causes:**
- ❌ Tidak ada mahasiswa aktif di prodi + angkatan tersebut
- ❌ Kurikulum tidak active
- ❌ Tidak ada mata kuliah wajib untuk semester tersebut

**Solution:**
1. Check: `Mahasiswa::where('program_studi_id', X)->where('angkatan', 2022)->count()`
2. Check: Kurikulum for prodi is active?
3. Check: MataKuliah exist untuk semester ini?

### **Problem 2: Nilai Tidak Tersimpan**

**Symptom:** Klik save tapi nilai tidak masuk database

**Possible Causes:**
- ❌ Validation error (nilai > 100 atau < 0)
- ❌ Database constraint error
- ❌ Transaction rollback karena error

**Solution:**
1. Check browser console untuk JavaScript error
2. Check Laravel log: `storage/logs/laravel.log`
3. Verify data: Semua nilai valid (0-100)?

### **Problem 3: KHS Tidak Ter-generate**

**Symptom:** Nilai saved tapi KHS kosong

**Possible Causes:**
- ❌ Error di `generateKhs()` method
- ❌ Mata kuliah tidak punya SKS
- ❌ Database foreign key constraint

**Solution:**
1. Check log error
2. Manual check: MataKuliah punya SKS?
3. Verify: Semester ID valid?

---

## 📞 SUPPORT

**Jika Ada Masalah:**

1. **Check Documentation:** Baca panduan ini lagi
2. **Check Log:** `storage/logs/laravel.log`
3. **Check Database:** Via phpMyAdmin
4. **Contact Developer:** Report error dengan screenshot + log

**Info Yang Dibutuhkan:**
- Screenshot error
- Filter yang dipilih (prodi, angkatan, semester)
- Log error dari Laravel
- Browser console error (F12)

---

## 📚 RELATED DOCS

- **SISTEM_KRS_DOCUMENTATION.md** - Complete KRS system documentation
- **REQUIREMENT_25_NOVEMBER_2025.md** - Original requirements
- **MASTER_PLAN_INTEGRASI_KRS.md** - Integration plan

---

## ✅ CHECKLIST BEFORE START

**Persiapan:**
- [ ] Data nilai sudah dipersiapkan (Excel/Spreadsheet)
- [ ] Master data complete (kurikulum, mata kuliah, mahasiswa)
- [ ] Jadwal already created (via JadwalPlaceholderSeeder atau manual)
- [ ] Browser modern (Chrome/Firefox/Edge)
- [ ] Koneksi internet stabil

**Let's Go!** 🚀

---

**Last Updated:** 27 November 2025  
**Version:** 1.0  
**Status:** Production Ready ✅
