# SPMB Flow - Sistem Penerimaan Mahasiswa Baru

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Alur Lengkap SPMB](#alur-lengkap-spmb)
3. [Status dan Transisi](#status-dan-transisi)
4. [Detail Per Tahap](#detail-per-tahap)
5. [Role Interaction](#role-interaction)
6. [Database Tables](#database-tables)

---

## Overview

SPMB (Seleksi Penerimaan Mahasiswa Baru) adalah sistem untuk mengelola seluruh proses penerimaan mahasiswa baru di STAI AL-FATIH, mulai dari pendaftaran online hingga mahasiswa resmi terdaftar di sistem akademik.

### Tahapan Utama:

1. **Pendaftaran Online** (8 Steps)
2. **Pembayaran Pendaftaran**
3. **Verifikasi Dokumen**
4. **Seleksi & Pengumuman**
5. **Daftar Ulang**
6. **Aktivasi sebagai Mahasiswa**

---

## Alur Lengkap SPMB

```
┌─────────────────────────────────────────────────────────────────┐
│                    CALON MAHASISWA (PUBLIC)                      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │  Akses Website   │
                    │  /spmb/register  │
                    └──────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│               FORMULIR PENDAFTARAN (8 STEPS)                     │
├─────────────────────────────────────────────────────────────────┤
│  Step 1: Data Pribadi (Nama, NIK, Tempat/Tanggal Lahir, dll)   │
│  Step 2: Alamat Lengkap (Alamat, RT/RW, Kota, Provinsi)        │
│  Step 3: Kontak (Email, No HP, No WA)                           │
│  Step 4: Data Orang Tua (Nama Ayah/Ibu, Pekerjaan, No HP)      │
│  Step 5: Pendidikan (Asal Sekolah, Tahun Lulus, Nilai)         │
│  Step 6: Program Studi (Pilihan Prodi & Jalur Masuk)           │
│  Step 7: Upload Dokumen (Foto, KTP/KK, Ijazah, dll)            │
│  Step 8: Review & Submit                                         │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │   Status:        │
                    │   'pending'      │
                    └──────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                   PEMBAYARAN PENDAFTARAN                         │
├─────────────────────────────────────────────────────────────────┤
│  - Calon Mahasiswa mendapat No Pendaftaran                      │
│  - Lihat tagihan biaya pendaftaran (misal: Rp 250.000)         │
│  - Upload bukti pembayaran (transfer bank)                      │
│  - Status pembayaran: 'pending'                                 │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              VERIFIKASI PEMBAYARAN (OPERATOR)                    │
├─────────────────────────────────────────────────────────────────┤
│  - Operator/Admin melihat daftar pembayaran pending             │
│  - Cek bukti transfer yang diupload                             │
│  - Verifikasi pembayaran → Status: 'verified'                   │
│  - Atau tolak jika tidak valid → Status: 'rejected'             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │   Status:        │
                    │   'paid'         │
                    └──────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              VERIFIKASI DOKUMEN (ADMIN/OPERATOR)                 │
├─────────────────────────────────────────────────────────────────┤
│  - Admin/Operator review dokumen yang diupload                  │
│  - Cek kelengkapan: Foto, KTP, Ijazah, dll                      │
│  - Verifikasi dokumen → Status: 'verified'                      │
│  - Atau revisi jika tidak lengkap → Status: 'revision'          │
│  - Atau tolak jika tidak valid → Status: 'rejected'             │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    SELEKSI & PENILAIAN                           │
├─────────────────────────────────────────────────────────────────┤
│  - Admin melakukan penilaian seleksi                            │
│  - Status diubah ke 'accepted' atau 'rejected'                  │
│  - Calon mahasiswa dapat cek status di portal                   │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │   Status:        │
                    │   'accepted'     │
                    └──────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    DAFTAR ULANG (ACCEPTED)                       │
├─────────────────────────────────────────────────────────────────┤
│  - Calon mahasiswa yang diterima akses portal daftar ulang      │
│  - Isi data tambahan (jika perlu)                               │
│  - Upload dokumen daftar ulang                                  │
│  - Bayar biaya daftar ulang (SPP pertama, dll)                  │
│  - Upload bukti pembayaran daftar ulang                         │
│  - Status daftar ulang: 'pending'                               │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│         VERIFIKASI DAFTAR ULANG (ADMIN/OPERATOR)                 │
├─────────────────────────────────────────────────────────────────┤
│  - Admin/Operator verifikasi pembayaran daftar ulang            │
│  - Verifikasi dokumen daftar ulang                              │
│  - Status daftar ulang: 'verified'                              │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│                    GENERATE NIM (ADMIN)                          │
├─────────────────────────────────────────────────────────────────┤
│  - Admin assign NIM berdasarkan NIM Range yang sudah diset      │
│  - Format NIM: YYYYPPSSSS                                       │
│    - YYYY: Tahun masuk (2024)                                   │
│    - PP: Kode Program Studi (01, 02, 03)                        │
│    - SSSS: Nomor urut (0001, 0002, ...)                         │
│  - Contoh: 202401010001                                         │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
┌─────────────────────────────────────────────────────────────────┐
│              AKTIVASI SEBAGAI MAHASISWA (ADMIN)                  │
├─────────────────────────────────────────────────────────────────┤
│  - Admin klik "Aktivasi sebagai Mahasiswa"                      │
│  - Sistem otomatis create:                                      │
│    1. User account (role: mahasiswa, username: NIM)             │
│    2. Record di table mahasiswas (dengan NIM)                   │
│    3. Password default: password (harus diganti saat login)     │
│  - Status pendaftaran: 'completed'                              │
│  - Pendaftar resmi menjadi Mahasiswa aktif                      │
└─────────────────────────────────────────────────────────────────┘
                              │
                              ▼
                    ┌──────────────────┐
                    │   MAHASISWA      │
                    │   AKTIF          │
                    │   (Login Portal) │
                    └──────────────────┘
```

---

## Status dan Transisi

### Status Pendaftaran (`pendaftarans` table)

| Status | Deskripsi | Aksi Selanjutnya |
|--------|-----------|------------------|
| `pending` | Baru mendaftar, belum bayar | Upload bukti bayar |
| `paid` | Sudah bayar, belum verifikasi dokumen | Menunggu verifikasi admin |
| `verified` | Dokumen terverifikasi | Menunggu seleksi |
| `accepted` | Diterima | Daftar ulang |
| `rejected` | Ditolak | - |
| `revision` | Perlu revisi dokumen | Upload ulang dokumen |
| `completed` | Selesai & sudah jadi mahasiswa | - |

### Status Pembayaran Pendaftaran (`pembayaran_pendaftarans` table)

| Status | Deskripsi |
|--------|-----------|
| `pending` | Bukti transfer sudah diupload, menunggu verifikasi |
| `verified` | Pembayaran terverifikasi oleh operator |
| `rejected` | Pembayaran ditolak (bukti tidak valid) |

### Status Daftar Ulang (`daftar_ulangs` table)

| Status | Deskripsi |
|--------|-----------|
| `pending` | Dokumen & pembayaran daftar ulang sudah diupload, menunggu verifikasi |
| `verified` | Daftar ulang terverifikasi, siap dapat NIM |
| `rejected` | Daftar ulang ditolak |

---

## Detail Per Tahap

### 1. Pendaftaran Online (8 Steps)

**URL:** `/spmb/register`

**Step 1: Data Pribadi**
- Nama Lengkap (required)
- NIK (required, 16 digit)
- Jenis Kelamin (required)
- Tempat Lahir (required)
- Tanggal Lahir (required)
- Agama (required)
- Kewarganegaraan (default: Indonesia)

**Step 2: Alamat**
- Alamat Lengkap (required)
- RT/RW
- Kelurahan/Desa
- Kecamatan
- Kabupaten/Kota (required)
- Provinsi (required)
- Kode Pos

**Step 3: Kontak**
- Email (required, unique)
- No HP (required)
- No WhatsApp (required)

**Step 4: Data Orang Tua**
- Nama Ayah (required)
- Pekerjaan Ayah
- No HP Ayah
- Nama Ibu (required)
- Pekerjaan Ibu
- No HP Ibu

**Step 5: Pendidikan**
- Asal Sekolah (required)
- Alamat Sekolah
- Tahun Lulus (required)
- Nilai Rata-rata (required)
- Jurusan

**Step 6: Program Studi**
- Pilihan Program Studi (required)
  - Pendidikan Agama Islam
  - Ekonomi Syariah
  - Hukum Keluarga Islam
- Jalur Masuk (required)
  - Reguler
  - Transfer
  - Beasiswa

**Step 7: Upload Dokumen**
- Foto (required, JPG/PNG, max 2MB)
- KTP/KK (required, PDF/JPG, max 2MB)
- Ijazah (required, PDF/JPG, max 2MB)
- Transkrip Nilai (optional, PDF/JPG, max 2MB)
- Surat Keterangan Lainnya (optional, PDF, max 2MB)

**Step 8: Review & Submit**
- Review semua data
- Checkbox pernyataan: "Saya menyatakan bahwa data yang saya isikan adalah benar"
- Submit → Create record di `pendaftarans` table

**Output:**
- No Pendaftaran: Format SPMB-YYYY-XXXX (contoh: SPMB-2024-0001)
- Status: `pending`
- Email notifikasi ke pendaftar

---

### 2. Pembayaran Pendaftaran

**URL:** `/spmb/check/{no_pendaftaran}` atau login sebagai pendaftar

**Proses:**
1. Pendaftar login dengan No Pendaftaran + tanggal lahir
2. Lihat tagihan biaya pendaftaran (contoh: Rp 250.000)
3. Informasi rekening bank kampus:
   ```
   Bank BNI Syariah
   No Rek: 1234567890
   A/n: STAI AL-FATIH TANGERANG
   ```
4. Transfer sesuai nominal
5. Upload bukti transfer (JPG/PNG/PDF, max 2MB)
6. Submit → Create record di `pembayaran_pendaftarans` table
7. Status pembayaran: `pending`
8. Status pendaftaran masih `pending`

**Database Record:**
```php
pembayaran_pendaftarans:
- pendaftaran_id: (FK to pendaftarans)
- jumlah: 250000
- tanggal_bayar: (tanggal user bayar)
- metode_pembayaran: 'transfer'
- bukti_path: '/storage/bukti_bayar/xxx.jpg'
- status: 'pending'
- verified_at: null
- verified_by: null
```

---

### 3. Verifikasi Pembayaran (Operator/Admin)

**URL:** `/admin/spmb` atau `/operator/spmb`

**Role:** Super Admin, Operator

**Proses:**
1. Admin/Operator akses menu SPMB
2. Tab "Pembayaran Pending"
3. Lihat list pembayaran dengan status `pending`
4. Klik "Lihat Bukti" → Modal popup menampilkan bukti transfer
5. Verifikasi:
   - **Approve:** Klik "Verifikasi" → Status pembayaran: `verified`, Status pendaftaran: `paid`
   - **Reject:** Klik "Tolak" + alasan → Status pembayaran: `rejected`, notifikasi ke pendaftar

**Database Update:**
```php
pembayaran_pendaftarans:
- status: 'verified'
- verified_at: now()
- verified_by: auth()->id()

pendaftarans:
- status: 'paid'
```

---

### 4. Verifikasi Dokumen (Admin/Operator)

**URL:** `/admin/spmb/verify/{id}`

**Role:** Super Admin, Operator

**Proses:**
1. Admin/Operator klik "Verifikasi Dokumen" pada pendaftar dengan status `paid`
2. Review dokumen yang diupload:
   - Foto
   - KTP/KK
   - Ijazah
   - Transkrip Nilai (jika ada)
   - Surat Keterangan Lainnya (jika ada)
3. Cek kelengkapan dan validitas
4. Keputusan:
   - **Verify:** Status `verified` - Dokumen lengkap dan valid
   - **Revision:** Status `revision` + catatan → Pendaftar harus upload ulang
   - **Reject:** Status `rejected` + alasan → Pendaftaran ditolak

**Database Update:**
```php
pendaftarans:
- status: 'verified' / 'revision' / 'rejected'
- verified_at: now()
- verified_by: auth()->id()
- catatan_verifikasi: 'text' (jika revision/reject)
```

---

### 5. Seleksi & Pengumuman

**URL:** `/admin/spmb/selection/{id}`

**Role:** Super Admin

**Proses:**
1. Admin review pendaftar dengan status `verified`
2. Penilaian berdasarkan:
   - Nilai rata-rata
   - Dokumen kelengkapan
   - Kuota program studi
3. Keputusan:
   - **Accepted:** Status `accepted` → Berhak daftar ulang
   - **Rejected:** Status `rejected` → Tidak diterima

**Pengumuman:**
- Pendaftar bisa cek status di `/spmb/check/{no_pendaftaran}`
- Email notifikasi otomatis terkirim
- Jika diterima: Instruksi untuk daftar ulang
- Jika ditolak: Ucapan terima kasih

---

### 6. Daftar Ulang

**URL:** `/spmb/daftar-ulang/{no_pendaftaran}`

**Role:** Pendaftar dengan status `accepted`

**Proses:**
1. Pendaftar yang diterima login
2. Akses halaman daftar ulang
3. Upload dokumen tambahan (jika diperlukan)
4. Lihat tagihan biaya daftar ulang (contoh: Rp 3.000.000 untuk SPP semester 1)
5. Transfer pembayaran
6. Upload bukti pembayaran daftar ulang
7. Submit → Create record di `daftar_ulangs` table
8. Status daftar ulang: `pending`

**Database Record:**
```php
daftar_ulangs:
- pendaftaran_id: (FK to pendaftarans)
- jumlah: 3000000
- tanggal_bayar: (user input)
- bukti_path: '/storage/daftar_ulang/xxx.jpg'
- status: 'pending'
- verified_at: null
- verified_by: null
```

---

### 7. Verifikasi Daftar Ulang (Admin/Operator)

**URL:** `/admin/spmb/verify-daftar-ulang/{id}`

**Role:** Super Admin, Operator

**Proses:**
1. Admin/Operator lihat daftar daftar ulang pending
2. Verifikasi pembayaran dan dokumen
3. Keputusan:
   - **Verify:** Status daftar ulang `verified` → Siap untuk aktivasi
   - **Reject:** Status daftar ulang `rejected` + alasan

**Database Update:**
```php
daftar_ulangs:
- status: 'verified' / 'rejected'
- verified_at: now()
- verified_by: auth()->id()
```

---

### 8. Generate NIM & Aktivasi Mahasiswa

**URL:** `/admin/spmb/activate/{id}`

**Role:** Super Admin

**Proses:**

**A. Generate NIM (Otomatis)**
1. Sistem ambil NIM Range yang aktif untuk program studi terkait
2. Format NIM: `YYYYPPSSSS`
   - `YYYY`: Tahun angkatan (2024)
   - `PP`: Kode program studi (01, 02, 03)
   - `SSSS`: Nomor urut (0001, 0002, ...)
3. Contoh NIM untuk Pendidikan Agama Islam tahun 2024: `202401010001`
4. Auto-increment nomor urut berdasarkan NIM terakhir

**B. Aktivasi Mahasiswa (One-Click)**
1. Admin klik tombol "Aktivasi sebagai Mahasiswa"
2. Sistem otomatis:

   **Create User Account:**
   ```php
   User::create([
       'username' => $nim, // 202401010001
       'name' => $pendaftaran->nama_lengkap,
       'email' => $pendaftaran->email,
       'phone' => $pendaftaran->no_hp,
       'password' => Hash::make('password'), // default password
       'role' => 'mahasiswa',
       'is_active' => true,
   ]);
   ```

   **Create Mahasiswa Record:**
   ```php
   Mahasiswa::create([
       'user_id' => $user->id,
       'nim' => $nim,
       'program_studi_id' => $pendaftaran->program_studi_id,
       'nama_lengkap' => $pendaftaran->nama_lengkap,
       'tempat_lahir' => $pendaftaran->tempat_lahir,
       'tanggal_lahir' => $pendaftaran->tanggal_lahir,
       'jenis_kelamin' => $pendaftaran->jenis_kelamin,
       'agama' => $pendaftaran->agama,
       'alamat' => $pendaftaran->alamat,
       'no_hp' => $pendaftaran->no_hp,
       'email' => $pendaftaran->email,
       'angkatan' => date('Y'),
       'status' => 'aktif',
   ]);
   ```

   **Update Status Pendaftaran:**
   ```php
   $pendaftaran->update([
       'status' => 'completed',
       'nim_assigned' => $nim,
   ]);
   ```

3. Email notifikasi ke mahasiswa:
   ```
   Subject: Selamat! Anda Resmi Menjadi Mahasiswa STAI AL-FATIH

   Nama: [nama]
   NIM: [nim]
   Program Studi: [prodi]
   Username: [nim]
   Password: password (segera ganti setelah login pertama)

   Silakan login di: https://siakad.stai-alfatih.ac.id
   ```

4. Status pendaftaran: `completed`
5. Mahasiswa bisa login dengan NIM dan password default

---

## Role Interaction

### Calon Mahasiswa (Public)
- ✅ Daftar online (8 steps)
- ✅ Login untuk cek status (No Pendaftaran + tanggal lahir)
- ✅ Upload bukti pembayaran pendaftaran
- ✅ Upload ulang dokumen (jika revision)
- ✅ Cek pengumuman (diterima/ditolak)
- ✅ Daftar ulang (jika diterima)
- ✅ Upload bukti pembayaran daftar ulang

### Operator
- ✅ Lihat semua pendaftar
- ✅ Verifikasi pembayaran pendaftaran
- ✅ Verifikasi dokumen (approve/revision/reject)
- ✅ Verifikasi pembayaran daftar ulang
- ❌ Tidak bisa seleksi (accepted/rejected)
- ❌ Tidak bisa aktivasi mahasiswa
- ❌ Tidak bisa manage NIM Range

### Super Admin
- ✅ Semua akses Operator
- ✅ Seleksi pendaftar (accepted/rejected)
- ✅ Manage NIM Range (konfigurasi format NIM)
- ✅ Aktivasi sebagai mahasiswa (generate NIM + create user)
- ✅ Edit/delete pendaftaran (jika diperlukan)
- ✅ Export data pendaftar (Excel/PDF)
- ✅ Dashboard statistik SPMB

---

## Database Tables

### 1. `pendaftarans`

**Menyimpan data pendaftaran calon mahasiswa**

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| no_pendaftaran | string | Unique, format: SPMB-YYYY-XXXX |
| nama_lengkap | string | Nama lengkap pendaftar |
| nik | string | NIK 16 digit |
| jenis_kelamin | enum | L/P |
| tempat_lahir | string | Tempat lahir |
| tanggal_lahir | date | Tanggal lahir |
| agama | string | Agama |
| kewarganegaraan | string | Default: Indonesia |
| alamat | text | Alamat lengkap |
| rt_rw | string | RT/RW |
| kelurahan | string | Kelurahan/Desa |
| kecamatan | string | Kecamatan |
| kota | string | Kabupaten/Kota |
| provinsi | string | Provinsi |
| kode_pos | string | Kode pos |
| email | string | Email (unique) |
| no_hp | string | Nomor HP |
| no_wa | string | Nomor WhatsApp |
| nama_ayah | string | Nama ayah |
| pekerjaan_ayah | string | Pekerjaan ayah |
| no_hp_ayah | string | No HP ayah |
| nama_ibu | string | Nama ibu |
| pekerjaan_ibu | string | Pekerjaan ibu |
| no_hp_ibu | string | No HP ibu |
| asal_sekolah | string | Asal sekolah |
| alamat_sekolah | string | Alamat sekolah |
| tahun_lulus | string | Tahun lulus |
| nilai_rata_rata | decimal | Nilai rata-rata |
| jurusan | string | Jurusan di sekolah |
| program_studi_id | bigint | FK to program_studis |
| jalur_masuk | enum | reguler/transfer/beasiswa |
| foto_path | string | Path foto |
| ktp_kk_path | string | Path KTP/KK |
| ijazah_path | string | Path ijazah |
| transkrip_path | string | Path transkrip (nullable) |
| surat_keterangan_path | string | Path surat keterangan (nullable) |
| status | enum | pending/paid/verified/accepted/rejected/revision/completed |
| verified_at | timestamp | Waktu verifikasi |
| verified_by | bigint | FK to users |
| catatan_verifikasi | text | Catatan dari admin (nullable) |
| nim_assigned | string | NIM yang sudah di-assign (nullable) |
| created_at | timestamp | - |
| updated_at | timestamp | - |
| deleted_at | timestamp | Soft delete |

### 2. `pembayaran_pendaftarans`

**Menyimpan data pembayaran pendaftaran**

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| pendaftaran_id | bigint | FK to pendaftarans |
| jumlah | decimal | Nominal pembayaran |
| tanggal_bayar | date | Tanggal bayar |
| metode_pembayaran | string | transfer/tunai |
| bukti_path | string | Path bukti bayar |
| status | enum | pending/verified/rejected |
| verified_at | timestamp | Waktu verifikasi |
| verified_by | bigint | FK to users |
| catatan | text | Catatan (nullable) |
| created_at | timestamp | - |
| updated_at | timestamp | - |

### 3. `daftar_ulangs`

**Menyimpan data daftar ulang mahasiswa baru yang diterima**

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| pendaftaran_id | bigint | FK to pendaftarans |
| jumlah | decimal | Nominal pembayaran daftar ulang |
| tanggal_bayar | date | Tanggal bayar |
| bukti_path | string | Path bukti bayar |
| dokumen_tambahan_path | string | Path dokumen tambahan (nullable) |
| status | enum | pending/verified/rejected |
| verified_at | timestamp | Waktu verifikasi |
| verified_by | bigint | FK to users |
| catatan | text | Catatan (nullable) |
| created_at | timestamp | - |
| updated_at | timestamp | - |

### 4. `nim_ranges`

**Konfigurasi range NIM per program studi dan tahun**

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| program_studi_id | bigint | FK to program_studis |
| tahun | string | Tahun angkatan (2024) |
| prefix | string | Prefix NIM (202401) |
| start_number | integer | Nomor awal (1) |
| current_number | integer | Nomor terakhir yang sudah digunakan |
| end_number | integer | Nomor akhir (9999) |
| is_active | boolean | Status aktif |
| created_at | timestamp | - |
| updated_at | timestamp | - |

**Contoh Data:**
```
Program Studi: Pendidikan Agama Islam
Tahun: 2024
Prefix: 202401
Start Number: 1
Current Number: 5 (artinya sudah ada 5 mahasiswa)
End Number: 9999
Is Active: true

→ NIM selanjutnya: 202401010006
```

### 5. `spmb_configs`

**Konfigurasi sistem SPMB**

| Column | Type | Description |
|--------|------|-------------|
| id | bigint | PK |
| tahun_akademik | string | 2024/2025 |
| biaya_pendaftaran | decimal | 250000 |
| biaya_daftar_ulang | decimal | 3000000 |
| tanggal_mulai_pendaftaran | date | Tanggal buka pendaftaran |
| tanggal_akhir_pendaftaran | date | Tanggal tutup pendaftaran |
| tanggal_pengumuman | date | Tanggal pengumuman hasil seleksi |
| is_active | boolean | Status pendaftaran buka/tutup |
| rekening_bank | string | Bank BNI Syariah |
| rekening_number | string | 1234567890 |
| rekening_atas_nama | string | STAI AL-FATIH TANGERANG |
| created_at | timestamp | - |
| updated_at | timestamp | - |

---

## Notifikasi & Email

Sistem mengirim email otomatis pada event berikut:

1. **Pendaftaran Berhasil**
   - Subject: "Pendaftaran SPMB Berhasil - [No Pendaftaran]"
   - Isi: No pendaftaran, instruksi pembayaran, rekening bank

2. **Pembayaran Diverifikasi**
   - Subject: "Pembayaran Pendaftaran Terverifikasi"
   - Isi: Status pembayaran diterima, tahap selanjutnya

3. **Dokumen Perlu Revisi**
   - Subject: "Dokumen Pendaftaran Perlu Revisi"
   - Isi: Catatan revisi, link upload ulang

4. **Pengumuman Diterima**
   - Subject: "Selamat! Anda Diterima di STAI AL-FATIH"
   - Isi: Ucapan selamat, instruksi daftar ulang, deadline

5. **Pengumuman Ditolak**
   - Subject: "Pemberitahuan Hasil Seleksi SPMB"
   - Isi: Ucapan terima kasih, motivasi

6. **Daftar Ulang Terverifikasi**
   - Subject: "Daftar Ulang Terverifikasi"
   - Isi: Konfirmasi, menunggu NIM

7. **Aktivasi Mahasiswa**
   - Subject: "Selamat! Anda Resmi Menjadi Mahasiswa STAI AL-FATIH"
   - Isi: NIM, username, password, link login

---

## Best Practices

### Untuk Admin/Operator:
1. Verifikasi pembayaran dalam 1x24 jam
2. Verifikasi dokumen dalam 2x24 jam
3. Komunikasikan alasan jika reject/revision
4. Backup data pendaftar secara berkala
5. Monitor statistik pendaftaran setiap hari

### Untuk Calon Mahasiswa:
1. Isi data dengan lengkap dan benar
2. Upload dokumen sesuai format yang diminta
3. Cek email secara berkala untuk notifikasi
4. Simpan No Pendaftaran dengan baik
5. Hubungi admin jika ada masalah

---

**Dibuat dengan ❤️ menggunakan Laravel & Claude Code**
