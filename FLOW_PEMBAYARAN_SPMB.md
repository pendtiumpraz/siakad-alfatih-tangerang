# Flow Pembayaran SPMB

## Alur Lengkap Pendaftaran Mahasiswa Baru

```
┌─────────────────────────────────────────────────────────────────────┐
│                    FLOW SPMB (LENGKAP)                              │
└─────────────────────────────────────────────────────────────────────┘

1. 📝 PENDAFTAR DAFTAR
   ├─ Isi form pendaftaran online
   ├─ Upload 7 dokumen (Foto, Ijazah, Transkrip, KTP, KK, Akta, SKTM)
   ├─ Submit form
   └─ Dapat: Nomor Pendaftaran (REG2025XXXXX)

   Status Pendaftar: "pending"
   Status Pembayaran: BELUM ADA

   ↓

2. 💰 PENDAFTAR BAYAR BIAYA PENDAFTARAN
   ├─ Lihat jumlah biaya di halaman result
   ├─ Transfer ke rekening kampus
   ├─ Upload bukti transfer (FITUR INI BELUM ADA - AKAN DIBUATKAN)
   └─ Atau: Kirim bukti via WhatsApp ke admin

   Status Pendaftar: "pending"
   Status Pembayaran: "pending" (menunggu verifikasi)

   ↓

3. ✅ ADMIN/OPERATOR VERIFIKASI PEMBAYARAN
   ├─ Cek bukti transfer di panel admin
   ├─ Cocokkan nominal dan nama pengirim
   ├─ Klik tombol "Verifikasi Pembayaran"
   └─ Pembayaran di-approve

   Status Pendaftar: "pending"
   Status Pembayaran: "verified" ✅

   ↓

4. ✅ ADMIN/OPERATOR VERIFIKASI DOKUMEN PENDAFTAR
   ├─ Cek kelengkapan 7 dokumen
   ├─ Validasi keaslian dokumen
   ├─ Klik tombol "Verifikasi Pendaftar"
   └─ Pendaftar lolos verifikasi dokumen

   Status Pendaftar: "verified" ✅
   Status Pembayaran: "verified" ✅

   ↓

5. 📚 TES/SELEKSI (Jika Ada)
   ├─ Ujian tulis / wawancara
   ├─ Penilaian oleh panitia
   └─ Hasil tes dicatat

   ↓

6. 📢 PENGUMUMAN HASIL SELEKSI
   ├─ Admin set status pendaftar
   ├─ Pilihan:
   │  ├─ "accepted" → DITERIMA ✅
   │  └─ "rejected" → TIDAK DITERIMA ❌
   └─ Notifikasi dikirim ke pendaftar

   Status Pendaftar: "accepted" / "rejected"

   ↓

7. 🎓 DAFTAR ULANG (Khusus Yang Diterima)
   ├─ Bayar biaya daftar ulang
   ├─ Upload dokumen tambahan (jika ada)
   ├─ Dapatkan NIM
   └─ Resmi jadi mahasiswa

```

## Status Yang Ada Di Sistem

### Status Pendaftar
| Status     | Arti                                  | Warna  |
|------------|---------------------------------------|--------|
| `draft`    | Belum submit final (masih draft)      | Abu    |
| `pending`  | Menunggu verifikasi                   | Kuning |
| `verified` | Dokumen terverifikasi                 | Biru   |
| `accepted` | DITERIMA                              | Hijau  |
| `rejected` | TIDAK DITERIMA                        | Merah  |

### Status Pembayaran
| Status     | Arti                                  | Warna  |
|------------|---------------------------------------|--------|
| `pending`  | Menunggu verifikasi admin             | Kuning |
| `verified` | Pembayaran terverifikasi ✅           | Hijau  |
| `rejected` | Pembayaran ditolak                    | Merah  |

## Halaman Admin/Operator: Detail Pendaftar

### Section "Informasi Pembayaran"

#### Kalau BELUM ada pembayaran:
```
╔══════════════════════════════════════════════════════╗
║  Informasi Pembayaran                                ║
╠══════════════════════════════════════════════════════╣
║  ⚠️  Belum Ada Pembayaran                            ║
║                                                       ║
║  Pendaftar belum melakukan pembayaran biaya          ║
║  pendaftaran sebesar Rp 350.000                      ║
║                                                       ║
║  💡 Instruksikan pendaftar untuk melakukan           ║
║     pembayaran dan upload bukti transfer.            ║
╚══════════════════════════════════════════════════════╝
```

#### Kalau SUDAH ada pembayaran:
```
╔══════════════════════════════════════════════════════════════╗
║  Informasi Pembayaran                                        ║
╠══════════════════════════════════════════════════════════════╣
║  Tanggal     │ Nominal      │ Metode  │ Status   │ Bukti    ║
║──────────────┼──────────────┼─────────┼──────────┼──────────║
║  27/10/2025  │ Rp 350.000   │ Transfer│ Pending  │ [Lihat]  ║
║              │              │         │  🟡      │          ║
╚══════════════════════════════════════════════════════════════╝

[Button: Verifikasi Pembayaran] [Button: Tolak Pembayaran]
```

## Bug Yang Sudah Diperbaiki

### Bug 1: Field Name Mismatch ❌ → ✅
**Masalah:**
- Database field: `bukti_pembayaran`
- View pakai: `bukti_bayar`
- Hasil: Bukti pembayaran GAK MUNCUL

**Fix:**
- Ganti semua `bukti_bayar` jadi `bukti_pembayaran`
- Applied to: admin/spmb/show.blade.php & operator/spmb/show.blade.php

### Bug 2: Section Pembayaran Tidak Selalu Muncul ❌ → ✅
**Masalah:**
- Section "Informasi Pembayaran" hanya muncul kalau ADA pembayaran
- Kalau belum bayar → section HILANG
- Admin bingung: "Kok gak ada info pembayaran?"

**Fix:**
- Section "Informasi Pembayaran" SELALU muncul
- Kalau belum bayar → tampil warning kuning
- Kalau sudah bayar → tampil tabel riwayat

## Yang Belum Ada (Perlu Dibuatkan)

### 1. ❌ Fitur Upload Bukti Pembayaran Oleh Pendaftar
**Saat ini:**
- Pendaftar kirim bukti via WhatsApp
- Admin manual upload di panel

**Yang dibutuhkan:**
- Di halaman result (setelah daftar)
- Ada form upload bukti pembayaran
- Upload langsung ke Google Drive
- Auto create record PembayaranPendaftaran

### 2. ❌ Tombol Verifikasi/Tolak Pembayaran Di Detail Pendaftar
**Yang dibutuhkan:**
- Button "Verifikasi Pembayaran" → set status jadi `verified`
- Button "Tolak Pembayaran" → set status jadi `rejected`, + keterangan
- Modal konfirmasi sebelum action

### 3. ❌ Notifikasi Email Otomatis
**Yang dibutuhkan:**
- Email saat pembayaran diverifikasi
- Email saat pendaftar diterima/ditolak
- Template email yang bagus

## FAQ

### Q: Pendaftar wajib bayar dulu baru bisa daftar?
**A:** Tidak. Flow-nya:
1. Daftar dulu (gratis)
2. Dapat nomor pendaftaran
3. Baru bayar

### Q: Kalau belum bayar, bisa ikut seleksi?
**A:** Tergantung kebijakan kampus. Umumnya:
- Wajib bayar + dokumen terverifikasi → baru bisa ikut seleksi

### Q: Admin bisa input pembayaran manual tanpa bukti?
**A:** Bisa, tapi TIDAK RECOMMENDED. Seharusnya ada bukti.

### Q: Bisa partial payment (bayar sebagian dulu)?
**A:** Sistem belum support. Harus bayar full di awal.

### Q: Refund kalau ditolak?
**A:** Harus diatur manual sama bagian keuangan. Sistem belum auto refund.

## Files Yang Sudah Diubah

✅ `resources/views/admin/spmb/show.blade.php`
- Fix field `bukti_bayar` → `bukti_pembayaran`
- Tampilkan section pembayaran meski belum ada
- UI lebih bagus untuk tombol "Lihat Bukti"

✅ `resources/views/operator/spmb/show.blade.php`
- Same changes as admin

## Next Steps (Rekomendasi)

1. **Buat fitur upload bukti pembayaran di halaman result**
2. **Tambah button verifikasi pembayaran di detail pendaftar**
3. **Email notifikasi otomatis**
4. **Dashboard analytics: berapa yang sudah bayar, belum bayar, dll**

---

🎉 **Dokumentasi ini dibuat untuk memperjelas flow SPMB!**
