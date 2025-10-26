# Cara Fix Foto Google Drive Yang Tidak Tampil

## Masalah
Foto dari Google Drive tidak bisa tampil, hanya muncul icon saja.

## Penyebab
File di Google Drive masih **private**, jadi browser tidak bisa load gambarnya.

## Solusi (Jalankan Di Server Production)

### 1. Pull perubahan terbaru
```bash
cd /path/ke/siakad-app
git pull origin main
```

### 2. Jalankan command untuk membuat semua file jadi public
```bash
php artisan gdrive:make-public --model=pendaftar
```

**Output yang benar:**
```
🔧 Making Google Drive files publicly accessible...
Processing SPMB Pendaftar records...
Found X pendaftar(s) with Google Drive files.

✅ Processed Y file(s)
✅ Done!
```

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

Semua upload baru otomatis langsung **public**, jadi foto langsung bisa tampil tanpa harus run command lagi.

## Kalau Masih Tidak Tampil?

Coba manual di Google Drive:
1. Buka Google Drive folder SPMB
2. Klik kanan pada file foto
3. Pilih "Share" / "Bagikan"
4. Pilih "Anyone with the link can view"
5. Copy link dan coba paste di browser
