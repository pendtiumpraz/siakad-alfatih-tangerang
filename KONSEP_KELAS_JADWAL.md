# KONSEP FIELD "KELAS" DI SISTEM JADWAL

## 📌 PROBLEM STATEMENT

Jika field "kelas" berisi **angkatan** (misal: PAI-2025-A), maka jadwal **TIDAK** bisa dipakai sepanjang tahun (redundant lagi).

**Contoh SALAH:**
```
❌ Jadwal: PAI-2025-A, Algoritma, Senin 08:00
   → Tahun 2026 harus buat lagi: PAI-2026-A (DUPLICATE!)
   → Tahun 2027 harus buat lagi: PAI-2027-A (DUPLICATE!)
```

---

## ✅ SOLUSI: Field "Kelas" = KELAS PARALEL (A, B, C)

Field **"kelas"** HANYA untuk **kelas paralel sederhana**, bukan angkatan atau prodi.

### Yang PERLU di Field "Kelas":
- ✅ **A**, **B**, **C**, **D**, **E** → Kelas paralel

### Yang TIDAK PERLU di Field "Kelas":
- ❌ **Angkatan** (2024, 2025, 2026)
- ❌ **Prodi** (TI, SI, PAI) → Sudah ada di mata kuliah
- ❌ **Semester** (1, 2, 3) → Sudah ada di mata kuliah
- ❌ **Program khusus** (Reguler, Karyawan) → Tidak perlu
- ❌ **Shift waktu** (Pagi, Sore, Malam) → Tidak perlu

---

## 💡 KONSEP & CONTOH PENGGUNAAN

### **Scenario 1: TI - Algoritma (Semester 1, Ganjil) - Banyak Kelas**

**Setup Jadwal (sekali saja):**
```
┌─────────────────────────────────────────────────────────┐
│ Mata Kuliah: Algoritma (Semester 1, TI)                │
│ Jenis Semester: Ganjil                                  │
├─────────────────────────────────────────────────────────┤
│ Kelas A → Senin 08:00-10:00, R101, Dosen X             │
│ Kelas B → Senin 10:00-12:00, R102, Dosen Y             │
│ Kelas C → Selasa 08:00-10:00, R101, Dosen Z            │
└─────────────────────────────────────────────────────────┘
```

**Berlaku untuk SEMUA angkatan:**
```
Tahun 2024:
- TI 2024 Semester 1 Kelas A → Algoritma, Senin 08:00, Dosen X
- TI 2024 Semester 1 Kelas B → Algoritma, Senin 10:00, Dosen Y
- TI 2024 Semester 1 Kelas C → Algoritma, Selasa 08:00, Dosen Z

Tahun 2025:
- TI 2025 Semester 1 Kelas A → Algoritma, Senin 08:00, Dosen X
- TI 2025 Semester 1 Kelas B → Algoritma, Senin 10:00, Dosen Y
- TI 2025 Semester 1 Kelas C → Algoritma, Selasa 08:00, Dosen Z

Tahun 2026:
- TI 2026 Semester 1 Kelas A → Algoritma, Senin 08:00, Dosen X
- (dst... TIDAK PERLU BUAT JADWAL BARU!)
```

---

### **Scenario 2: PAI - Fiqih (Semester 3, Ganjil) - Tidak Ada Kelas Paralel**

**Setup Jadwal:**
```
┌─────────────────────────────────────────────────────────┐
│ Mata Kuliah: Fiqih (Semester 3, PAI)                   │
│ Jenis Semester: Ganjil                                  │
├─────────────────────────────────────────────────────────┤
│ Kelas A → Rabu 13:00-15:00, R201, Dosen Ahmad          │
└─────────────────────────────────────────────────────────┘
```

**Atau bisa isi "Reguler":**
```
Kelas: Reguler
```

**Berlaku untuk semua angkatan:**
- PAI 2023 Semester 3 → Fiqih, Rabu 13:00
- PAI 2024 Semester 3 → Fiqih, Rabu 13:00
- PAI 2025 Semester 3 → Fiqih, Rabu 13:00

---

### **Scenario 3: SI - Pemrograman Web (Semester 4, Genap) - Banyak Kelas**

**Setup Jadwal:**
```
┌─────────────────────────────────────────────────────────┐
│ Mata Kuliah: Pemrograman Web (Semester 4, SI)          │
│ Jenis Semester: Genap                                   │
├─────────────────────────────────────────────────────────┤
│ Kelas A → Selasa 13:00-15:00, R201, Dosen Ahmad        │
│ Kelas B → Rabu 08:00-10:00, R202, Dosen Budi           │
└─────────────────────────────────────────────────────────┘
```

**Use case:**
- Mahasiswa SI Semester 4 bisa pilih Kelas A atau B
- Berlaku untuk semua angkatan (SI 2021, 2022, 2023, dst)

---

## 🔍 CARA KERJA DI SISTEM KRS

### **1. Mahasiswa Melihat Jadwal**

```php
// Ambil semester aktif mahasiswa
$mahasiswa = Mahasiswa::find($mahasiswaId);
$semesterMahasiswa = 3; // Mahasiswa sedang semester 3
$semesterAktif = Semester::where('is_active', true)->first();
$jenisSemester = $semesterAktif->jenis; // 'ganjil' atau 'genap'

// Ambil jadwal yang relevan
$jadwalsAvailable = Jadwal::where('jenis_semester', $jenisSemester)
    ->whereHas('mataKuliah', function($q) use ($mahasiswa, $semesterMahasiswa) {
        $q->where('semester', $semesterMahasiswa) // Mata kuliah semester 3
          ->whereHas('kurikulum', function($q2) use ($mahasiswa) {
              $q2->where('program_studi_id', $mahasiswa->program_studi_id); // Prodi TI
          });
    })
    ->get();

// Hasil: Tampilkan semua jadwal mata kuliah semester 3 TI di semester ganjil
// Mahasiswa bisa pilih Kelas A, B, atau C
```

### **2. Mahasiswa Pilih Kelas**

```
Mahasiswa: Ali (TI 2024, Semester 3)
Semester Aktif: 2024/2025 Ganjil

Muncul pilihan:
┌────────────────────────────────────────────────────────┐
│ Algoritma (3 SKS) - Semester 3                         │
├────────────────────────────────────────────────────────┤
│ ○ Kelas A - Senin 08:00-10:00, R101, Dosen X          │
│ ○ Kelas B - Senin 10:00-12:00, R102, Dosen Y          │
│ ○ Kelas C - Selasa 08:00-10:00, R101, Dosen Z         │
└────────────────────────────────────────────────────────┘

Ali pilih: Kelas B

Simpan ke KRS:
- mahasiswa_id: Ali
- jadwal_id: [Jadwal Algoritma Kelas B]
- semester_id: 2024/2025 Ganjil
```

### **3. Tahun Depan (2025/2026 Ganjil)**

```
Mahasiswa: Budi (TI 2025, Semester 3)
Semester Aktif: 2025/2026 Ganjil

Muncul pilihan SAMA (dari jadwal yang sama):
┌────────────────────────────────────────────────────────┐
│ Algoritma (3 SKS) - Semester 3                         │
├────────────────────────────────────────────────────────┤
│ ○ Kelas A - Senin 08:00-10:00, R101, Dosen X          │
│ ○ Kelas B - Senin 10:00-12:00, R102, Dosen Y          │
│ ○ Kelas C - Selasa 08:00-10:00, R101, Dosen Z         │
└────────────────────────────────────────────────────────┘

TIDAK PERLU BUAT JADWAL BARU!
```

---

## 📊 ALUR LENGKAP: Dari Jadwal ke KRS

```
┌─────────────────────────────────────────────────────────────┐
│ 1. ADMIN BUAT JADWAL (Sekali saja)                         │
├─────────────────────────────────────────────────────────────┤
│ Mata Kuliah: Algoritma (Semester 1, TI)                    │
│ Jenis Semester: Ganjil                                      │
│ Kelas: A                                                    │
│ Hari: Senin                                                 │
│ Jam: 08:00-10:00                                            │
│ Ruangan: R101                                               │
│ Dosen: Dosen X                                              │
└─────────────────────────────────────────────────────────────┘
                           │
                           │ BERLAKU SELAMANYA
                           │
          ┌────────────────┼────────────────┐
          │                │                │
          ▼                ▼                ▼
┌──────────────────┐ ┌──────────────────┐ ┌──────────────────┐
│ Tahun 2024/2025  │ │ Tahun 2025/2026  │ │ Tahun 2026/2027  │
│ Semester Ganjil  │ │ Semester Ganjil  │ │ Semester Ganjil  │
├──────────────────┤ ├──────────────────┤ ├──────────────────┤
│ TI 2024 Sem 1    │ │ TI 2025 Sem 1    │ │ TI 2026 Sem 1    │
│ - Ali            │ │ - Budi           │ │ - Citra          │
│ - Bambang        │ │ - Dewi           │ │ - Dani           │
│ - Citra          │ │ - Eka            │ │ - Elsa           │
│                  │ │                  │ │                  │
│ Semua ambil:     │ │ Semua ambil:     │ │ Semua ambil:     │
│ Algoritma Kls A  │ │ Algoritma Kls A  │ │ Algoritma Kls A  │
│ Senin 08:00      │ │ Senin 08:00      │ │ Senin 08:00      │
│ (Jadwal SAMA!)   │ │ (Jadwal SAMA!)   │ │ (Jadwal SAMA!)   │
└──────────────────┘ └──────────────────┘ └──────────────────┘
```

---

## ✅ BEST PRACTICES

### **1. Naming Convention untuk Kelas: SIMPLE A/B/C**

**Gunakan:**
- ✅ A, B, C, D, E (simple & clear)
- ✅ A1, A2, B1, B2 (jika kelas sangat banyak)

**Jangan Gunakan:**
- ❌ 2024-A, 2025-A (mengandung tahun/angkatan)
- ❌ TI-A, SI-A (mengandung prodi, sudah ada di mata kuliah)
- ❌ S1-A, S3-A (mengandung semester, sudah ada di mata kuliah)
- ❌ Reguler, Karyawan (tidak perlu)
- ❌ Pagi, Sore (tidak perlu)

### **2. Konsistensi:**
- Gunakan format yang sama di semua jadwal
- Pakai A/B/C untuk semua mata kuliah
- Simple is better!

---

## 🎯 VALIDATION DI CONTROLLER

Tidak ada validation khusus untuk field "kelas", karena:
1. Tidak ada foreign key (free text)
2. Admin diberi kebebasan sesuai kebutuhan kampus
3. Helper text sudah cukup jelas di UI

Tapi bisa tambahkan optional validation jika perlu:

```php
// Optional: Batasi format jika mau strict
'kelas' => 'required|string|max:50|regex:/^[A-Za-z0-9\s\-]+$/',

// Atau bisa tambahkan enum jika fixed:
'kelas' => 'required|in:A,B,C,Reguler,Karyawan',
```

---

## 📝 SUMMARY

| Aspek | Penjelasan |
|-------|------------|
| **Field "kelas"** | Kelas paralel (A, B, C), bukan angkatan |
| **Prodi** | Sudah ada di mata kuliah → kurikulum → program_studi |
| **Semester** | Sudah ada di mata kuliah (1-14) |
| **Angkatan** | Otomatis dari mahasiswa yang daftar KRS |
| **Jenis Semester** | Ganjil/Genap (berlaku selamanya) |
| **Benefit** | Setup sekali, pakai untuk semua angkatan |

**Dengan sistem ini, jadwal benar-benar GENERIC dan bisa dipakai sepanjang tahun!** ✅
