1. Clone Repository


git clone https://github.com/Dikayuann/FarmGo.git
cd FarmGo

2. Install Dependency
   
composer install

Folder vendor/ tidak disertakan di repository dan akan di-generate otomatis oleh Composer.

3️ Konfigurasi Environment
Salin file environment

cp .env.example .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=farmgo
DB_USERNAME=root
DB_PASSWORD=
Pastikan database sudah dibuat.

4️⃣ Generate Application Key

php artisan key:generate

5️⃣ Migrasi Database
php artisan migrate

Jika menggunakan seeder:
php artisan migrate --seed AdminSeeder
6️⃣ Menjalankan Aplikasi


php artisan serve 
npm run dev
Akses aplikasi:


http://127.0.0.1:8000
