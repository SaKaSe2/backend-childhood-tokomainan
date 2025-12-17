## 📈 Database Statistics

### Get Total Products
```php
Product::count();
```

### Get Average Price
```php
Product::avg('price');
```

### Get Total Stock
```php
Product::sum('stock');
```

### Get Products by Category
```php
Product::selectRaw('category, COUNT(*) as total')
    ->groupBy('category')
    ->get();
```
