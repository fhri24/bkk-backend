# BKK System - Web Terpisah (Admin & Public)

## 📋 Ringkasan Implementasi

Sistem telah berhasil dipisah menjadi **2 portal terpisah** dengan autentikasi berbeda:

### 1. **Portal ADMIN** (`/admin/*`)

- Hanya untuk **Admin BKK**, **Kepala BKK**, **Super Admin**, dan **Perusahaan**
- Email khusus untuk login admin
- Akses melalui middleware `admin`
- Fitur: Dashboard, Manajemen Lowongan Kerja

### 2. **Portal PUBLIC/STUDENT** (`/student/*` dan `/`)

- Untuk **Siswa** yang mendaftar
- Akses daftar lowongan kerja
- Email siswa tersimpan di database
- Fitur: Home, Profile, Daftar Lamaran

---

## 🔐 Akun Default untuk Testing

### Admin Accounts:

```
Super Admin:
  Email: superadmin@bkk.com
  Password: password123

Admin BKK:
  Email: admin@bkk.com
  Password: password123

Kepala BKK:
  Email: kepala@bkk.com
  Password: password123

Company (Perusahaan):
  Email: company@majujaya.com
  Password: password123
```

### Student Accounts:

```
Siswa 1-5:
  Email: siswa1@example.com - siswa5@example.com
  Password: password123
```

---

## 🗂️ Struktur File yang Dibuat

### Controllers

```
app/Http/Controllers/
├── AuthController.php          (Login, Register, Logout)
├── Admin/
│   ├── DashboardController.php (Admin Dashboard)
│   └── JobController.php       (Manajemen Job)
└── Student/
    └── HomeController.php      (Student Portal)
```

### Middleware

```
app/Http/Middleware/
├── CheckAdminRole.php          (Proteksi Admin Area)
├── CheckStudentRole.php        (Proteksi Student Area)
└── RedirectIfAuthenticated.php (Auto Redirect)
```

### Views

```
resources/views/
├── layouts/
│   ├── app.blade.php          (Master Layout)
│   ├── navbar.blade.php       (Navigation)
│   └── footer.blade.php       (Footer)
├── auth/
│   ├── login.blade.php        (Login Page)
│   └── register.blade.php     (Register Siswa)
├── public/
│   └── home.blade.php         (Landing Page)
├── admin/
│   ├── dashboard.blade.php    (Admin Dashboard)
│   └── jobs/
│       ├── index.blade.php    (Job List)
│       └── create.blade.php   (Job Form)
└── student/
    ├── home.blade.php         (Student Home)
    ├── profile.blade.php      (Student Profile)
    └── applications.blade.php (Student Applications)
```

### Routes

```
routes/web.php
├── Public Routes
│   ├── GET  /                 (Landing Page)
│   ├── GET  /login            (Login Form)
│   ├── POST /login            (Process Login)
│   ├── GET  /register         (Register Form)
│   └── POST /register         (Process Register)
├── Student Routes (Protected by 'auth', 'student')
│   ├── GET  /student/         (Home)
│   ├── GET  /student/profile  (Profile)
│   └── GET  /student/applications (Applications)
└── Admin Routes (Protected by 'auth', 'admin')
    ├── GET  /admin/           (Dashboard)
    ├── GET  /admin/jobs       (Job List)
    ├── GET  /admin/jobs/create (Job Form)
    ├── POST /admin/jobs       (Store Job)
    └── DELETE /admin/jobs/{job} (Delete Job)
```

---

## 🔄 Flow Autentikasi

```
1. USER VISIT WEBSITE
   ↓
2. CHOOSE LOGIN / REGISTER
   ├─→ ADMIN USERS (Email: admin domains)
   │   ↓
   │   Redirect ke /admin/dashboard
   │
   └─→ STUDENT USERS (Email: student domains)
       ↓
       Redirect ke /student/ (Home)

3. LOGOUT
   ↓
   Redirect ke / (Landing Page)
```

---

## 💾 Database Synchronization

### Roles yang sudah tersedia:

```
1. super_admin    → Super Admin
2. admin_bkk      → Admin BKK
3. kepala_bkk     → Kepala BKK
4. perusahaan     → Company Account
5. siswa          → Student Account
6. kepala_sekolah → School Principal
```

### Data Tersinkronisasi:

- ✅ User accounts dengan roles
- ✅ Student profiles dengan email
- ✅ Company data
- ✅ Job listings & applications

---

## 🚀 Cara Menjalankan

### First Time Setup:

```bash
# Fresh migration dengan seed data
php artisan migrate:fresh --seed

# Atau jika sudah ada database:
php artisan migrate
php artisan db:seed --class=UserSeeder
```

### Jalankan Development Server:

```bash
php artisan serve
```

Akses di: `http://localhost:8000`

---

## 📝 Middleware & Proteksi

### Admin Area (`/admin/*`):

- Middleware: `CheckAdminRole`
- Allowed Roles: `super_admin`, `admin_bkk`, `kepala_bkk`, `perusahaan`
- Unauthorized: Error 403

### Student Area (`/student/*`):

- Middleware: `CheckStudentRole`
- Allowed Roles: `siswa` only
- Auto Redirect jika bukan siswa

### Guest Middleware:

- Login/Register hanya untuk yang belum login
- Auto redirect ke dashboard jika sudah login

---

## ✨ Fitur yang Sudah Tersedia

### Admin Panel:

- ✅ Dashboard dengan statistik
- ✅ Manajemen Job Listings
- ✅ Tambah job baru
- ✅ Hapus job
- ✅ Lihat semua lowongan

### Student Portal:

- ✅ Daftar akun baru
- ✅ Login dengan email
- ✅ Lihat profil
- ✅ Lihat daftar lowongan
- ✅ Lihat status lamaran
- ✅ Home dashboard

---

## 🔧 Customization

### Tambah Akun Admin Baru:

Edit `/database/seeders/UserSeeder.php` atau buat script SQL

### Ubah Email Domain Khusus:

Lihat di `AuthController.php` bagian role checking

### Ubah Routing:

Edit `/routes/web.php` untuk custom routes

---

## 📞 Testing Routes

```bash
# Test Landing Page
curl http://localhost:8000/

# Test Admin Login
curl -X POST http://localhost:8000/login \
  -d "email=superadmin@bkk.com&password=password123"

# Test Student Login
curl -X POST http://localhost:8000/login \
  -d "email=siswa1@example.com&password=password123"
```

---

## 🎯 Next Steps (Optional)

1. **Email Verification**: Add email verification untuk siswa
2. **Job Application**: Implement job application system
3. **Admin Reports**: Add reporting features untuk admin
4. **Two-Factor Auth**: Tambah 2FA untuk keamanan
5. **API**: Buat REST API untuk mobile app
6. **Deployment**: Setup production environment

---

**System Status**: ✅ Ready to Use

Semua komponen sudah di-setup dan siap untuk testing!
