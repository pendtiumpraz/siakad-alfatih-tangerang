# 📥 Cara Import Mahasiswa & Dosen

Panduan lengkap untuk mengimport data mahasiswa dan dosen menggunakan file Excel.

---

## 🎯 Fitur Import

### ✅ **Import Mahasiswa**
- Format: Excel (.xlsx atau .xls)
- Password default: `mahasiswa_staialfatih`
- Semester otomatis dihitung dari angkatan
- Support status: aktif, cuti, lulus, dropout

### ✅ **Import Dosen**
- Format: Excel (.xlsx atau .xls)
- Password default: `dosen_staialfatih`
- Support multiple program studi
- Mata kuliah yang diampu dikosongi (assign manual nanti)

---

## 📋 Langkah-langkah Import

### **1. Akses Halaman User Management**
1. Login sebagai **Super Admin**
2. Buka menu **Admin → Manajemen User**
3. Klik tombol **"Buka"** pada section **"Import Data User"**

### **2. Download Template Excel**

#### **Untuk Mahasiswa:**
1. Klik tombol **"Download Template"** di bagian Import Mahasiswa
2. File `template_import_mahasiswa.xlsx` akan terdownload
3. Template sudah berisi contoh data

#### **Untuk Dosen:**
1. Klik tombol **"Download Template"** di bagian Import Dosen
2. File `template_import_dosen.xlsx` akan terdownload
3. Template sudah berisi contoh data

### **3. Isi Data di Excel**

#### **Format Mahasiswa:**
```
| username    | email                  | no_telepon   | nim     | nama_lengkap | kode_prodi | angkatan | tempat_lahir | tanggal_lahir | jenis_kelamin | alamat            | status | tanggal_lulus | tanggal_dropout |
|-------------|------------------------|--------------|---------|--------------|------------|----------|--------------|---------------|---------------|-------------------|--------|---------------|-----------------|
| john_doe    | john@example.com       | 081234567890 | 2301001 | John Doe     | PAI        | 2023     | Jakarta      | 2000-01-15    | L             | Jl. Contoh No. 1  | aktif  |               |                 |
| jane_smith  | jane@example.com       | 081298765432 | 2301002 | Jane Smith   | PGMI       | 2023     | Bandung      | 2001-05-20    | P             | Jl. Contoh No. 2  | aktif  |               |                 |
```

**Keterangan Field:**
- `username`: Username untuk login (unik, wajib)
- `email`: Email mahasiswa (unik, wajib)
- `no_telepon`: Nomor HP (opsional)
- `nim`: NIM mahasiswa (unik, wajib)
- `nama_lengkap`: Nama lengkap mahasiswa (wajib)
- `kode_prodi`: Kode program studi, contoh: PAI, PGMI (wajib)
- `angkatan`: Tahun angkatan, contoh: 2023 (wajib)
- `tempat_lahir`: Tempat lahir (opsional)
- `tanggal_lahir`: Format: YYYY-MM-DD (opsional)
- `jenis_kelamin`: L atau P (wajib)
- `alamat`: Alamat lengkap (opsional)
- `status`: aktif, cuti, lulus, atau dropout (default: aktif)
- `tanggal_lulus`: Jika status = lulus, format: YYYY-MM-DD
- `tanggal_dropout`: Jika status = dropout, format: YYYY-MM-DD

#### **Format Dosen:**
```
| username      | email                        | no_telepon   | nidn       | nama_lengkap    | gelar_depan | gelar_belakang | kode_prodi |
|---------------|------------------------------|--------------|------------|-----------------|-------------|----------------|------------|
| ahmad_fauzi   | ahmad.fauzi@staialfatih.ac.id| 081234567890 | 1234567890 | Ahmad Fauzi     | Dr.         | M.Pd.I         | PAI,PGMI   |
| siti_nur      | siti.nur@staialfatih.ac.id   | 081298765432 | 0987654321 | Siti Nurhaliza  |             | S.Pd.I, M.Pd   | PAI        |
```

**Keterangan Field:**
- `username`: Username untuk login (unik, wajib)
- `email`: Email dosen (unik, wajib)
- `no_telepon`: Nomor HP (opsional)
- `nidn`: NIDN dosen (unik, wajib)
- `nama_lengkap`: Nama lengkap tanpa gelar (wajib)
- `gelar_depan`: Contoh: Dr., Prof. (opsional)
- `gelar_belakang`: Contoh: M.Pd.I, S.Pd (opsional)
- `kode_prodi`: Kode program studi, pisah dengan **koma** untuk multiple. Contoh: `PAI,PGMI` (wajib)

### **4. Upload File Excel**
1. Setelah file Excel diisi, kembali ke halaman **Manajemen User**
2. Pilih file Excel dengan klik **"Pilih File Excel"**
3. Klik tombol **"Import Mahasiswa"** atau **"Import Dosen"**
4. Tunggu proses import selesai

### **5. Cek Hasil Import**
- ✅ **Sukses**: Akan muncul notifikasi hijau dengan jumlah data berhasil diimport
- ⚠️ **Error**: Akan muncul notifikasi kuning dengan daftar baris yang error
- ❌ **Gagal**: Akan muncul notifikasi merah jika tidak ada data yang berhasil

---

## 📝 Contoh Kasus

### **Kasus 1: Import Mahasiswa Semester 5**
Mahasiswa existing yang sudah semester 5 di tahun 2025:
```
username: mhs2022001
email: mhs2022001@staialfatih.ac.id
nim: 2022001
nama_lengkap: Ahmad Zaki
kode_prodi: PAI
angkatan: 2022  ← Penting! Angkatan 2022 = Semester 5 di 2025
jenis_kelamin: L
status: aktif
```

### **Kasus 2: Import Dosen dengan Multiple Prodi**
Dosen yang mengajar di 2 program studi:
```
username: dosen_ahmad
email: ahmad@staialfatih.ac.id
nidn: 1234567890
nama_lengkap: Ahmad Fauzi
gelar_depan: Dr.
gelar_belakang: M.Pd.I
kode_prodi: PAI,PGMI  ← Pisah dengan koma, tanpa spasi
```

### **Kasus 3: Import Mahasiswa Lulus**
Mahasiswa yang sudah lulus:
```
username: alumni2020
email: alumni2020@staialfatih.ac.id
nim: 2020001
nama_lengkap: Siti Aisyah
kode_prodi: PAI
angkatan: 2020
jenis_kelamin: P
status: lulus
tanggal_lulus: 2024-08-15  ← Wajib diisi jika status = lulus
```

---

## ⚠️ Validasi & Error Handling

### **Data yang Akan Dilewati (Skip):**
- Baris kosong (nim atau nama_lengkap kosong)
- NIM sudah terdaftar (duplicate)
- Username sudah digunakan
- Email sudah digunakan
- NIDN sudah terdaftar (untuk dosen)
- Program studi tidak ditemukan

### **Error Messages:**
```
❌ "Baris 3: NIM 2301001 sudah terdaftar"
❌ "Baris 5: Program Studi 'XYZ' tidak ditemukan untuk NIM 2301002"
❌ "Baris 7: Username 'john_doe' sudah digunakan"
❌ "Baris 10: Email 'test@example.com' sudah digunakan"
```

---

## 🔐 Password Default

### **Mahasiswa:**
```
Password: mahasiswa_staialfatih
```

### **Dosen:**
```
Password: dosen_staialfatih
```

**Note:** User dapat mengganti password setelah login pertama kali.

---

## 💡 Tips & Best Practices

### ✅ **DO:**
1. **Download template** terlebih dahulu untuk melihat format yang benar
2. **Cek duplikasi** NIM, username, email sebelum import
3. **Gunakan kode_prodi yang benar** (PAI, PGMI, dll)
4. **Format tanggal** harus YYYY-MM-DD atau biarkan kosong
5. **Backup database** sebelum import data besar
6. **Import bertahap** jika data sangat banyak (50-100 baris per file)

### ❌ **DON'T:**
1. **Jangan edit nama kolom** di template
2. **Jangan gunakan spasi** di kode_prodi multiple (gunakan koma: PAI,PGMI)
3. **Jangan import file corrupt** atau rusak
4. **Jangan skip field wajib** (username, email, nim/nidn, nama_lengkap, kode_prodi, angkatan/jenis_kelamin)

---

## 🔍 Troubleshooting

### **Problem: "Program Studi tidak ditemukan"**
**Solution:** Pastikan kode_prodi sesuai dengan yang ada di database. Cek di menu **Admin → Program Studi** untuk melihat kode_prodi yang tersedia.

### **Problem: "NIM/Username/Email sudah terdaftar"**
**Solution:** Data sudah ada di database. Gunakan fitur **Edit User** untuk update data, atau gunakan NIM/username/email yang berbeda.

### **Problem: "File tidak valid"**
**Solution:** Pastikan file berformat .xlsx atau .xls, dan tidak corrupt. Download ulang template dan copy-paste data.

### **Problem: "Semester tidak sesuai"**
**Solution:** Semester dihitung otomatis dari angkatan. Untuk mahasiswa semester 5 di 2025, gunakan angkatan 2022.

---

## 📊 Contoh Import Lengkap

### **Template Mahasiswa - 50 Mahasiswa Semester 5:**
```excel
username     | email                    | nim     | nama_lengkap        | kode_prodi | angkatan | jenis_kelamin | status
-------------|--------------------------|---------|---------------------|------------|----------|---------------|-------
mhs2022001   | mhs2022001@staialfatih   | 2022001 | Ahmad Zaki          | PAI        | 2022     | L             | aktif
mhs2022002   | mhs2022002@staialfatih   | 2022002 | Siti Aisyah         | PAI        | 2022     | P             | aktif
mhs2022003   | mhs2022003@staialfatih   | 2022003 | Muhammad Iqbal      | PGMI       | 2022     | L             | aktif
... (47 baris lagi)
```

### **Template Dosen - 20 Dosen:**
```excel
username     | email                      | nidn       | nama_lengkap     | gelar_depan | gelar_belakang | kode_prodi
-------------|----------------------------|------------|------------------|-------------|----------------|------------
dosen001     | dosen001@staialfatih       | 1234567890 | Ahmad Fauzi      | Dr.         | M.Pd.I         | PAI,PGMI
dosen002     | dosen002@staialfatih       | 0987654321 | Siti Nurhaliza   |             | S.Pd.I, M.Pd   | PAI
dosen003     | dosen003@staialfatih       | 1122334455 | Muhammad Yusuf   | Prof. Dr.   | M.A            | PGMI
... (17 baris lagi)
```

---

## 📞 Support

Jika mengalami kendala, hubungi:
- **Developer**: Factory AI Support
- **Documentation**: `/admin/docs`

---

**Last Updated**: November 25, 2025  
**Version**: 1.0.0
