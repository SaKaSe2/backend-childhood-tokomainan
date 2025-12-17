## 🎮 Table: products

### Schema

| Column | Type | Constraint | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | Product ID |
| name | VARCHAR(255) | NOT NULL | Nama produk |
| description | TEXT | NOT NULL | Deskripsi produk |
| price | DECIMAL(10,2) | NOT NULL | Harga (max 99,999,999.99) |
| stock | INT | NOT NULL, DEFAULT 0 | Jumlah stok |
| category | ENUM | NOT NULL | Kategori produk |
| image_url | VARCHAR(255) | NULLABLE | URL gambar eksternal |
| age_range | ENUM | NOT NULL, DEFAULT '4-7' | Rentang usia |
| brand | VARCHAR(255) | NULLABLE | Brand/merek |
| rating | DECIMAL(2,1) | DEFAULT 0.0 | Rating (0.0-5.0) |
| is_featured | BOOLEAN | DEFAULT false | Produk unggulan |
| file_path | VARCHAR(255) | NULLABLE | Path file upload |
| created_at | TIMESTAMP | NULLABLE | Tanggal dibuat |
| updated_at | TIMESTAMP | NULLABLE | Tanggal diupdate |
| deleted_at | TIMESTAMP | NULLABLE | Soft delete timestamp |

### ENUM Values

#### category
```php
[
    'action_figure',
    'lego',
    'puzzle',
    'board_game',
    'educational',
    'collector'
]
```

#### age_range
```php
[
    '0-3',
    '4-7',
    '8-12',
    '13+'
]
```

### Indexes

```sql
PRIMARY KEY (id)
INDEX (category)
INDEX (is_featured)
INDEX (deleted_at)  -- Untuk soft delete
```

### Migration File

```php
Schema::create('products', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->text('description');
    $table->decimal('price', 10, 2);
    $table->integer('stock')->default(0);
    $table->enum('category', [
        'action_figure',
        'lego',
        'puzzle',
        'board_game',
        'educational',
        'collector'
    ])->default('action_figure');
    $table->string('image_url')->nullable();
    $table->enum('age_range', ['0-3', '4-7', '8-12', '13+'])->default('4-7');
    $table->string('brand')->nullable();
    $table->decimal('rating', 2, 1)->default(0.0);
    $table->boolean('is_featured')->default(false);
    $table->timestamps();
    $table->softDeletes();
});
```

### Add file_path Column (Migration)

```php
// File: 2025_12_17_021235_add_file_path_to_products_table.php

public function up()
{
    Schema::table('products', function (Blueprint $table) {
        $table->string('file_path')->nullable()->after('is_featured');
    });
}

public function down()
{
    Schema::table('products', function (Blueprint $table) {
        $table->dropColumn('file_path');
    });
}
```
