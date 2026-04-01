# RBAC System Implementation - Complete

## Overview
Role-Based Access Control (RBAC) system with 4 roles, full authentication, and session management.

## Architecture

### 1. Database Structure

#### Roles Table
```sql
CREATE TABLE roles (
    id BIGINT UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) UNIQUE NOT NULL,
    label VARCHAR(255),
    description TEXT,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);
```

#### Users Table (with role_id)
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
    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE SET NULL
);
```

### 2. Models

#### Role Model
- Location: `app/Models/Role.php`
- Relationships:
  - `users()` - HasMany relationship to User model
- Attributes: id, name, label, description, timestamps

#### User Model
- Location: `app/Models/User.php`
- Relationships:
  - `role()` - BelongsTo relationship to Role model
- Key Methods:
  - `hasRole($name)` - Check if user has specific role
  - `hasAnyRole($names)` - Check if user has any of multiple roles
- Authentication: Uses Laravel's built-in Authenticatable trait

### 3. Authentication

#### AuthController
- Location: `app/Http/Controllers/AuthController.php`
- Methods:
  - `showLogin()` - Display login form
  - `login(Request $request)` - Authenticate user
  - `logout(Request $request)` - Logout user
  - `showRegister()` - Display registration form
  - `register(Request $request)` - Register new user

#### Features:
- Email/password authentication
- Remember me functionality
- Password hashing with Hash::make()
- Session regeneration for security
- Form validation with Laravel's validator
- Automatic role assignment for new users (default: 'user' role)

### 4. Views

#### auth/login.blade.php
- Email input field
- Password input field
- Remember me checkbox
- Bootstrap styling
- Error display

#### auth/register.blade.php
- Name input field
- Email input field
- Password input field
- Password confirmation field
- Bootstrap styling
- Error display

### 5. Middleware

#### CheckRole Middleware
- Location: `app/Http/Middleware/CheckRole.php`
- Purpose: Protect routes based on user role
- Usage: `Route::get(...)->middleware('role:super_admin,admin_cabang')`
- Logic:
  1. Check if user is authenticated
  2. Check if user has any of specified roles
  3. Return 403 if not authorized
  4. Allow request if authorized

### 6. Routes

#### Public Routes (No Auth Required)
```php
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
```

#### Protected Routes (Auth Required)
```php
Route::middleware('auth')->group(function () {
    // All app routes here
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::prefix('barang-masuk')->group([...]);
    Route::prefix('perangkat-jaringan')->group([...]);
});
```

### 7. Defined Roles

| Role | Name | Label | Description | Purpose |
|------|------|-------|-------------|---------|
| 1 | super_admin | Super Admin | Lihat semua cabang | Full system access |
| 2 | admin_cabang | Admin Cabang | Hanya lihat cabangnya | Branch-level admin |
| 3 | staff | Staff | Input distribusi | Data entry staff |
| 4 | user | User | Hanya lihat laporan | Report viewer only |

### 8. Default Test Users

```
┌─────────────────┬──────────────────────┬──────────┬──────────────────────┐
│ Email           │ Name                 │ Password │ Role                 │
├─────────────────┼──────────────────────┼──────────┼──────────────────────┤
│ admin@simjar.   │ Super Admin          │ password │ super_admin          │
│ test            │                      │          │                      │
├─────────────────┼──────────────────────┼──────────┼──────────────────────┤
│ staff@simjar.   │ Staff                │ password │ staff                │
│ test            │                      │          │                      │
├─────────────────┼──────────────────────┼──────────┼──────────────────────┤
│ user@simjar.    │ User                 │ password │ user                 │
│ test            │                      │          │                      │
└─────────────────┴──────────────────────┴──────────┴──────────────────────┘
```

## Security Features

1. **Password Hashing**
   - Uses Laravel's `Hash::make()` for secure hashing
   - Passwords never stored in plain text

2. **Session Management**
   - `$request->session()->regenerate()` after login
   - `$request->session()->invalidate()` on logout
   - `$request->session()->regenerateToken()` for CSRF protection

3. **CSRF Protection**
   - All forms include `@csrf` token
   - Middleware checks CSRF tokens automatically

4. **Authentication Guard**
   - Uses Laravel's 'web' guard
   - Maintains session-based authentication
   - `auth()->check()` to verify login status
   - `auth()->user()` to get current user

5. **Authorization**
   - Role-based middleware for route protection
   - Helper methods for role checking in views/controllers

## Usage Examples

### Check User Role in Controller
```php
if (auth()->user()->hasRole('super_admin')) {
    // Show admin features
}

if (auth()->user()->hasAnyRole(['super_admin', 'admin_cabang'])) {
    // Show admin or branch admin features
}
```

### Check User Role in View (Blade)
```blade
@if (auth()->user()->hasRole('super_admin'))
    <button>Delete All</button>
@endif

@if (auth()->user()->hasAnyRole(['staff', 'super_admin']))
    <a href="/barang-masuk/create">Add Item</a>
@endif
```

### Protect Route with Role Middleware
```php
Route::delete('/users/{id}', [UserController::class, 'destroy'])
    ->middleware('role:super_admin');

Route::get('/reports', [ReportController::class, 'index'])
    ->middleware('role:super_admin,admin_cabang,staff');
```

### Get User Information
```php
// In controller or view
auth()->user()->name;           // User's name
auth()->user()->email;          // User's email
auth()->user()->role->name;     // Role name (super_admin, etc)
auth()->user()->role->label;    // Role label (Super Admin, etc)
```

## File Structure

```
app/
├── Console/
│   └── Commands/
│       └── SetupRoles.php (NEW - Setup command)
├── Http/
│   ├── Controllers/
│   │   └── AuthController.php (NEW)
│   ├── Kernel.php (UPDATED - Register middleware)
│   └── Middleware/
│       └── CheckRole.php (NEW)
├── Models/
│   ├── Role.php (NEW)
│   └── User.php (NEW/UPDATED)
│
database/
├── migrations/
│   ├── 2026_02_15_000000_create_users_table.php (NEW)
│   └── 2026_02_15_000001_create_roles_table.php (NEW)
└── seeders/
    └── RoleSeeder.php (NEW)

resources/
└── views/
    └── auth/
        ├── login.blade.php (NEW)
        └── register.blade.php (NEW)

routes/
└── web.php (UPDATED - Added auth routes)
```

## Setup Commands

### 1. Create Tables and Add Columns
```bash
php artisan setup:roles
```
- Creates roles table
- Creates users table
- Adds role_id column to users
- Creates indices

### 2. Seed Default Users
```bash
php artisan db:seed --class=RoleSeeder
```
- Creates 4 default roles
- Creates 3 default test users

### 3. Full Setup (if starting fresh)
```bash
php artisan migrate:fresh
php artisan db:seed
```

## Testing the RBAC System

### Test Login Flow
1. Visit `/login`
2. Enter: admin@simjar.test / password
3. Should redirect to dashboard
4. Navbar shows user name and role

### Test Logout
1. Click user name in navbar
2. Click "Logout" from dropdown
3. Should redirect to login page

### Test Register
1. Visit `/register`
2. Fill in: name, email, password, password_confirmation
3. Submit form
4. Should be logged in with 'user' role
5. Should redirect to dashboard

### Test Role Authorization (Future)
1. Create route with role middleware
2. Try accessing with different users
3. Verify only authorized roles can access

## Next Steps for Implementation

### Route-Based Access Control
```php
// Only super_admin can delete users
Route::delete('/admin/users/{id}', [UserController::class, 'destroy'])
    ->middleware('role:super_admin');

// Only staff and above can input data
Route::post('/barang-masuk', [BarangMasukController::class, 'store'])
    ->middleware('role:staff,super_admin,admin_cabang');
```

### View-Based Access Control
```blade
@if (auth()->user()->hasRole('super_admin'))
    <button class="btn btn-danger">Delete All Records</button>
@endif

@if (auth()->user()->hasAnyRole(['admin_cabang', 'super_admin']))
    <a href="/admin/users">Manage Users</a>
@endif
```

### Data Filtering by Role
```php
// In controller
$query = BarangMasuk::query();

if (!auth()->user()->hasRole('super_admin')) {
    $query->where('cabang_id', auth()->user()->branch_id);
}

$barangMasuk = $query->get();
```

## Maintenance

### Add New Role
```php
// In database
DB::table('roles')->insert([
    'name' => 'admin_keuangan',
    'label' => 'Admin Keuangan',
    'description' => 'Manage finance',
]);

// Then assign to users
```

### Change User Role
```php
// In controller or command
$user = User::find($id);
$user->role_id = Role::where('name', 'staff')->first()->id;
$user->save();
```

### Get All Users with Specific Role
```php
$staffUsers = User::whereHas('role', function ($query) {
    $query->where('name', 'staff');
})->get();
```

## Troubleshooting

### "Route not defined" Error
- Make sure routes are registered in `routes/web.php`
- Check route names in links

### "Class not found" Error
- Check namespace is correct
- Run: `php artisan optimize:clear`

### Login Fails
- Verify credentials in database
- Check password hashing
- Ensure role exists

### Permission Denied
- Check user has correct role
- Verify middleware is applied
- Check role names match exactly
