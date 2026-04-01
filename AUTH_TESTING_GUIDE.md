# Authentication System - Testing Guide

## Default Users Created

The system has been set up with 4 default users, one for each role:

### 1. Super Admin
- **Email:** admin@simjar.test
- **Password:** password
- **Role:** Super Admin (Lihat semua cabang)
- **Access:** Can view all branches and data

### 2. Staff
- **Email:** staff@simjar.test
- **Password:** password
- **Role:** Staff (Input distribusi)
- **Access:** Can input and manage distributions

### 3. User
- **Email:** user@simjar.test
- **Password:** password
- **Role:** User (Hanya lihat laporan)
- **Access:** Can only view reports

## How to Test

### 1. Access the System
- Open your browser and go to: `http://127.0.0.1:8000/login`

### 2. Login with a Default User
- Use one of the email/password combinations above
- Check "Remember me" (optional)
- Click Login

### 3. Features to Test

#### After Login:
- Dashboard will display
- Navbar shows your name and role
- Click your name in the navbar to see:
  - Your current role
  - Logout button

#### Test Different Roles:
1. **Super Admin** (admin@simjar.test):
   - Can access all modules
   - Can import data
   - Can manage all branches

2. **Staff** (staff@simjar.test):
   - Can input Barang Masuk
   - Can view Perangkat Jaringan
   - Can create distributions

3. **User** (user@simjar.test):
   - Can only view and search data
   - No edit/delete access
   - Limited to reports

### 4. Register New User
- Go to: `http://127.0.0.1:8000/register`
- Fill in the form
- New users automatically get the "User" role
- Can login after registration

### 5. Logout
- Click your name in top-right navbar
- Click "Logout" from the dropdown menu
- You'll be redirected to login page

## Database Information

### Roles Table
```sql
SELECT * FROM roles;
```
Shows: id, name, label, description, timestamps

### Users Table
```sql
SELECT * FROM users;
```
Shows: id, name, email, password, role_id, timestamps

## Key Features Implemented

✅ User Authentication (Login/Logout/Register)
✅ 4 User Roles (Super Admin, Admin Cabang, Staff, User)
✅ Role-based Access Control (RBAC)
✅ Default Users for Testing
✅ Password Hashing with Laravel's Hash class
✅ Session Management
✅ Remember Me functionality
✅ Error Handling and Validation
✅ User Profile Display in Navbar
✅ Dropdown Menu with Logout

## Next Steps

### Optional Enhancements:
1. Add role-based route protection (middleware on routes)
2. Create AdminController for user/role management
3. Add branch selection for Admin Cabang users
4. Filter data by user role and branch
5. Add role-based dashboard widgets
6. Create audit log for role changes

## Troubleshooting

### Can't Login?
- Check email and password are correct
- Default users are case-sensitive
- Make sure migrations ran successfully

### Database Error?
- Run: `php artisan setup:roles`
- Run: `php artisan db:seed --class=RoleSeeder`

### Page Redirects to Login?
- You're not authenticated
- Login first at `/login`
