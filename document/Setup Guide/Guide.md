🔧 Langkah-langkah Setup
--------------------------------------------------------------------------
1. Install JWT Auth Package bash
composer require php-open-source-saver/jwt-auth
--------------------------------------------------------------------------
2. Publish Konfigurasi JWT bash
php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider"
--------------------------------------------------------------------------
3. Generate JWT Secret Key bash
php artisan jwt:secret
Kegunaan: Perintah ini akan menambahkan JWT_SECRET ke file .env Anda.
--------------------------------------------------------------------------
4. Buat Symbolic Link untuk Storage bash
php artisan storage:link
Kegunaan: Ini membuat link dari public/storage ke storage/app/public.
--------------------------------------------------------------------------
5. Buat Migration untuk File Path bash
php artisan make:migration add_file_path_to_products_table --table=products
--------------------------------------------------------------------------
6. Jalankan Migration bash
php artisan migrate
--------------------------------------------------------------------------
7. Jalankan Server bash
php artisan serve
--------------------------------------------------------------------------
