# Fix Upload Timeout for Nginx + PHP-FPM

## Problem
- Upload form auto reload/timeout tanpa error log
- Folder Google Drive berhasil dibuat tapi file tidak ter-upload
- Ini karena **Nginx/PHP-FPM timeout** sebelum upload selesai

## Solution: Increase Timeout Settings

### 1. Nginx Configuration

Edit Nginx config file (biasanya `/etc/nginx/sites-available/default` atau `/etc/nginx/nginx.conf`):

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/siakad-app/public;

    # TAMBAHKAN INI - Increase timeout untuk upload
    client_max_body_size 20M;           # Max file upload size
    client_body_timeout 300s;            # 5 menit
    fastcgi_read_timeout 300s;           # 5 menit
    proxy_read_timeout 300s;             # 5 menit
    send_timeout 300s;                   # 5 menit

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.2-fpm.sock;  # Sesuaikan versi PHP
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;

        # TAMBAHKAN INI - Timeout untuk PHP-FPM
        fastcgi_read_timeout 300s;
        fastcgi_send_timeout 300s;
    }
}
```

### 2. PHP-FPM Configuration

Edit PHP-FPM config (biasanya `/etc/php/8.2/fpm/pool.d/www.conf`):

```ini
; TAMBAHKAN atau UPDATE INI
request_terminate_timeout = 300
```

### 3. PHP.ini Configuration

Edit php.ini (biasanya `/etc/php/8.2/fpm/php.ini`):

```ini
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
post_max_size = 20M
upload_max_filesize = 10M
```

### 4. Restart Services

```bash
# Restart Nginx
sudo systemctl restart nginx

# Restart PHP-FPM (sesuaikan versi)
sudo systemctl restart php8.2-fpm
# atau
sudo systemctl restart php-fpm
```

### 5. Verify Configuration

Test dengan phpinfo:

```bash
# Buat file test.php di public/
echo "<?php phpinfo();" > /path/to/siakad-app/public/test.php

# Akses via browser
# https://your-domain.com/test.php
# Cari: max_execution_time, max_input_time, post_max_size
# Harus 300, 300, 20M

# HAPUS setelah test!
rm /path/to/siakad-app/public/test.php
```

## Alternative: User.ini (jika tidak bisa edit server config)

Buat file `.user.ini` di `public/`:

```ini
max_execution_time = 300
max_input_time = 300
memory_limit = 256M
post_max_size = 20M
upload_max_filesize = 10M
```

**NOTE**: `.user.ini` hanya work jika PHP running as CGI/FastCGI mode.

## Troubleshooting

### Check current PHP settings:
```bash
php -i | grep max_execution_time
php -i | grep max_input_time
php -i | grep post_max_size
```

### Check Nginx error log:
```bash
sudo tail -f /var/log/nginx/error.log
```

### Check PHP-FPM error log:
```bash
sudo tail -f /var/log/php8.2-fpm.log
```

### Test timeout:
```bash
# Create test script that sleeps 60 seconds
echo "<?php sleep(60); echo 'OK';" > public/test-timeout.php

# Access via browser - should see 'OK' after 60 seconds
# If timeout < 60 seconds, need to increase timeout settings
```

## Recommended Settings

For SPMB upload with 7 documents (~10MB total):

| Setting | Recommended | Description |
|---------|-------------|-------------|
| `max_execution_time` | 300s (5 min) | PHP script timeout |
| `max_input_time` | 300s (5 min) | Input parsing timeout |
| `memory_limit` | 256M | Memory for processing |
| `post_max_size` | 20M | Total form data size |
| `upload_max_filesize` | 10M | Per file size limit |
| `client_max_body_size` | 20M | Nginx max body size |
| `fastcgi_read_timeout` | 300s | Nginx → PHP-FPM timeout |

## After Configuration

1. ✅ Restart Nginx
2. ✅ Restart PHP-FPM
3. ✅ Test upload SPMB form
4. ✅ Check logs: `/debug-logs?filter=upload`
5. ✅ Should see "Successfully uploaded" messages

## Still Having Issues?

Check:
1. **Shared hosting?** Contact hosting provider to increase limits
2. **CloudFlare?** Check CloudFlare timeout settings (default 100s)
3. **Load Balancer?** Check LB timeout settings
4. **Firewall?** Some firewalls kill long requests
