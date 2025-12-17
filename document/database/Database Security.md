## 🔒 Database Security

### ✅ Best Practices

1. **Use Prepared Statements** (Laravel ORM otomatis)
```php
// ✅ Safe (using ORM)
Product::where('name', $userInput)->get();

// ❌ Unsafe (raw query)
DB::select("SELECT * FROM products WHERE name = '$userInput'");
```

2. **Hash Passwords**
```php
'password' => bcrypt($request->password)
```

3. **Mass Assignment Protection**
```php
protected $fillable = ['name', 'email'];
// Atau
protected $guarded = ['id', 'admin'];
```

4. **Soft Delete** untuk data penting
```php
use SoftDeletes;
$product->delete(); // Soft delete
$product->forceDelete(); // Permanent delete
```
