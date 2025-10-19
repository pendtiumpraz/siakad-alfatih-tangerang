# Roles & Permissions - SIAKAD STAI AL-FATIH

## 📋 Daftar Isi

1. [Overview](#overview)
2. [Super Admin](#super-admin-)
3. [Operator](#operator-)
4. [Dosen](#dosen-)
5. [Mahasiswa](#mahasiswa-)
6. [Permission Matrix](#permission-matrix)
7. [Route Protection](#route-protection)

---

## Overview

SIAKAD STAI AL-FATIH menggunakan **4 role utama** dengan hak akses berbeda:

| Role | Icon | Warna | Fokus Utama |
|------|------|-------|-------------|
| Super Admin | 🔴 | Red | Manajemen sistem & master data |
| Operator | 🟡 | Yellow | Operasional keuangan & SPMB |
| Dosen | 🟢 | Green | Pembelajaran & penilaian |
| Mahasiswa | 🔵 | Blue | Informasi akademik pribadi |

---

## Super Admin 🔴

**Akses:** FULL ACCESS ke seluruh fitur sistem

### Dashboard

**URL:** `/admin/dashboard`

**Fitur:**
- 📊 Statistik real-time:
  - Total Mahasiswa (semua prodi & angkatan)
  - Total Dosen
  - Total Program Studi
  - Total Mata Kuliah
  - Pendaftar SPMB (pending/accepted/rejected)
  - Pembayaran Pending
- 📈 Chart: Mahasiswa per Program Studi (Pie Chart)
- 📈 Chart: Mahasiswa per Angkatan (Bar Chart)
- 📋 Activity Log: 10 aktivitas terakhir
- 🔔 Notifikasi penting

### 1. User Management

**URL:** `/admin/users`

**Fitur:**
- ✅ View daftar semua user (table with pagination)
- ✅ Filter by role (super_admin, operator, dosen, mahasiswa)
- ✅ Filter by status (active/inactive)
- ✅ Search by name, username, email
- ✅ Create new user (all roles)
  - Form fields: username, name, email, phone, password, role, is_active
  - Role-specific fields:
    - Mahasiswa: NIM, Program Studi, Angkatan
    - Dosen: NIDN, Gelar
    - Operator: Employee ID
- ✅ Edit user (all fields including role change)
- ✅ Delete user (soft delete)
- ✅ Activate/deactivate user (toggle is_active)
- ✅ Reset password user
- ✅ View user detail (profile lengkap)

**Table Columns:**
- Username
- Nama
- Email
- Role (badge dengan warna)
- Status (Active/Inactive badge)
- Last Login
- Actions (Edit, Delete, Reset Password)

### 2. SPMB Management

**URL:** `/admin/spmb`

**Fitur:**

**A. Dashboard SPMB**
- 📊 Statistik:
  - Total Pendaftar
  - Pending Verification (dokumen)
  - Accepted
  - Rejected
  - Pembayaran Pending
  - Daftar Ulang Pending
- 📈 Chart: Pendaftar per Program Studi
- 📈 Chart: Status Pendaftaran

**B. Daftar Pendaftar**
- ✅ View all pendaftar (table with pagination)
- ✅ Filter by:
  - Status (pending, paid, verified, accepted, rejected, revision, completed)
  - Program Studi
  - Tanggal Daftar (range)
  - Jalur Masuk (reguler, transfer, beasiswa)
- ✅ Search by: No Pendaftaran, Nama, Email, NIK
- ✅ Sort by: Tanggal daftar, Nama, Status

**Table Columns:**
- No Pendaftaran
- Nama Lengkap
- Email / No HP
- Program Studi
- Jalur Masuk
- Status (badge)
- Tanggal Daftar
- Actions

**C. Detail Pendaftar**
- 👁️ View detail lengkap (8 sections):
  1. Data Pribadi
  2. Alamat
  3. Kontak
  4. Data Orang Tua
  5. Pendidikan
  6. Program Studi
  7. Dokumen (preview/download)
  8. Riwayat Status
- 📄 Download dokumen (foto, KTP, ijazah, dll)
- 🖨️ Print biodata

**D. Verifikasi Pembayaran**
- ✅ View daftar pembayaran pending
- ✅ Lihat bukti transfer (modal preview)
- ✅ Verifikasi pembayaran (approve/reject)
- ✅ Input catatan verifikasi
- ✅ Auto-update status pendaftaran ke "paid"

**E. Verifikasi Dokumen**
- ✅ Review dokumen yang diupload
- ✅ Preview dokumen (foto, KTP, ijazah, transkrip)
- ✅ Keputusan:
  - **Verify** → Status: verified
  - **Revision** → Status: revision (pendaftar upload ulang)
  - **Reject** → Status: rejected (ditolak)
- ✅ Input catatan verifikasi
- ✅ Email notifikasi otomatis ke pendaftar

**F. Seleksi Pendaftar**
- ✅ View pendaftar status "verified"
- ✅ Penilaian seleksi
- ✅ Accept/Reject pendaftar
- ✅ Input catatan keputusan
- ✅ Email pengumuman otomatis

**G. Verifikasi Daftar Ulang**
- ✅ View daftar daftar ulang pending
- ✅ Verifikasi pembayaran daftar ulang
- ✅ Verifikasi dokumen daftar ulang
- ✅ Approve/reject daftar ulang

**H. NIM Range Management**
- ✅ Konfigurasi format NIM per program studi
- ✅ Set tahun angkatan
- ✅ Set prefix (contoh: 202401 untuk PAI 2024)
- ✅ Set range nomor (start, end)
- ✅ Monitor current number (auto-increment)
- ✅ Activate/deactivate NIM range

**I. Aktivasi Mahasiswa**
- ✅ Generate NIM otomatis
- ✅ One-click aktivasi:
  - Create user account (role: mahasiswa)
  - Create mahasiswa record
  - Set username = NIM
  - Set default password
  - Update status pendaftaran = completed
- ✅ Email kredensial ke mahasiswa baru
- ✅ Print kartu mahasiswa

**J. Export Data**
- 📤 Export to Excel (all/filtered)
- 📤 Export to PDF (all/filtered)
- 📤 Export biodata per pendaftar

**K. SPMB Configuration**
- ⚙️ Set tahun akademik
- ⚙️ Set biaya pendaftaran
- ⚙️ Set biaya daftar ulang
- ⚙️ Set tanggal buka/tutup pendaftaran
- ⚙️ Set tanggal pengumuman
- ⚙️ Set rekening bank
- ⚙️ Toggle buka/tutup pendaftaran

### 3. Pengurus Management

**URL:** `/admin/pengurus`

**Fitur:**

**A. Ketua Program Studi**
- 📊 Dashboard:
  - Total Program Studi
  - Ketua Prodi yang sudah di-assign
  - Prodi tanpa Ketua Prodi
- ✅ View daftar program studi (cards)
- ✅ Assign ketua prodi (select dosen)
- ✅ Remove ketua prodi
- ✅ View mahasiswa per prodi
- ✅ View dosen di prodi tersebut

**Card per Program Studi:**
```
┌────────────────────────────────────┐
│ Pendidikan Agama Islam             │
│ Kode: PAI-01 | Akreditasi: A       │
│                                     │
│ Ketua Prodi: Dr. Ahmad, M.Pd.I     │
│ [Remove Ketua Prodi]                │
│                                     │
│ Mahasiswa: 150 | Dosen: 12          │
└────────────────────────────────────┘
```

**B. Dosen Wali**
- **URL:** `/admin/pengurus/dosen-wali`
- 📊 Dashboard:
  - Total Mahasiswa
  - Mahasiswa dengan Dosen Wali
  - Mahasiswa tanpa Dosen Wali
  - Rata-rata mahasiswa per dosen wali

**Bulk Assignment:**
- ✅ Assign dosen wali untuk semua mahasiswa di 1 prodi sekaligus
- ✅ Form:
  - Select Program Studi
  - Select Dosen Wali
  - Apply to: Semua mahasiswa di prodi tersebut yang belum ada wali
- ✅ Konfirmasi sebelum assign

**Individual Assignment:**
- ✅ View daftar mahasiswa (table)
- ✅ Filter by:
  - Program Studi
  - Angkatan
  - Dosen Wali (assigned/unassigned)
- ✅ Search by: Nama, NIM
- ✅ Assign/change dosen wali per mahasiswa
- ✅ Remove dosen wali
- ✅ Modal untuk pilih dosen

**Table Mahasiswa:**
- NIM
- Nama Mahasiswa
- Program Studi
- Angkatan
- Dosen Wali (nama atau "Belum Ada")
- Actions (Assign, Change, Remove)

### 4. Program Studi Management

**URL:** `/admin/program-studi`

**Fitur:**
- ✅ View daftar program studi (table/cards)
- ✅ Create program studi
  - Kode Prodi (contoh: PAI-01)
  - Nama Prodi
  - Jenjang (S1, S2, S3)
  - Akreditasi (A, B, C, Unggul)
  - Ketua Prodi (optional)
- ✅ Edit program studi
- ✅ Delete program studi (soft delete)
- ✅ View detail prodi:
  - Statistik mahasiswa
  - Daftar mata kuliah
  - Daftar dosen

### 5. Mata Kuliah Management

**URL:** `/admin/mata-kuliah`

**Fitur:**
- ✅ View daftar mata kuliah (table)
- ✅ Filter by:
  - Program Studi
  - Semester
  - Jenis (Wajib/Pilihan)
- ✅ Search by: Kode MK, Nama MK
- ✅ Create mata kuliah
  - Kode MK (contoh: PAI101)
  - Nama MK
  - SKS
  - Semester
  - Jenis (Wajib/Pilihan)
  - Program Studi
  - Deskripsi
- ✅ Edit mata kuliah
- ✅ Delete mata kuliah
- ✅ View detail MK (silabus, RPS)

### 6. Kurikulum Management

**URL:** `/admin/kurikulum`

**Fitur:**
- ✅ View daftar kurikulum per program studi
- ✅ Create kurikulum baru
  - Nama Kurikulum (contoh: Kurikulum 2020)
  - Tahun Berlaku
  - Program Studi
  - Total SKS
- ✅ Edit kurikulum
- ✅ Assign mata kuliah ke kurikulum
- ✅ Set semester per mata kuliah
- ✅ Delete kurikulum

### 7. Dosen Management

**URL:** `/admin/dosen`

**Fitur:**
- ✅ View daftar dosen (table)
- ✅ Filter by: Program Studi, Status (aktif/tidak aktif)
- ✅ Search by: Nama, NIDN
- ✅ Create dosen (via user management + data tambahan)
- ✅ Edit dosen
- ✅ View detail dosen:
  - Biodata lengkap
  - Mata kuliah yang diampu
  - Jadwal mengajar
  - Mahasiswa bimbingan (jika dosen wali)
  - Prodi yang dipimpin (jika ketua prodi)
- ✅ Delete dosen

### 8. Mahasiswa Management

**URL:** `/admin/mahasiswa`

**Fitur:**
- ✅ View daftar mahasiswa (table)
- ✅ Filter by:
  - Program Studi
  - Angkatan
  - Status (aktif, cuti, lulus, DO)
  - Dosen Wali (assigned/unassigned)
- ✅ Search by: Nama, NIM
- ✅ Create mahasiswa manual (jika bukan dari SPMB)
- ✅ Edit mahasiswa
- ✅ View detail mahasiswa:
  - Biodata lengkap
  - KHS semua semester
  - Transkrip nilai
  - Riwayat pembayaran
  - Dosen wali
- ✅ Update status (aktif, cuti, lulus, DO)
- ✅ Delete mahasiswa

### 9. Semester Management

**URL:** `/admin/semester`

**Fitur:**
- ✅ View daftar semester (table)
- ✅ Create semester
  - Nama Semester (contoh: Ganjil 2024/2025)
  - Tahun Akademik (2024/2025)
  - Jenis (Ganjil/Genap)
  - Tanggal Mulai
  - Tanggal Selesai
  - Is Active (toggle)
- ✅ Edit semester
- ✅ Set semester aktif (hanya 1 yang aktif)
- ✅ Delete semester
- ✅ View statistik semester:
  - Total mahasiswa aktif
  - Total jadwal kuliah
  - Total nilai yang sudah diinput

### 10. Jadwal Kuliah Management

**URL:** `/admin/jadwal`

**Fitur:**
- ✅ View jadwal per semester (table/calendar view)
- ✅ Filter by:
  - Semester
  - Program Studi
  - Dosen
  - Hari
  - Ruangan
- ✅ Create jadwal
  - Semester
  - Mata Kuliah
  - Dosen Pengampu
  - Hari
  - Jam Mulai - Jam Selesai
  - Ruangan
  - Kelas (jika ada paralel)
- ✅ Edit jadwal
- ✅ Delete jadwal
- ✅ Cek bentrok jadwal (dosen/ruangan)
- ✅ Export jadwal (PDF/Excel)
- ✅ Print jadwal per prodi/dosen

### 11. Nilai Management

**URL:** `/admin/nilai`

**Fitur:**
- ✅ View all nilai (table)
- ✅ Filter by:
  - Semester
  - Program Studi
  - Mata Kuliah
  - Dosen
  - Mahasiswa
- ✅ Monitor progress input nilai:
  - Mata kuliah yang belum ada nilai
  - Mata kuliah yang sudah selesai input
- ✅ Edit nilai (if needed)
- ✅ Delete nilai
- ✅ Export nilai (Excel/PDF)

### 12. KHS Management

**URL:** `/admin/khs`

**Fitur:**
- ✅ View all KHS (table)
- ✅ Filter by:
  - Semester
  - Program Studi
  - Mahasiswa
- ✅ View detail KHS (nilai per MK)
- ✅ Generate KHS untuk mahasiswa (if not generated by dosen)
- ✅ Delete KHS
- ✅ Export KHS per mahasiswa (PDF)
- ✅ Export KHS per semester per prodi (Excel)

### 13. Pembayaran Management

**URL:** `/admin/pembayaran`

**Fitur:**
- ✅ View all pembayaran (table)
- ✅ Filter by:
  - Status (pending, verified, rejected)
  - Jenis (SPP, Daftar Ulang, Wisuda, dll)
  - Mahasiswa
  - Program Studi
  - Tanggal (range)
- ✅ View detail pembayaran
- ✅ Verifikasi pembayaran (approve/reject)
- ✅ Lihat bukti transfer
- ✅ Input catatan verifikasi
- ✅ Export laporan keuangan (Excel/PDF)
- ✅ Laporan per periode
- ✅ Laporan per program studi

### 14. Pengumuman Management

**URL:** `/admin/pengumuman`

**Fitur:**
- ✅ View all pengumuman (table)
- ✅ Filter by:
  - Target (all, mahasiswa, dosen, operator)
  - Status (published, draft)
  - Tanggal publish
- ✅ Create pengumuman
  - Judul
  - Konten (rich text editor)
  - Target audience (all/mahasiswa/dosen/operator)
  - Lampiran (optional, file upload)
  - Publish Now / Schedule
- ✅ Edit pengumuman
- ✅ Delete pengumuman
- ✅ View statistik:
  - Total views
  - Total readers
  - Unread count
- ✅ Pin/unpin pengumuman penting

### 15. Settings & Configuration

**URL:** `/admin/settings`

**Fitur:**
- ⚙️ General Settings:
  - Nama Institusi
  - Logo
  - Alamat
  - Kontak (Email, Phone, Website)
  - Social Media
- ⚙️ Academic Settings:
  - Tahun Akademik Aktif
  - Semester Aktif
  - Sistem Penilaian (bobot nilai)
  - Batas minimal IP
- ⚙️ SPMB Settings (sudah dijelaskan di atas)
- ⚙️ Email Settings:
  - SMTP Configuration
  - Email Templates
- ⚙️ Notification Settings:
  - Toggle email notifications
  - Toggle in-app notifications

---

## Operator 🟡

**Akses:** TERBATAS pada operasional keuangan & SPMB

### Dashboard

**URL:** `/operator/dashboard`

**Fitur:**
- 📊 Statistik:
  - Pembayaran Pending (hari ini)
  - Pembayaran Verified (bulan ini)
  - Total Pembayaran (bulan ini)
  - Pendaftar SPMB Pending
- 📋 List pembayaran pending terbaru
- 📋 List pendaftar SPMB terbaru
- 🔔 Notifikasi

### 1. SPMB Management

**URL:** `/operator/spmb`

**Fitur:**
- ✅ View daftar pendaftar (table)
- ✅ Filter by status, prodi, tanggal
- ✅ Search by nama, no pendaftaran
- ✅ View detail pendaftar (read-only untuk data pribadi)
- ✅ **Verifikasi Pembayaran Pendaftaran**
  - View pembayaran pending
  - Lihat bukti transfer
  - Approve/reject pembayaran
  - Input catatan
- ✅ **Verifikasi Dokumen** (TERBATAS)
  - Review dokumen
  - Approve dokumen saja (tidak bisa reject/revision)
  - Input catatan
- ✅ **Verifikasi Daftar Ulang**
  - View daftar ulang pending
  - Verifikasi pembayaran daftar ulang
  - Approve/reject
- ❌ TIDAK BISA:
  - Seleksi (accept/reject pendaftar)
  - Aktivasi mahasiswa
  - Manage NIM Range
  - Delete pendaftar

### 2. Pembayaran Management

**URL:** `/operator/pembayaran`

**Fitur:**
- ✅ View all pembayaran (table)
- ✅ Filter by status, jenis, mahasiswa, tanggal
- ✅ View detail pembayaran
- ✅ **Verifikasi Pembayaran**
  - Lihat bukti transfer
  - Approve/reject pembayaran SPP
  - Input catatan verifikasi
- ✅ Export laporan keuangan (periode tertentu)
- ❌ TIDAK BISA:
  - Edit pembayaran
  - Delete pembayaran
  - Manage jenis pembayaran
  - Set biaya SPP

### 3. Pengumuman

**URL:** `/operator/pengumuman`

**Fitur:**
- ✅ View all pengumuman
- ✅ Create pengumuman (untuk mahasiswa/dosen)
- ✅ Edit pengumuman yang dibuat sendiri
- ✅ Delete pengumuman yang dibuat sendiri
- ❌ TIDAK BISA:
  - Edit/delete pengumuman admin
  - Pin pengumuman

### 4. Master Data (READ-ONLY)

**URL:** `/operator/mahasiswa`, `/operator/dosen`, `/operator/program-studi`

**Fitur:**
- 👁️ View daftar mahasiswa (read-only)
- 👁️ View detail mahasiswa (read-only)
- 👁️ View daftar dosen (read-only)
- 👁️ View daftar program studi (read-only)
- 👁️ Search & filter
- ❌ TIDAK BISA:
  - Create/edit/delete mahasiswa
  - Create/edit/delete dosen
  - Create/edit/delete program studi

### 5. Profile

**URL:** `/operator/profile`

**Fitur:**
- ✅ View profile sendiri
- ✅ Edit email & phone
- ✅ Change password
- ❌ Field lain: read-only (locked)

---

## Dosen 🟢

**Akses:** FOKUS pada pembelajaran & penilaian

### Dashboard

**URL:** `/dosen/dashboard`

**Fitur:**
- 📊 Statistik:
  - Total Mata Kuliah yang Diampu (semester aktif)
  - Total Mahasiswa Diajar (semester aktif)
  - Total Nilai yang Sudah Diinput (semester aktif)
  - Total Mahasiswa Bimbingan (jika dosen wali)
- 📅 Jadwal mengajar hari ini
- 📋 Mahasiswa bimbingan (jika dosen wali)
- 🔔 Notifikasi
- 📢 Pengumuman terbaru

### 1. Jadwal Mengajar

**URL:** `/dosen/jadwal`

**Fitur:**
- ✅ View jadwal mengajar (table/calendar view)
- ✅ Filter by semester
- ✅ View jadwal per hari
- ✅ View detail jadwal:
  - Mata Kuliah
  - Hari & Jam
  - Ruangan
  - Daftar mahasiswa yang ikut
- ✅ Export jadwal (PDF)
- 👁️ Read-only (tidak bisa create/edit/delete)

### 2. Input Nilai

**URL:** `/dosen/nilai`

**Fitur:**
- ✅ View mata kuliah yang diampu (semester aktif)
- ✅ Select mata kuliah untuk input nilai
- ✅ View daftar mahasiswa yang ikut mata kuliah
- ✅ **Input Nilai** (table form):
  - NIM
  - Nama Mahasiswa
  - Kehadiran (%)
  - Tugas (0-100)
  - UTS (0-100)
  - UAS (0-100)
  - Nilai Akhir (auto-calculate)
  - Grade (auto-calculate: A, A-, B+, B, B-, C+, C, D, E)
- ✅ Formula nilai (bisa dikonfigurasi):
  ```
  Nilai Akhir = (Kehadiran × 10%) + (Tugas × 20%) + (UTS × 30%) + (UAS × 40%)
  ```
- ✅ Grade mapping:
  ```
  A  : 85-100
  A- : 80-84
  B+ : 75-79
  B  : 70-74
  B- : 65-69
  C+ : 60-64
  C  : 55-59
  D  : 50-54
  E  : 0-49
  ```
- ✅ Save draft (bisa edit lagi)
- ✅ Submit final (locked, tidak bisa edit kecuali minta unlock ke admin)
- ✅ Export nilai per mata kuliah (Excel/PDF)

### 3. Generate KHS

**URL:** `/dosen/khs`

**Fitur:**
- ✅ View semester list
- ✅ View mahasiswa per semester (yang sudah ada nilainya)
- ✅ **Generate KHS per Mahasiswa**
  - Pilih mahasiswa
  - Pilih semester
  - Generate → Create KHS record
  - KHS otomatis hitung:
    - IP (Indeks Prestasi) semester tersebut
    - Total SKS semester tersebut
- ✅ **Generate KHS Bulk** (untuk semua mahasiswa di 1 semester sekaligus)
  - Pilih semester
  - Generate All → Create KHS untuk semua mahasiswa yang sudah ada nilai
- ✅ View KHS yang sudah di-generate
- ✅ Re-generate KHS (jika ada perubahan nilai)
- ✅ Export KHS per mahasiswa (PDF)

**Catatan:**
- KHS hanya bisa di-generate jika mahasiswa sudah ada nilai di semester tersebut
- IPK (Indeks Prestasi Kumulatif) dihitung otomatis dari semua KHS

### 4. Mahasiswa Bimbingan (Jika Dosen Wali)

**URL:** `/dosen/mahasiswa-bimbingan`

**Fitur:**
- 👁️ View daftar mahasiswa bimbingan (table)
- 👁️ View detail mahasiswa:
  - Biodata
  - KHS semua semester
  - Transkrip nilai
  - IPK
  - Total SKS
  - Status akademik
- 👁️ Monitor progress akademik mahasiswa
- 📞 Kontak mahasiswa (email, phone)
- 📝 Catatan bimbingan (create notes)
- ❌ TIDAK BISA:
  - Edit data mahasiswa
  - Input nilai (kecuali untuk mata kuliah yang diampu)

### 5. Pengumuman

**URL:** `/dosen/pengumuman`

**Fitur:**
- ✅ View pengumuman (untuk dosen & all)
- ✅ Create pengumuman (untuk mahasiswa bimbingan atau semua mahasiswa)
- ✅ Edit pengumuman yang dibuat sendiri
- ✅ Delete pengumuman yang dibuat sendiri
- ❌ TIDAK BISA:
  - Edit/delete pengumuman admin

### 6. Master Data (READ-ONLY)

**URL:** `/dosen/mata-kuliah`, `/dosen/mahasiswa`

**Fitur:**
- 👁️ View daftar mata kuliah (read-only)
- 👁️ View detail mata kuliah (silabus, RPS)
- 👁️ View daftar mahasiswa (read-only)
- 👁️ Search & filter
- ❌ TIDAK BISA:
  - Create/edit/delete master data

### 7. Profile

**URL:** `/dosen/profile`

**Fitur:**
- ✅ View profile sendiri
- ✅ Edit email & phone
- ✅ Change password
- ✅ Upload foto profil
- ❌ Field lain: read-only (locked)

---

## Mahasiswa 🔵

**Akses:** FOKUS pada informasi akademik pribadi

### Dashboard

**URL:** `/mahasiswa/dashboard`

**Fitur:**
- 📊 Kartu Mahasiswa (Card with photo):
  - Nama Lengkap
  - NIM
  - Program Studi
  - Angkatan
  - Foto
- 📊 Statistik Akademik:
  - IPK (Indeks Prestasi Kumulatif)
  - Total SKS Lulus
  - Semester Saat Ini
  - Status (Aktif/Cuti/Lulus)
- 👨‍🏫 Dosen Wali (nama + kontak)
- 📅 Jadwal kuliah hari ini
- 📢 Pengumuman terbaru (3 terakhir)
- 🔔 Notifikasi

### 1. Profile & Biodata

**URL:** `/mahasiswa/profile`

**Fitur:**
- 👁️ View biodata lengkap:
  - Data Pribadi (nama, NIK, tempat/tanggal lahir, agama, dll)
  - Alamat
  - Kontak
  - Data Orang Tua
  - Pendidikan (asal sekolah, tahun lulus)
  - Data Akademik (NIM, prodi, angkatan, status, dosen wali)
- ✅ Edit email & phone (editable)
- ✅ Change password
- ✅ Upload foto profil
- ❌ Field lain: read-only (locked)

### 2. Jadwal Kuliah

**URL:** `/mahasiswa/jadwal`

**Fitur:**
- 👁️ View jadwal kuliah semester aktif (table/calendar view)
- 👁️ Filter by hari
- 👁️ View detail jadwal:
  - Mata Kuliah
  - Dosen Pengampu
  - Hari & Jam
  - Ruangan
  - SKS
- 📤 Export jadwal (PDF)
- 📱 Add to calendar (Google Calendar, iCal)

**Table Jadwal:**
- Hari
- Jam
- Mata Kuliah
- Dosen
- Ruangan
- SKS

### 3. KHS (Kartu Hasil Studi)

**URL:** `/mahasiswa/khs`

**Fitur:**
- 👁️ View daftar KHS (per semester)
- 👁️ KHS hanya muncul jika sudah di-generate oleh dosen
- 👁️ View detail KHS per semester:
  - Daftar mata kuliah
  - Nilai (Angka & Grade)
  - SKS per mata kuliah
  - IP (Indeks Prestasi) semester tersebut
  - Total SKS semester
- 📊 Summary Card:
  - IPK (Indeks Prestasi Kumulatif)
  - Total SKS Lulus
  - Total Semester
- 📈 Chart IP per semester (line chart)
- 📤 Export KHS per semester (PDF)
- 📤 Export Transkrip Nilai (PDF) - semua semester

**KHS Card per Semester:**
```
┌────────────────────────────────────┐
│ Semester 1 - Ganjil 2024/2025      │
│ IP: 3.75 | SKS: 20                 │
│                                     │
│ [Lihat Detail] [Download PDF]      │
└────────────────────────────────────┘
```

**KHS Detail:**
| Kode MK | Mata Kuliah | SKS | Nilai | Grade |
|---------|-------------|-----|-------|-------|
| PAI101 | Aqidah Akhlak | 3 | 85 | A |
| PAI102 | Fiqh Ibadah | 3 | 80 | A- |
| ... | ... | ... | ... | ... |

### 4. Nilai

**URL:** `/mahasiswa/nilai`

**Fitur:**
- 👁️ View all nilai (semua semester)
- 👁️ Filter by semester
- 👁️ View detail nilai per mata kuliah:
  - Kehadiran
  - Tugas
  - UTS
  - UAS
  - Nilai Akhir
  - Grade
- 📊 Summary statistik:
  - Total mata kuliah
  - Nilai rata-rata
  - Grade terbanyak
- ❌ TIDAK BISA:
  - Edit nilai
  - Input nilai

### 5. Kurikulum

**URL:** `/mahasiswa/kurikulum`

**Fitur:**
- 👁️ View kurikulum program studi (read-only)
- 👁️ View mata kuliah per semester (1-8)
- 👁️ View detail mata kuliah:
  - Kode MK
  - Nama MK
  - SKS
  - Semester
  - Jenis (Wajib/Pilihan)
  - Deskripsi
- 📊 Total SKS kurikulum
- ✅ Checklist mata kuliah yang sudah diambil (berdasarkan nilai)

**Kurikulum View:**
```
Semester 1 (20 SKS)
├─ PAI101 - Aqidah Akhlak (3 SKS) [Wajib] ✅
├─ PAI102 - Fiqh Ibadah (3 SKS) [Wajib] ✅
└─ ...

Semester 2 (20 SKS)
├─ PAI201 - Tafsir Al-Quran (3 SKS) [Wajib] ⏳
└─ ...
```

### 6. Pembayaran

**URL:** `/mahasiswa/pembayaran`

**Fitur:**
- 👁️ View riwayat pembayaran (table)
- 👁️ View tagihan aktif (belum bayar)
- 👁️ View detail pembayaran:
  - Jenis (SPP, Daftar Ulang, Wisuda, dll)
  - Jumlah
  - Tanggal bayar
  - Bukti transfer
  - Status (pending, verified, rejected)
  - Catatan verifikasi (jika ada)
- ✅ **Upload Bukti Pembayaran**
  - Pilih tagihan
  - Upload bukti transfer (JPG/PNG/PDF)
  - Input tanggal bayar
  - Submit → Status: pending
- ✅ Upload ulang bukti (jika ditolak)
- 📤 Download bukti yang sudah diupload
- 📤 Print bukti pembayaran yang sudah verified

**Tagihan Card:**
```
┌────────────────────────────────────┐
│ SPP Semester Ganjil 2024/2025      │
│ Rp 3.000.000                        │
│                                     │
│ Status: Belum Bayar                 │
│ [Upload Bukti Pembayaran]           │
└────────────────────────────────────┘
```

### 7. Pengumuman

**URL:** `/mahasiswa/notifications` atau `/mahasiswa/pengumuman`

**Fitur:**
- 👁️ View semua pengumuman (for mahasiswa & all)
- 👁️ Filter by tanggal
- 👁️ Search by judul
- 👁️ View detail pengumuman:
  - Judul
  - Konten
  - Lampiran (download)
  - Tanggal publish
  - Publisher
- ✅ Mark as read (otomatis saat buka)
- ✅ Download lampiran
- 🔔 Badge unread count di sidebar

**Pengumuman List:**
```
┌────────────────────────────────────┐
│ 🔴 [UNREAD]                         │
│ Pengumuman Jadwal UAS Semester ... │
│ 19 Oktober 2025 | Admin            │
└────────────────────────────────────┘
```

### 8. Dosen Wali

**URL:** `/mahasiswa/dosen-wali`

**Fitur:**
- 👁️ View profil dosen wali:
  - Nama & Gelar
  - NIDN
  - Email
  - No HP
  - Foto
- 📞 Kontak cepat (email/WhatsApp)
- 📝 View catatan bimbingan (dari dosen wali)

---

## Permission Matrix

Tabel lengkap hak akses per role:

| Fitur | Super Admin | Operator | Dosen | Mahasiswa |
|-------|-------------|----------|-------|-----------|
| **User Management** |
| View Users | ✅ All | ❌ | ❌ | ❌ |
| Create User | ✅ All roles | ❌ | ❌ | ❌ |
| Edit User | ✅ All | ❌ | ❌ | ❌ |
| Delete User | ✅ All | ❌ | ❌ | ❌ |
| Reset Password | ✅ All | ❌ | ❌ | ❌ |
| **SPMB** |
| View Pendaftar | ✅ All | ✅ All | ❌ | ❌ |
| Verifikasi Pembayaran | ✅ | ✅ | ❌ | ❌ |
| Verifikasi Dokumen | ✅ Full | ✅ Approve only | ❌ | ❌ |
| Seleksi (Accept/Reject) | ✅ | ❌ | ❌ | ❌ |
| Verifikasi Daftar Ulang | ✅ | ✅ | ❌ | ❌ |
| Manage NIM Range | ✅ | ❌ | ❌ | ❌ |
| Aktivasi Mahasiswa | ✅ | ❌ | ❌ | ❌ |
| SPMB Config | ✅ | ❌ | ❌ | ❌ |
| **Pengurus** |
| Assign Ketua Prodi | ✅ | ❌ | ❌ | ❌ |
| Assign Dosen Wali | ✅ | ❌ | ❌ | ❌ |
| **Master Data** |
| Manage Program Studi | ✅ | 👁️ Read | 👁️ Read | ❌ |
| Manage Mata Kuliah | ✅ | 👁️ Read | 👁️ Read | 👁️ Own prodi |
| Manage Kurikulum | ✅ | 👁️ Read | 👁️ Read | 👁️ Own prodi |
| Manage Dosen | ✅ | 👁️ Read | 👁️ Read | ❌ |
| Manage Mahasiswa | ✅ | 👁️ Read | 👁️ Read | 👁️ Own profile |
| Manage Semester | ✅ | ❌ | 👁️ Read | 👁️ Active only |
| **Jadwal Kuliah** |
| Create/Edit Jadwal | ✅ | ❌ | ❌ | ❌ |
| View Jadwal | ✅ All | 👁️ All | 👁️ Own | 👁️ Own |
| Export Jadwal | ✅ | ✅ | ✅ | ✅ Own |
| **Nilai & KHS** |
| Input Nilai | ❌ | ❌ | ✅ Own MK | ❌ |
| Edit Nilai | ✅ All | ❌ | ✅ Own (draft) | ❌ |
| View Nilai | ✅ All | ❌ | ✅ Own MK | 👁️ Own |
| Generate KHS | ✅ All | ❌ | ✅ | ❌ |
| View KHS | ✅ All | ❌ | ✅ All | 👁️ Own |
| Export KHS | ✅ | ❌ | ✅ | ✅ Own |
| **Pembayaran** |
| View Pembayaran | ✅ All | ✅ All | ❌ | 👁️ Own |
| Verifikasi Pembayaran | ✅ | ✅ | ❌ | ❌ |
| Upload Bukti | ❌ | ❌ | ❌ | ✅ Own |
| Export Laporan Keuangan | ✅ | ✅ | ❌ | ❌ |
| **Pengumuman** |
| Create Pengumuman | ✅ | ✅ | ✅ | ❌ |
| Edit Pengumuman | ✅ All | ✅ Own | ✅ Own | ❌ |
| Delete Pengumuman | ✅ All | ✅ Own | ✅ Own | ❌ |
| View Pengumuman | ✅ All | ✅ For operator | ✅ For dosen | ✅ For mahasiswa |
| Pin Pengumuman | ✅ | ❌ | ❌ | ❌ |
| **Profile** |
| View Own Profile | ✅ | ✅ | ✅ | ✅ |
| Edit Email/Phone | ✅ | ✅ | ✅ | ✅ |
| Edit Other Fields | ✅ | ❌ Locked | ❌ Locked | ❌ Locked |
| Change Password | ✅ | ✅ | ✅ | ✅ |
| **Settings** |
| System Settings | ✅ | ❌ | ❌ | ❌ |
| Academic Settings | ✅ | ❌ | ❌ | ❌ |
| Email Settings | ✅ | ❌ | ❌ | ❌ |

**Legend:**
- ✅ Full Access
- 👁️ Read Only
- ❌ No Access

---

## Route Protection

### Middleware Implementation

**Routes:**
```php
// web.php

// Public routes
Route::get('/spmb/register', ...); // Public SPMB registration

// Auth required
Route::middleware('auth')->group(function() {

    // Super Admin routes
    Route::middleware('role:super_admin')->prefix('admin')->name('admin.')->group(function() {
        Route::get('/dashboard', ...);
        Route::resource('users', UserController::class);
        Route::resource('program-studi', ProgramStudiController::class);
        Route::resource('mata-kuliah', MataKuliahController::class);
        Route::prefix('spmb')->name('spmb.')->group(function() {
            Route::get('/', ...)->name('index');
            Route::post('/verify-payment/{id}', ...)->name('verify-payment');
            Route::post('/verify-document/{id}', ...)->name('verify-document');
            Route::post('/selection/{id}', ...)->name('selection');
            Route::post('/activate/{id}', ...)->name('activate');
            // ... more
        });
        // ... more
    });

    // Operator routes
    Route::middleware('role:operator')->prefix('operator')->name('operator.')->group(function() {
        Route::get('/dashboard', ...);
        Route::prefix('spmb')->name('spmb.')->group(function() {
            Route::get('/', ...)->name('index');
            Route::post('/verify-payment/{id}', ...)->name('verify-payment');
            Route::post('/verify-document/{id}', ...)->name('verify-document'); // Limited
            // ... more
        });
        Route::prefix('pembayaran')->name('pembayaran.')->group(function() {
            Route::get('/', ...)->name('index');
            Route::post('/verify/{id}', ...)->name('verify');
        });
        // ... more
    });

    // Dosen routes
    Route::middleware('role:dosen')->prefix('dosen')->name('dosen.')->group(function() {
        Route::get('/dashboard', ...);
        Route::get('/jadwal', ...)->name('jadwal.index');
        Route::prefix('nilai')->name('nilai.')->group(function() {
            Route::get('/', ...)->name('index');
            Route::get('/{mataKuliahId}', ...)->name('input');
            Route::post('/store', ...)->name('store');
        });
        Route::prefix('khs')->name('khs.')->group(function() {
            Route::get('/', ...)->name('index');
            Route::post('/generate/{mahasiswaId}', ...)->name('generate');
            Route::post('/generate-bulk', ...)->name('generate-bulk');
        });
        Route::get('/mahasiswa-bimbingan', ...)->name('mahasiswa-bimbingan');
        // ... more
    });

    // Mahasiswa routes
    Route::middleware('role:mahasiswa')->prefix('mahasiswa')->name('mahasiswa.')->group(function() {
        Route::get('/dashboard', ...);
        Route::get('/profile', ...)->name('profile.index');
        Route::put('/profile', ...)->name('profile.update'); // Email/phone only
        Route::get('/jadwal', ...)->name('jadwal.index');
        Route::get('/khs', ...)->name('khs.index');
        Route::get('/khs/{semesterId}', ...)->name('khs.detail');
        Route::get('/khs/{semesterId}/export', ...)->name('khs.export');
        Route::get('/nilai', ...)->name('nilai.index');
        Route::get('/kurikulum', ...)->name('kurikulum.index');
        Route::prefix('pembayaran')->name('pembayaran.')->group(function() {
            Route::get('/', ...)->name('index');
            Route::post('/upload/{id}', ...)->name('upload');
        });
        Route::get('/notifications', ...)->name('notifications.index');
        // ... more
    });
});
```

### Middleware Check

**RoleMiddleware.php:**
```php
public function handle($request, Closure $next, $role)
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    if (auth()->user()->role !== $role) {
        abort(403, 'Unauthorized access');
    }

    return $next($request);
}
```

### Policy-Based Authorization

**Example: PendaftaranPolicy.php**
```php
class PendaftaranPolicy
{
    public function viewAny(User $user)
    {
        return in_array($user->role, ['super_admin', 'operator']);
    }

    public function verifyPayment(User $user)
    {
        return in_array($user->role, ['super_admin', 'operator']);
    }

    public function selection(User $user)
    {
        return $user->role === 'super_admin';
    }

    public function activate(User $user)
    {
        return $user->role === 'super_admin';
    }
}
```

---

**Dibuat dengan ❤️ menggunakan Laravel & Claude Code**
