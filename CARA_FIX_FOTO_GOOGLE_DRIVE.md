# Cara Fix Foto Google Drive Yang Tidak Tampil

## Masalah
Foto dari Google Drive tidak bisa tampil, hanya muncul icon saja.

## Penyebab (SUDAH DIPERBAIKI)
~~File di Google Drive masih **private**~~ ❌ (Bukan ini masalahnya)

**Masalah sebenarnya:** CORS Error - Google Drive block request dari domain lain

## Solusi (SUDAH DITERAPKAN)

### 1. Pull perubahan terbaru
```bash
cd /path/ke/siakad-app
git pull origin main
```

### 2. ~~Jalankan command~~ **TIDAK PERLU LAGI!**

Command `gdrive:make-public` **tidak diperlukan** karena masalahnya bukan permission.

**Fix yang diterapkan:**
- Ganti URL format dari `/uc?export=view` ke `/thumbnail?id=X&sz=w400`
- Thumbnail API tidak kena CORS block
- Remove `crossorigin="anonymous"` attribute

### 3. Test hasilnya
1. Buka halaman result SPMB
2. Foto seharusnya sudah tampil (bukan icon lagi)
3. Buka browser console (F12) - harusnya ada log:
   ```
   ✅ Image loaded successfully from: https://drive.google.com/uc?export=view&id=...
   ```

## Perubahan Print Layout

### Di Layar (Screen)
- Semua info tetap ditampilkan lengkap
- Foto tampil di samping nomor pendaftaran
- Text normal size (tidak kecil)

### Saat Print (Ctrl+P)
- Hanya info penting: nama, NIK, TTL, program studi, jalur seleksi
- **Foto TIDAK dicetak** (hemat tinta)
- Text normal 14px (mudah dibaca)
- Cukup 1 halaman

## Upload Baru (Setelah Deploy)

Semua upload (baru maupun lama) sekarang pakai **thumbnail API** yang tidak kena CORS, jadi langsung bisa tampil.

## Kalau Masih Tidak Tampil?

### Cek 1: Pastikan file sudah di-share
File harus "Anyone with the link can view":
1. Buka Google Drive folder SPMB
2. Klik kanan pada file foto
3. Pilih "Share" / "Bagikan"
4. Pilih "Anyone with the link can view"

### Cek 2: Test URL thumbnail langsung
Copy URL ini dan ganti `FILE_ID_NYA`:
```
https://drive.google.com/thumbnail?id=FILE_ID_NYA&sz=w400
```

Paste di browser. Kalau foto muncul = sukses!

### Cek 3: Browser console
Buka halaman result → F12 → Console → lihat error apa yang muncul.
