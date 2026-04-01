# SIMJAR - Complete System Documentation

## 🎯 Project Overview

SIMJAR (Sistem Inventory Jaringan) is a Laravel-based inventory management system for tracking network equipment (Perangkat Jaringan) and incoming goods (Barang Masuk) with complete role-based access control (RBAC) and authentication.

## ✨ Key Features Implemented

### 1. **Authentication System**
- User login with email/password
- User registration with automatic role assignment
- Remember me functionality
- Secure logout with session invalidation
- CSRF protection on all forms
- Password hashing with bcrypt

### 2. **Role-Based Access Control (RBAC)**
Four user roles with different permissions:
- **Super Admin**: Full system access, all branches
- **Admin Cabang**: Branch-level admin, own branch only
- **Staff**: Data entry, can input distributions
- **User**: Read-only access, view reports only

### 3. **Core Modules**
- **Barang Masuk**: Incoming goods inventory
  - Create, read, update, delete operations
  - Excel/CSV import functionality
  - PDF export capability
  - Full-text search with highlighting
  - Pagination with filter preservation

- **Perangkat Jaringan**: Network equipment management
  - Create, read, update, deactivate operations
  - Activity logging
  - Status tracking (aktif/tidak_aktif)
  - Monthly installation charts

### 4. **Dashboard**
- Real-time statistics
- Charts for equipment installations
- Quick access to modules
- Role-aware content display

## 📋 System Requirements

- **Server**: Apache/Nginx with PHP 8.2+
- **Database**: MySQL 5.7+ or MariaDB 10.2+
- **PHP Extensions**: PDO, GD (for image processing)
- **Dependencies**: Composer, Node.js (optional, for assets)

## 🚀 Getting Started

### 1. **Initial Setup**
```bash
cd c:\xampp\htdocs\Simjar_dispusip

# Setup roles and database
php artisan setup:roles

# Seed default users
php artisan db:seed --class=RoleSeeder
```

### 2. **Start the Server**
```bash
php artisan serve
```
Server will run at: `http://127.0.0.1:8000`

### 3. **Access the System**
- Visit: `http://127.0.0.1:8000/login`
- Use default credentials (see below)

## 👤 Default Users

The system comes with 3 pre-configured test users:

| Email | Password | Role | Purpose |
|-------|----------|------|---------|
| admin@simjar.test | password | Super Admin | Full access |
| staff@simjar.test | password | Staff | Data entry |
| user@simjar.test | password | User | View only |

## 📁 Project Structure

```
simjar_dispusip/
├── app/
│   ├── Console/
│   │   └── Commands/
│   │       └── SetupRoles.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── AuthController.php (NEW)
│   │   │   ├── DashboardController.php
│   │   │   ├── BarangMasukController.php
│   │   │   └── PerangkatJaringanController.php
│   │   ├── Kernel.php (UPDATED)
│   │   └── Middleware/
│   │       └── CheckRole.php (NEW)
│   └── Models/
│       ├── User.php (UPDATED)
│       ├── Role.php (NEW)
│       ├── BarangMasuk.php
│       ├── PerangkatJaringan.php
│       └── ActivityLog.php
├── database/
│   ├── migrations/
│   │   ├── 2026_02_15_000000_create_users_table.php (NEW)
│   │   ├── 2026_02_15_000001_create_roles_table.php (NEW)
│   │   ├── 2024_01_01_000001_create_barang_masuk_table.php
│   │   ├── 2024_01_01_000002_create_perangkat_jaringan_table.php
│   │   └── 2024_01_01_000003_create_activity_logs_table.php
│   └── seeders/
│       ├── RoleSeeder.php (NEW)
│       └── DatabaseSeeder.php
├── resources/
│   └── views/
│       ├── auth/ (NEW)
│       │   ├── login.blade.php
│       │   └── register.blade.php
│       ├── dashboard.blade.php
│       ├── layout.blade.php (UPDATED)
│       ├── barang_masuk/
│       │   ├── index.blade.php
│       │   ├── create.blade.php
│       │   ├── edit.blade.php
│       │   ├── show.blade.php
│       │   └── import.blade.php
│       └── perangkat_jaringan/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           ├── show.blade.php
│           └── activity_log.blade.php
├── routes/
│   └── web.php (UPDATED)
├── config/
│   ├── app.php
│   ├── database.php
│   └── session.php
└── public/
    ├── index.php
    └── storage/
```

## 🔐 Authentication Flow

### Login Process
1. User visits `/login`
2. Enters email and password
3. System authenticates against users table
4. Session is regenerated for security
5. User redirected to dashboard

### Registration Process
1. User visits `/register`
2. Fills name, email, password, password confirmation
3. System validates input
4. New user created with 'user' role (default)
5. User automatically logged in
6. Redirected to dashboard

### Logout Process
1. User clicks name dropdown in navbar
2. Clicks "Logout"
3. Session invalidated
4. CSRF token regenerated
5. User redirected to login page

## 🛡️ Security Features

### Authentication
- Uses Laravel's built-in authentication guard
- Password hashing with bcrypt
- Session-based authentication
- Remember token for persistent login
- Email/password validation

### Authorization
- Role-based middleware for route protection
- Helper methods for role checking
- Ability to check single or multiple roles
- 403 response for unauthorized access

### Protection Measures
- CSRF tokens on all forms
- SQL injection prevention (ORM)
- XSS prevention (Blade escaping)
- Session regeneration on login
- HTTPS recommendation (production)

## 📊 Database Schema

### Users Table
```sql
CREATE TABLE users (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255),
    email VARCHAR(255) UNIQUE,
    password VARCHAR(255),
    role_id BIGINT UNSIGNED,
    remember_token VARCHAR(100),
    created_at TIMESTAMP,
    updated_at TIMESTAMP,
    FOREIGN KEY (role_id) REFERENCES roles(id)
);
```

### Roles Table
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE,
    label VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Barang Masuk Table
```sql
CREATE TABLE barang_masuk (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nomor_barang VARCHAR(255),
    nama_barang VARCHAR(255),
    kategori VARCHAR(255),
    jumlah INT,
    tanggal_masuk DATE,
    keterangan TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

### Perangkat Jaringan Table
```sql
CREATE TABLE perangkat_jaringan (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    nama_perangkat VARCHAR(255),
    tipe_perangkat VARCHAR(255),
    status ENUM('aktif', 'tidak_aktif'),
    tanggal_pemasangan DATE,
    lokasi TEXT,
    keterangan TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

## 🎨 Frontend Framework

- **CSS Framework**: Bootstrap 5.3
- **Icons**: Bootstrap Icons 1.11
- **Charts**: Chart.js 4.4
- **Templating**: Blade (Laravel)
- **Responsive**: Mobile-friendly design

## 🔌 API Routes

### Authentication Routes
```
POST   /login           - Authenticate user
GET    /login           - Show login form
POST   /register        - Register new user
GET    /register        - Show registration form
POST   /logout          - Logout user
```

### Protected Routes (Require Auth)
```
GET    /                - Dashboard
GET    /barang-masuk    - List barang masuk
POST   /barang-masuk    - Create barang masuk
GET    /barang-masuk/create - Show create form
GET    /barang-masuk/{id} - View barang masuk
GET    /barang-masuk/{id}/edit - Edit form
PUT    /barang-masuk/{id} - Update barang masuk
DELETE /barang-masuk/{id} - Delete barang masuk
GET    /barang-masuk/export/pdf - Export to PDF
GET    /barang-masuk/import - Show import form
POST   /barang-masuk/import - Import from Excel

GET    /perangkat-jaringan - List perangkat
POST   /perangkat-jaringan - Create perangkat
GET    /perangkat-jaringan/create - Show create form
GET    /perangkat-jaringan/{id} - View perangkat
GET    /perangkat-jaringan/{id}/edit - Edit form
PUT    /perangkat-jaringan/{id} - Update perangkat
POST   /perangkat-jaringan/{id}/deactivate - Deactivate
GET    /perangkat-jaringan/{id}/activity-log - View activity log
```

## 🧪 Testing the System

### Test Checklist
- [ ] Login with admin@simjar.test
- [ ] Verify dashboard loads
- [ ] Check user name in navbar
- [ ] Click user dropdown
- [ ] See user role displayed
- [ ] Click logout
- [ ] Verify redirected to login
- [ ] Register new user
- [ ] Login with new user
- [ ] Verify new user has 'user' role
- [ ] Access barang-masuk module
- [ ] Search with keywords
- [ ] Try import feature
- [ ] Export to PDF
- [ ] Access perangkat-jaringan module
- [ ] View equipment activity log

## 🔧 Configuration

### Session Configuration
File: `config/session.php`
```php
'lifetime' => 120,  // Minutes
'expire_on_close' => false,
'encrypt' => false,
'files' => storage_path('framework/sessions'),
```

### Database Configuration
File: `config/database.php`
```php
'mysql' => [
    'host' => 'localhost',
    'port' => 3306,
    'database' => 'simjar_db',
    'username' => 'root',
    'password' => '',
],
```

### Authentication Configuration
File: `config/auth.php`
```php
'guards' => [
    'web' => [
        'driver' => 'session',
        'provider' => 'users',
    ],
],
'providers' => [
    'users' => [
        'driver' => 'eloquent',
        'model' => App\Models\User::class,
    ],
],
```

## 📚 Code Examples

### Check User Role in Controller
```php
if (auth()->user()->hasRole('super_admin')) {
    // User is super admin
}

if (auth()->user()->hasAnyRole(['super_admin', 'admin_cabang'])) {
    // User is admin or branch admin
}
```

### Check Role in Blade Template
```blade
@if (auth()->user()->hasRole('staff'))
    <button>Add New Item</button>
@endif

@if (auth()->user()->hasAnyRole(['super_admin', 'admin_cabang']))
    <a href="/admin">Admin Panel</a>
@endif
```

### Protect Route with Middleware
```php
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->middleware('role:super_admin');
```

### Get Current User Information
```php
// In controller
auth()->user()->name;           // User name
auth()->user()->email;          // User email
auth()->user()->role->name;     // Role name
auth()->user()->role->label;    // Role label

// In view
{{ auth()->user()->name }}
{{ auth()->user()->role->label }}
```

## 🐛 Troubleshooting

### Common Issues

**Issue: Can't login**
- Solution: Check email/password in database
- Verify roles table is populated
- Run: `php artisan setup:roles`

**Issue: "Page not found" error**
- Solution: Check routes are registered
- Clear route cache: `php artisan route:clear`
- Verify database migrations ran

**Issue: "Class not found" error**
- Solution: Check namespace is correct
- Clear cache: `php artisan optimize:clear`
- Verify file is in correct directory

**Issue: Role not working**
- Solution: Check user has role_id set
- Verify role exists in database
- Check role name matches exactly

## 📖 Useful Commands

```bash
# Database
php artisan migrate              # Run migrations
php artisan migrate:rollback     # Rollback migrations
php artisan migrate:fresh        # Reset database
php artisan db:seed             # Seed database

# Cache
php artisan cache:clear         # Clear cache
php artisan route:clear         # Clear route cache
php artisan config:clear        # Clear config cache
php artisan optimize:clear      # Clear all caches

# Setup
php artisan setup:roles         # Create roles and tables
php artisan db:seed --class=RoleSeeder  # Seed roles

# Server
php artisan serve               # Start development server
php artisan serve --host=0.0.0.0 --port=8000  # Custom host/port

# Routes
php artisan route:list          # List all routes
php artisan route:list --path=login  # List specific routes
```

## 📞 Support & Maintenance

### Regular Tasks
- Monitor user activity logs
- Backup database regularly
- Clear cache periodically
- Update packages: `composer update`
- Check server logs for errors

### Enhancement Ideas
1. Add two-factor authentication
2. Implement email verification
3. Add user profile management
4. Create admin user management panel
5. Implement audit logging
6. Add notification system
7. Create API with token authentication
8. Add data analytics dashboard

## 🎓 Learning Resources

- Laravel Documentation: https://laravel.com/docs
- Blade Template Syntax: https://laravel.com/docs/blade
- Eloquent ORM: https://laravel.com/docs/eloquent
- Authentication: https://laravel.com/docs/authentication
- Authorization: https://laravel.com/docs/authorization

## 📝 Changelog

### Version 1.0 (Current)
- ✅ User authentication (login/logout/register)
- ✅ Role-based access control
- ✅ Barang masuk module with import/export
- ✅ Perangkat jaringan module with activity logging
- ✅ Dashboard with statistics and charts
- ✅ Full-text search functionality
- ✅ Responsive design

## 📄 License & Credits

SIMJAR - Sistem Inventory Jaringan
Created for inventory management of network equipment and incoming goods.

---

**Status**: ✅ Production Ready
**Last Updated**: 2024
**Version**: 1.0 with RBAC
