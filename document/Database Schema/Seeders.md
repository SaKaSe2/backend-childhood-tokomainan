## 🌱 Seeders

### DatabaseSeeder

File: `database/seeders/DatabaseSeeder.php`

```php
public function run(): void
{
    User::factory()->create([
        'name' => 'Test User',
        'email' => 'test@example.com',
    ]);
}
```

### ProductSeeder

File: `database/seeders/ProductSeeder.php`

```php
public function run(): void
{
    // Product 1: LEGO
    Product::create([
        'name' => 'LEGO City Police Station',
        'description' => 'Set LEGO kantor polisi lengkap dengan kendaraan dan mini figures.',
        'price' => 1250000,
        'stock' => 15,
        'category' => 'lego',
        'image_url' => 'https://example.com/lego-police.jpg',
        'age_range' => '8-12',
        'brand' => 'LEGO',
        'rating' => 4.5,
        'is_featured' => true
    ]);

    // Product 2: Action Figure
    Product::create([
        'name' => 'Gundam RX-78-2 Ver. Ka',
        'description' => 'Model kit Gundam skala 1/100 dengan detail tinggi.',
        'price' => 850000,
        'stock' => 8,
        'category' => 'action_figure',
        'image_url' => 'https://example.com/gundam-rx78.jpg',
        'age_range' => '13+',
        'brand' => 'Bandai',
        'rating' => 4.8,
        'is_featured' => true
    ]);

    // Product 3: Board Game
    Product::create([
        'name' => 'Monopoly Classic',
        'description' => 'Permainan papan klasik untuk keluarga.',
        'price' => 350000,
        'stock' => 25,
        'category' => 'board_game',
        'image_url' => 'https://example.com/monopoly.jpg',
        'age_range' => '8-12',
        'brand' => 'Hasbro',
        'rating' => 4.3,
        'is_featured' => false
    ]);
}
```

### Run Seeders

```bash
# Run specific seeder
php artisan db:seed --class=ProductSeeder

# Run all seeders
php artisan db:seed

# Fresh migration + seed
php artisan migrate:fresh --seed
```

---

## 🔧 Migration Commands

### Create Migration

```bash
# Create new migration
php artisan make:migration create_products_table

# Create migration for existing table
php artisan make:migration add_file_path_to_products_table --table=products
```

### Run Migrations

```bash
# Run all pending migrations
php artisan migrate

# Rollback last batch
php artisan migrate:rollback

# Rollback all migrations
php artisan migrate:reset

# Drop all tables & re-run migrations
php artisan migrate:fresh

# Fresh migration + seed
php artisan migrate:fresh --seed
```

### Migration Status

```bash
# Check migration status
php artisan migrate:status
```

Output:
```
+------+-----------------------------------------------------+-------+
| Ran? | Migration                                           | Batch |
+------+-----------------------------------------------------+-------+
| Yes  | 2014_10_12_000000_create_users_table                | 1     |
| Yes  | 2025_12_03_053251_create_products_table             | 1     |
| Yes  | 2025_12_17_021235_add_file_path_to_products_table   | 2     |
+------+-----------------------------------------------------+-------+
```
