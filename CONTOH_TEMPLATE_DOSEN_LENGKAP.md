# 📊 Contoh Template Import Dosen (Lengkap dengan Mata Kuliah)

## 📝 Format Template Excel

Template import dosen sekarang **SUDAH TERMASUK** kolom `kode_mk` untuk auto-assign mata kuliah saat import.

### **Kolom Wajib:**

| Kolom          | Deskripsi                           | Contoh                    | Wajib? |
|----------------|-------------------------------------|---------------------------|--------|
| `username`     | Username login (NIDN)               | `0101018901`              | ✅ Ya  |
| `email`        | Email dosen                         | `0101018901@staialfatih.ac.id` | ✅ Ya  |
| `no_telepon`   | Nomor HP (kosongkan dulu)           | (kosong)                  | ❌ Tidak |
| `nidn`         | NIDN resmi                          | `0101018901`              | ✅ Ya  |
| `nama_lengkap` | Nama lengkap tanpa gelar            | `Ahmad Fauzi`             | ✅ Ya  |
| `gelar_depan`  | Gelar akademik depan (opsional)     | `Dr.`                     | ❌ Tidak |
| `gelar_belakang`| Gelar akademik belakang (opsional) | `M.Pd.I`                  | ❌ Tidak |
| `kode_prodi`   | Kode program studi (multiple ok)    | `PAI-S1-L,MPI-S1-L`       | ✅ Ya  |
| `kode_mk`      | Kode mata kuliah (multiple ok)      | `PAI-S1-L-101,PAI-S1-L-102` | ❌ Tidak |

---

## 🎯 Contoh Data Template

### **Contoh 1: Dosen PAI dengan Mata Kuliah Semester 1**

```excel
username: 0101018901
email: 0101018901@staialfatih.ac.id
no_telepon: (kosong)
nidn: 0101018901
nama_lengkap: Ahmad Fauzi
gelar_depan: Dr.
gelar_belakang: M.Pd.I
kode_prodi: PAI-S1-L
kode_mk: PAI-S1-L-101,PAI-S1-L-102,PAI-S1-L-103
```

**Penjelasan:**
- Dosen mengajar di **1 prodi**: PAI S1 Luring
- Mengampu **3 mata kuliah** semester 1:
  - PAI-S1-L-101 (Ulumul Qur'an)
  - PAI-S1-L-102 (Bahasa Arab I)
  - PAI-S1-L-103 (Tahsin Al-Qur'an)

---

### **Contoh 2: Dosen Multiple Prodi dengan Multiple Mata Kuliah**

```excel
username: 1212019512
email: 1212019512@staialfatih.ac.id
no_telepon: (kosong)
nidn: 1212019512
nama_lengkap: Zaid Hakim
gelar_depan: (kosong)
gelar_belakang: S.Pd.I, M.Pd
kode_prodi: PAI-S1-L,MPI-S1-L
kode_mk: PAI-S1-L-401,MPI-S1-L-401
```

**Penjelasan:**
- Dosen mengajar di **2 prodi**: PAI S1 Luring DAN MPI S1 Luring
- Mengampu **2 mata kuliah** semester 4 (masing-masing 1 per prodi):
  - PAI-S1-L-401 (Tafsir Tahlili II)
  - MPI-S1-L-401 (Metodologi Penelitian)

---

### **Contoh 3: Dosen Tanpa Mata Kuliah (Assign Manual Nanti)**

```excel
username: 0909019409
email: 0909019409@staialfatih.ac.id
no_telepon: (kosong)
nidn: 0909019409
nama_lengkap: Aisyah Zahra
gelar_depan: (kosong)
gelar_belakang: S.Pd.I, M.Pd
kode_prodi: PGMI-S1-D
kode_mk: (kosong)
```

**Penjelasan:**
- Dosen mengajar di **1 prodi**: PGMI S1 Daring
- **TIDAK mengampu mata kuliah** saat import
- Mata kuliah akan di-assign manual nanti via Edit User

---

## 📋 Daftar Kode Mata Kuliah yang Tersedia

### **PAI S1 Luring (PAI-S1-L)**

| Kode MK       | Nama Mata Kuliah    | Semester | SKS |
|---------------|---------------------|----------|-----|
| PAI-S1-L-101  | Ulumul Qur'an       | 1        | 3   |
| PAI-S1-L-102  | Bahasa Arab I       | 1        | 3   |
| PAI-S1-L-103  | Tahsin Al-Qur'an    | 1        | 2   |
| PAI-S1-L-104  | Aqidah Islamiyah    | 1        | 2   |
| PAI-S1-L-201  | Tafsir Maudhu'i     | 2        | 3   |
| PAI-S1-L-202  | Bahasa Arab II      | 2        | 3   |
| PAI-S1-L-301  | Tafsir Tahlili I    | 3        | 3   |
| PAI-S1-L-401  | Tafsir Tahlili II   | 4        | 3   |
| PAI-S1-L-501  | Tafsir Tahlili III  | 5        | 3   |

### **MPI S1 Luring (MPI-S1-L)**

| Kode MK       | Nama Mata Kuliah          | Semester | SKS |
|---------------|---------------------------|----------|-----|
| MPI-S1-L-101  | Pengantar Pendidikan      | 1        | 3   |
| MPI-S1-L-102  | Psikologi Pendidikan      | 1        | 3   |
| MPI-S1-L-201  | Manajemen Pendidikan      | 2        | 3   |
| MPI-S1-L-301  | Supervisi Pendidikan      | 3        | 3   |
| MPI-S1-L-401  | Metodologi Penelitian     | 4        | 2   |

### **HES S1 Luring (HES-S1-L)**

| Kode MK       | Nama Mata Kuliah          | Semester | SKS |
|---------------|---------------------------|----------|-----|
| HES-S1-L-101  | Fiqh Muamalah I           | 1        | 3   |
| HES-S1-L-102  | Ekonomi Mikro Syariah     | 1        | 3   |
| HES-S1-L-201  | Fiqh Muamalah II          | 2        | 3   |
| HES-S1-L-301  | Perbankan Syariah         | 3        | 3   |

---

## ⚠️ PENTING: Cek Master Data SEBELUM Import

### **Langkah Wajib:**

1. **Login sebagai Super Admin**
2. **Buka Menu: Master Data → Program Studi**
   - URL: `https://domain-anda.com/admin/program-studi`
   - Catat semua `kode_prodi` yang AKTIF
3. **Buka Menu: Master Data → Mata Kuliah**
   - URL: `https://domain-anda.com/admin/mata-kuliah`
   - Filter berdasarkan Program Studi
   - Catat semua `kode_mk` yang tersedia
4. **Copy-paste kode** dari halaman master data ke template Excel Anda

---

## ✅ Validasi Template Sebelum Upload

Cek checklist ini sebelum import:

- [ ] Username = NIDN (contoh: `0101018901`)
- [ ] Email format: `{NIDN}@staialfatih.ac.id`
- [ ] No telepon **KOSONGKAN** (akan diisi dosen sendiri)
- [ ] NIDN sama dengan username
- [ ] kode_prodi **PERSIS sama** dengan yang ada di master data (CASE SENSITIVE!)
- [ ] kode_mk **PERSIS sama** dengan yang ada di master data
- [ ] Multiple prodi/mata kuliah pisah dengan **koma** (`,`)
- [ ] Jika tidak pasti kode_mk, **KOSONGKAN saja**

---

## 🚀 Cara Import

1. **Download Template** dari sistem: 
   - Menu: `Admin → Users` → Tab "Import Data User"
   - Klik **"Download Template Dosen"**

2. **Isi Template** dengan data dosen Anda
   - Gunakan contoh di atas sebagai referensi
   - CEK kode_prodi dan kode_mk di master data

3. **Upload File Excel**:
   - Menu: `Admin → Users` → Tab "Import Data User"
   - Bagian **"Import Data Dosen"**
   - Pilih file → Klik **"Import Dosen"**

4. **Cek Hasil Import**:
   - Sistem akan menampilkan jumlah berhasil dan gagal
   - Jika ada error, baca pesan error dengan teliti
   - Error umum: kode tidak ditemukan, username/email duplikat

---

## 🛠️ Troubleshooting

### **Error: "Mata Kuliah 'XXX' tidak ditemukan"**
**Penyebab:**
- Kode mata kuliah salah ketik
- Mata kuliah belum ada di database untuk prodi tersebut

**Solusi:**
- Cek ulang ejaan kode_mk
- Cek apakah mata kuliah sudah ada di Master Data
- Jika belum ada, **kosongkan kolom kode_mk** dan assign manual nanti

### **Import Berhasil Tapi Mata Kuliah Tidak Ter-Assign**
**Penyebab:**
- Kode_mk tidak cocok dengan database

**Solusi:**
- Edit dosen via menu **Admin → Users → Edit**
- Pilih mata kuliah manual dari checkbox yang tersedia

---

## 📞 Bantuan

Jika masih ada pertanyaan atau error saat import:

1. Baca **PANDUAN_CEK_MASTER_DATA.md** (dokumentasi lengkap)
2. Screenshot error message
3. Hubungi Administrator Sistem

---

**Dokumen ini dibuat untuk memudahkan import dosen dengan mata kuliah ke SIAKAD STAI AL-FATIH Tangerang.**

✅ **Fitur Baru:** Import dosen sekarang bisa sekaligus assign mata kuliah yang diampu!
