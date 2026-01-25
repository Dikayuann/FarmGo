# FarmGo
<p align="center">
  <img
    src="https://github.com/user-attachments/assets/fb2a80ff-0e52-4143-ae2c-132d1380da70"
    alt="FarmGo"
    width="150"
    height="150"
  />
</p>
Sistem Manajemen Peternakan Berbasis Web

FarmGo adalah aplikasi berbasis web untuk membantu peternak individu
mengelola data peternakan secara digital. Sistem ini mendukung pencatatan
data ternak, vaksinasi, reproduksi, serta pembuatan laporan secara terstruktur
dan efisien.

Project ini dikembangkan sebagai bagian dari tugas akademik pada mata kuliah
Digital Business & Software Engineering.

---

## 🎯 Tujuan Pengembangan


• Mengelola data ternak secara terpusat  
• Mengurangi pencatatan manual yang rawan kesalahan  
• Menyediakan sistem sederhana namun fungsional  
• Menjadi fondasi pengembangan sistem peternakan berbasis web

---


## 📌 Fitur Utama

- 🔐 **Autentikasi Pengguna**
  - Login & Registrasi
- 📊 **Dashboard**
  - Ringkasan data dan aktivitas peternakan
- 🐮 **Manajemen Data Ternak**
  - Tambah, ubah, hapus data ternak
- 💉 **Pencatatan Vaksinasi**
- 🧬 **Pencatatan Reproduksi / Perkawinan**
- 📄 **Ekspor Laporan**
  - PDF / Excel
- 👤 **Manajemen Akun & Role Pengguna**
- ⭐ **Sistem Langganan (Premium)**

---

## 🛠️ Teknologi yang Digunakan

- **Backend**: Laravel 12  
- **Frontend**: Blade, Livewire, Filament  
- **Database**: MySQL  
- **Web Server**: Nginx  
- **Tools**: Git, Composer, Node.js, npm

---

### Prasyarat

Pastikan sistem telah terpasang:

• PHP 8.2 atau lebih baru  
• Composer  
• Node.js dan npm  
• MySQL 
  


## Langkah Instalasi

### 1. Clone Repository
```bash
git clone https://github.com/Dikayuann/FarmGo.git
cd FarmGo
```
### 2. Install Dependency Backend dan Frontend
```bash
composer install
npm install
npm run build
```
### 3. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
### 4. Konfigurasi Environment
```bash
cp .env.example .env
php artisan key:generate
```
Atur koneksi database pada file .env:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmgo (bisa disesuaikan)
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi Database
```bash
php artisan migrate
```

### 6. Menjalankan Aplikasi
```bash
php artisan serve
```
Aplikasi dapat diakses melalui browser:
http://127.0.0.1:8000


    




