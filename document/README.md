# 📚 Dokumentasi API Toko Mainan

## 🎯 Overview Project

**Nama Project**: Backend Toko Mainan  
**Framework**: Laravel 11  
**Database**: MySQL  
**Authentication**: JWT (JSON Web Token)  
**Theme**: E-commerce Toko Mainan

---

## 📋 Daftar Isi Dokumentasi

1. **Overview & Setup** (Dokumen ini)
2. **Konfigurasi Environment**
3. **Authentication (JWT)**
4. **Product Management**
5. **File Storage**
6. **Testing dengan Postman**
7. **Database Schema**
8. **Troubleshooting**

---

## 🎯 Fitur Utama

### ✅ Authentication
- Register user baru
- Login dengan JWT token
- Logout
- Get user profile (me)

### ✅ Product Management
- **Public Endpoints** (Tanpa Auth):
  - Get all products dengan pagination & filtering
  - Get product detail by ID
  
- **Protected Endpoints** (Dengan Auth):
  - Create product baru
  - Update product
  - Delete product (soft delete)
  - Upload file/gambar product

### ✅ File Storage
- Upload file dengan validasi maksimal 5MB
- Storage menggunakan symbolic link
- Automatic file deletion saat update

---

## 🛠️ Tech Stack

- **Backend Framework**: Laravel 11
- **Authentication**: php-open-source-saver/jwt-auth
- **Database**: MySQL
- **Storage**: Local Storage dengan Symbolic Link
- **API Testing**: Postman

---

## 📦 Dependencies Utama

```json
{
  "php": "^8.2",
  "laravel/framework": "^11.0",
  "php-open-source-saver/jwt-auth": "^2.0"
}
```

---

## 🚀 Quick Start

### 1. Clone & Install
```bash
# Clone repository
git clone <repository-url>
cd backend-tokomainan

# Install dependencies
composer install
```

### 2. Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Generate JWT secret
php artisan jwt:secret
```

### 3. Setup Database
```bash
# Run migrations
php artisan migrate

# Run seeders (optional)
php artisan db:seed
```

### 4. Setup Storage
```bash
# Create symbolic link
php artisan storage:link
```

### 5. Run Server
```bash
php artisan serve
# Server berjalan di http://localhost:8000
```

---

## 📊 Database Tables

1. **users** - Menyimpan data user
2. **products** - Menyimpan data produk mainan
3. **personal_access_tokens** - Token management
4. **password_reset_tokens** - Password reset
5. **failed_jobs** - Failed job tracking

---

## 🔗 Base URL

```
Local Development: http://localhost:8000
API Base Path: /api
```

---

## 📌 HTTP Status Codes

| Code | Keterangan |
|------|------------|
| 200 | OK - Request berhasil |
| 201 | Created - Resource berhasil dibuat |
| 400 | Bad Request - Request tidak valid |
| 401 | Unauthorized - Token tidak valid/expired |
| 404 | Not Found - Resource tidak ditemukan |
| 422 | Unprocessable Entity - Validasi gagal |
| 500 | Internal Server Error |

---

## 🔐 Authentication Header

Untuk endpoint yang memerlukan authentication, sertakan header:

```
Authorization: Bearer <your_jwt_token>
```

---

## 📝 Response Format

### Success Response
```json
{
  "status": "success",
  "message": "Operation successful",
  "data": { ... }
}
```

### Error Response
```json
{
  "status": "error",
  "message": "Error description"
}
```

---

## 👥 Team & Credits

**Disusun oleh:**
- Rikza Ahmad Nur Muhammad

**University**  
Universitas Muhammadiyah Malang

---

## 📅 Version

- **Version**: 1.0
- **Last Updated**: Desember 2025
- **Laravel Version**: 11.x
- **PHP Version**: 8.2+

---

**Next**: Lanjut ke dokumentasi [Konfigurasi Environment]()
