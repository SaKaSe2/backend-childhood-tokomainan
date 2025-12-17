🔧 Langkah-langkah Setup Terminal
--------------------------------------------------------------------------
1. Install JWT Auth Package bash, tulis: 
(#composer require php-open-source-saver/jwt-auth#)
--------------------------------------------------------------------------
2. Publish Konfigurasi JWT bash, tulis:
(php artisan vendor:publish --provider="PHPOpenSourceSaver\JWTAuth\Providers\LaravelServiceProvider")
--------------------------------------------------------------------------
3. Generate JWT Secret Key bash, tulis:
(php artisan jwt:secret)
Kegunaan: Perintah ini akan menambahkan JWT_SECRET ke file .env Anda.
--------------------------------------------------------------------------
4. Buat Symbolic Link untuk Storage bash, tulis:
(php artisan storage:link)
Kegunaan: Ini membuat link dari public/storage ke storage/app/public.
--------------------------------------------------------------------------
5. Buat Migration untuk File Path bash, tulis:
(php artisan make:migration add_file_path_to_products_table --table=products)
--------------------------------------------------------------------------
6. Jalankan Migration bash, tulis:
(php artisan migrate)
--------------------------------------------------------------------------
7. Jalankan Server bash, tulis:
(php artisan serve)
--------------------------------------------------------------------------
