# SIMJAR RBAC - Quick Reference

## 🚀 Quick Start

### Access the System
```
URL: http://127.0.0.1:8000/login
```

### Default Credentials
```
Email: admin@simjar.test
Password: password
```

## 👤 Available Test Accounts

| Email | Password | Role |
|-------|----------|------|
| admin@simjar.test | password | Super Admin |
| staff@simjar.test | password | Staff |
| user@simjar.test | password | User |

## 🔑 Key Features

✅ **Login** - Email/password authentication
✅ **Register** - Create new user account
✅ **Logout** - Safely logout (click name → Logout)
✅ **Role Display** - See your role in navbar
✅ **Session Security** - Automatic session management

## 📱 Navigation

### After Login
1. Click username in top-right corner
2. See your role in dropdown
3. Click "Logout" to exit

### Main Menu (Sidebar)
- Dashboard - System overview
- Barang Masuk - Inventory management
- Perangkat Jaringan - Equipment management

## 🔐 Security Notes

- Never share passwords
- Password is hashed in database
- Session times out after 2 hours
- Session regenerates on login
- CSRF protection on all forms

## ⚙️ If You Need to Reset

### Reset Everything
```bash
php artisan setup:roles
php artisan db:seed --class=RoleSeeder
```

### Reset Just Users
```bash
php artisan tinker
>>> DB::table('users')->truncate()
>>> exit
php artisan db:seed --class=RoleSeeder
```

## 🐛 Common Issues

### "Login Failed"
- Check email/password spelling
- Default password is: `password`
- Email is case-sensitive

### "Page Not Found"
- Make sure server is running: `php artisan serve`
- Check URL is exactly: `http://127.0.0.1:8000`

### "Can't Logout"
- Click your name in navbar (top-right)
- Click "Logout" from dropdown menu
- Should redirect to login page

## 📊 Database Check

### Verify Setup
```bash
php artisan tinker

# Check roles
>>> Role::all()

# Check users  
>>> User::with('role')->get()

# Check current user
>>> auth()->user()

>>> exit
```

## 📞 Support

### Check Server Status
```bash
# Server running?
http://127.0.0.1:8000

# Routes working?
php artisan route:list
```

### Clear Cache If Issues
```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear
```

## 🎯 Role Definitions

| Role | Can Do |
|------|--------|
| Super Admin | Everything |
| Admin Cabang | Manage own branch |
| Staff | Input data, manage distributions |
| User | View and search only |

## 🔗 Key URLs

| Page | URL |
|------|-----|
| Login | /login |
| Register | /register |
| Dashboard | / |
| Barang Masuk | /barang-masuk |
| Perangkat Jaringan | /perangkat-jaringan |

## 💡 Tips

1. Always click "Remember me" on personal devices
2. New registered users get "User" role by default
3. Admins can later change user roles in database
4. Use dashboard to see system statistics
5. Search functionality on all list pages

## 🎓 Test Scenarios

### Scenario 1: Admin Full Access
1. Login as: admin@simjar.test / password
2. Try all menu options
3. See all features available
4. Logout

### Scenario 2: Staff Limited Access  
1. Login as: staff@simjar.test / password
2. Try creating inventory items
3. View equipment list
4. Try admin features (won't work)
5. Logout

### Scenario 3: User Read-Only
1. Login as: user@simjar.test / password
2. Can view lists
3. Can search data
4. Cannot edit/delete
5. Cannot import/export
6. Logout

### Scenario 4: New Registration
1. Click "Register" on login page
2. Fill form with new details
3. Note: New users get "User" role
4. Can login immediately
5. Check dashboard

## 📋 Files Modified

- **routes/web.php** - Auth routes added
- **app/Models/User.php** - Role relationship
- **resources/views/layout.blade.php** - Navbar updated
- **app/Http/Kernel.php** - Middleware registered

## ✨ New Files Created

- **app/Http/Controllers/AuthController.php**
- **app/Http/Middleware/CheckRole.php**
- **app/Models/Role.php**
- **resources/views/auth/login.blade.php**
- **resources/views/auth/register.blade.php**
- **database/migrations/2026_02_15_*.php**
- **database/seeders/RoleSeeder.php**

## 🎉 You're Ready!

Everything is set up and ready to use. Start by:

1. Open browser
2. Visit: http://127.0.0.1:8000/login
3. Login with: admin@simjar.test / password
4. Explore the system!

---

**Questions?** Check **COMPLETE_DOCUMENTATION.md** for detailed info.
