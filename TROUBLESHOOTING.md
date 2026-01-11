# Troubleshooting Guide: Not Found Error di Live Server

## Masalah: 404 Not Found di kkg2.kkmpaket.online

### Checklist Pengecekan

#### 1. **Pastikan File .htaccess Sudah Diupload**
- [ ] Upload file `.htaccess` ke root directory server
- [ ] Pastikan nama PERSIS `.htaccess` (bukan `htaccess.txt` atau `.htaccess.bak`)
- [ ] Cek permission: 644 (rw-r--r--)

#### 2. **Pastikan mod_rewrite Aktif**
Buat file `test_rewrite.php`:
```php
<?php
phpinfo();
```

Buka `http://kkg2.kkmpaket.online/test_rewrite.php`
Cari "mod_rewrite" - harus ada dan "Loaded"

Atau test dengan command:
```bash
apache2ctl -M | grep rewrite
```

#### 3. **Cek AllowOverride**
Pastikan di config Apache (`httpd.conf` atau vhost config):
```apache
<Directory "/path/to/kkg2.kkmpaket.online">
    AllowOverride All
    Require all granted
</Directory>
```

#### 4. **Test URL Secara Bertahap**

Test dengan URL langsung (tanpa pretty URL):
- ✅ `http://kkg2.kkmpaket.online/index.php`
- ✅ `http://kkg2.kkmpaket.online/profil.php`
- ✅ `http://kkg2.kkmpaket.online/login.php`

Jika berhasil, coba pretty URL:
- ✅ `http://kkg2.kkmpaket.online/profil`
- ✅ `http://kkg2.kkmpaket.online/login`

#### 5. **Cek Error Log**
```bash
tail -f /var/log/apache2/error.log
```

### Solusi Cepat: Jika mod_rewrite Tidak Bisa Aktif

Sementara gunakan URL dengan `.php`:
- `http://kkg2.kkmpaket.online/profil.php`
- `http://kkg2.kkmpaket.online/admin/login.php`

### File yang Harus Ada di Live Server

Pastikan file-file ini ada:
- ✅ `.htaccess`
- ✅ `index.php`
- ✅ `login.php` (redirect ke admin/login.php)
- ✅ `profil.php`
- ✅ `anggota.php`
- ✅ `register.php`
- ✅ Folder: `admin/`, `assets/`, `config/`, `includes/`

### Kontak Hosting Support

Jika masih error, hubungi support hosting dan tanyakan:
1. Apakah mod_rewrite sudah aktif?
2. Apakah AllowOverride All sudah diset?
3. Minta mereka enable mod_rewrite jika belum

### Update .htaccess untuk Localhost

Jika deploy di subdirectory di localhost (http://localhost/kkgpaket/):
```apache
RewriteBase /kkgpaket/
```

Jika deploy di root domain (http://kkg2.kkmpaket.online/):
```apache
RewriteBase /
```

File `.htaccess` yang sudah diupdate otomatis detect keduanya.
