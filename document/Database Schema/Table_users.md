## 👥 Table: users

### Schema

| Column | Type | Constraint | Description |
|--------|------|------------|-------------|
| id | BIGINT UNSIGNED | PRIMARY KEY, AUTO_INCREMENT | User ID |
| name | VARCHAR(255) | NOT NULL | Nama user |
| email | VARCHAR(255) | NOT NULL, UNIQUE | Email user |
| email_verified_at | TIMESTAMP | NULLABLE | Tanggal verifikasi email |
| password | VARCHAR(255) | NOT NULL | Password (hashed) |
| remember_token | VARCHAR(100) | NULLABLE | Remember me token |
| created_at | TIMESTAMP | NULLABLE | Tanggal dibuat |
| updated_at | TIMESTAMP | NULLABLE | Tanggal diupdate |

### Indexes

```sql
PRIMARY KEY (id)
UNIQUE KEY (email)
```

### Migration File

```php
Schema::create('users', function (Blueprint $table) {
    $table->id();
    $table->string('name');
    $table->string('email')->unique();
    $table->timestamp('email_verified_at')->nullable();
    $table->string('password');
    $table->rememberToken();
    $table->timestamps();
});
```

### Sample Data

```sql
INSERT INTO users (name, email, password) VALUES
('Test User', 'test@example.com', '$2y$12$hashed_password');
```
