# 🚀 Setup Instructions - Sistem Blacklist Rental Indonesia

Panduan lengkap untuk setup dan menjalankan aplikasi dari awal.

## 📋 Prerequisites

Pastikan sistem Anda memiliki:

### Required Software
- **PHP 8.2+** dengan extensions:
  - BCMath, Ctype, Fileinfo, JSON, Mbstring, OpenSSL, PDO, Tokenizer, XML
- **Composer 2.0+**
- **Node.js 18.0+** dan NPM 8.0+
- **Git** untuk version control

### Optional (untuk production)
- **Web Server**: Apache/Nginx
- **Database**: MySQL/PostgreSQL (SQLite default untuk development)

## 🔧 Installation Steps

### 1. Clone Repository

```bash
# Clone dari GitHub (setelah repository dibuat)
git clone https://github.com/tupski/rental-blacklist.git
cd rental-blacklist

# Atau download ZIP dan extract
```

### 2. Install Dependencies

```bash
# Install PHP dependencies
composer install

# Install Node.js dependencies
npm install

# Build frontend assets
npm run build
```

### 3. Environment Setup

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup

```bash
# Run migrations (akan create SQLite database otomatis)
php artisan migrate

# Seed database dengan sample data
php artisan db:seed

# Create storage symbolic link
php artisan storage:link
```

### 5. Start Development Server

```bash
# Start Laravel development server
php artisan serve

# Aplikasi akan berjalan di: http://127.0.0.1:8000
```

## 🎯 Quick Test

### Akses Aplikasi
1. **Buka browser**: `http://127.0.0.1:8000`
2. **Test pencarian**: Cari "Angga" atau "3674012345670001"
3. **Login demo**: 
   - Email: `budi@rental.com`
   - Password: `password`

### Verifikasi Features
- ✅ Halaman utama loading
- ✅ Pencarian berfungsi dengan data sensor
- ✅ Login berhasil
- ✅ Dashboard menampilkan statistik
- ✅ CRUD laporan berfungsi

## 🔧 Configuration

### Database Configuration (.env)

**SQLite (Default - Recommended untuk development):**
```env
DB_CONNECTION=sqlite
DB_DATABASE=/absolute/path/to/database/database.sqlite
```

**MySQL (Production):**
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rental_blacklist
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### Application Configuration (.env)

```env
APP_NAME="Rental Blacklist"
APP_ENV=local
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

# File Storage
FILESYSTEM_DISK=local

# Mail Configuration (optional)
MAIL_MAILER=smtp
MAIL_HOST=mailpit
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
```

## 📁 Project Structure

```
rental-blacklist/
├── 📁 app/
│   ├── Http/Controllers/     # Controllers
│   └── Models/              # Eloquent models
├── 📁 database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── 📁 docs/                # 📚 Documentation
├── 📁 public/              # Web root
├── 📁 resources/
│   ├── views/              # Blade templates
│   ├── css/                # Stylesheets
│   └── js/                 # JavaScript
├── 📁 routes/              # Route definitions
├── 📁 storage/             # File storage
└── 📁 tests/               # Test files
```

## 🧪 Testing

### Run Tests
```bash
# Run all tests
php artisan test

# Run specific test
php artisan test --filter=BlacklistTest

# Run with coverage
php artisan test --coverage
```

### Manual Testing
Ikuti panduan di `docs/TESTING_GUIDE.md` untuk testing manual lengkap.

## 🚀 Development Workflow

### 1. Daily Development
```bash
# Pull latest changes
git pull origin main

# Install new dependencies (jika ada)
composer install
npm install

# Run migrations (jika ada yang baru)
php artisan migrate

# Start development
php artisan serve
npm run dev  # untuk watch mode
```

### 2. Making Changes
```bash
# Create feature branch
git checkout -b feature/new-feature

# Make changes...

# Commit changes
git add .
git commit -m "feat: add new feature"

# Push to repository
git push origin feature/new-feature
```

### 3. Production Build
```bash
# Build for production
npm run build

# Optimize for production
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 🛠️ Troubleshooting

### Common Issues

**1. Permission Errors**
```bash
# Fix storage permissions
chmod -R 755 storage bootstrap/cache
```

**2. Key Not Set Error**
```bash
php artisan key:generate
```

**3. Storage Link Error**
```bash
php artisan storage:link --force
```

**4. Database Not Found**
```bash
# Untuk SQLite, pastikan file database ada
touch database/database.sqlite
php artisan migrate
```

**5. Assets Not Loading**
```bash
npm run build
php artisan view:clear
```

### Debug Mode

Enable debug untuk development:
```env
APP_DEBUG=true
```

Cek logs di: `storage/logs/laravel.log`

### Laravel Debugbar

Debugbar sudah terinstall untuk development:
- Akan muncul di bottom halaman saat `APP_DEBUG=true`
- Menampilkan queries, routes, views, dll

## 📚 Documentation

### Available Documentation
- `README.md` - Overview dan quick start
- `docs/DOKUMENTASI.md` - Technical documentation
- `docs/DEPLOYMENT.md` - Production deployment
- `docs/API.md` - API endpoints
- `docs/TESTING_GUIDE.md` - Testing scenarios
- `docs/GITHUB_SETUP.md` - GitHub setup
- `docs/FINAL_SUMMARY.md` - Project completion

### Code Documentation
- Controllers memiliki docblocks
- Models memiliki relationship documentation
- Views memiliki comments untuk sections

## 🔒 Security Notes

### Development Security
- ✅ CSRF protection enabled
- ✅ Input validation implemented
- ✅ File upload restrictions
- ✅ SQL injection prevention
- ✅ XSS protection

### Production Security
- Set `APP_DEBUG=false`
- Use strong `APP_KEY`
- Configure proper file permissions
- Setup SSL certificate
- Regular security updates

## 📞 Support

### Getting Help
1. **Check Documentation** - Baca docs/ folder
2. **Check Logs** - `storage/logs/laravel.log`
3. **Laravel Docs** - [laravel.com/docs](https://laravel.com/docs)
4. **GitHub Issues** - Report bugs di repository

### Community
- Laravel Indonesia Facebook Group
- Laravel Discord Server
- Stack Overflow dengan tag `laravel`

---

## ✅ Setup Checklist

- [ ] PHP 8.2+ installed
- [ ] Composer installed
- [ ] Node.js & NPM installed
- [ ] Repository cloned
- [ ] Dependencies installed (`composer install`)
- [ ] Frontend built (`npm run build`)
- [ ] Environment configured (`.env`)
- [ ] Database migrated (`php artisan migrate`)
- [ ] Database seeded (`php artisan db:seed`)
- [ ] Storage linked (`php artisan storage:link`)
- [ ] Server started (`php artisan serve`)
- [ ] Application accessible at `http://127.0.0.1:8000`
- [ ] Login test successful
- [ ] Search functionality working

---

**🎉 Selamat! Aplikasi siap digunakan!**

**Next Steps:**
1. Explore fitur-fitur aplikasi
2. Baca dokumentasi lengkap di `docs/`
3. Lakukan testing sesuai `docs/TESTING_GUIDE.md`
4. Deploy ke production dengan `docs/DEPLOYMENT.md`
