# Panduan Kontribusi

Terima kasih atas minat Anda untuk berkontribusi pada Sistem Blacklist Rental Indonesia! 🎉

## 🚀 Cara Berkontribusi

### 1. Fork Repository
```bash
# Fork repository ini ke akun GitHub Anda
# Kemudian clone ke lokal
git clone https://github.com/YOUR_USERNAME/rental-blacklist.git
cd rental-blacklist
```

### 2. Setup Development Environment
```bash
# Install dependencies
composer install
npm install && npm run build

# Setup environment
cp .env.example .env
php artisan key:generate

# Setup database
php artisan migrate:fresh --seed
php artisan storage:link

# Jalankan server
php artisan serve
```

### 3. Buat Branch Baru
```bash
# Buat branch untuk fitur/perbaikan Anda
git checkout -b feature/nama-fitur
# atau
git checkout -b fix/nama-bug
```

### 4. Lakukan Perubahan
- Ikuti coding standards Laravel
- Tulis kode yang bersih dan terdokumentasi
- Tambahkan tests jika diperlukan
- Update dokumentasi jika diperlukan

### 5. Commit & Push
```bash
# Commit dengan pesan yang jelas
git add .
git commit -m "feat: tambah fitur pencarian advanced"

# Push ke repository fork Anda
git push origin feature/nama-fitur
```

### 6. Buat Pull Request
- Buka GitHub dan buat Pull Request
- Berikan deskripsi yang jelas tentang perubahan
- Referensikan issue yang terkait (jika ada)

## 📝 Coding Standards

### PHP/Laravel
- Ikuti [PSR-12](https://www.php-fig.org/psr/psr-12/) coding standard
- Gunakan Laravel best practices
- Tulis docblocks untuk methods dan classes
- Gunakan type hints dan return types

### JavaScript
- Gunakan ES6+ syntax
- Konsisten dengan jQuery patterns yang ada
- Tambahkan comments untuk logic yang kompleks

### CSS/TailwindCSS
- Gunakan utility classes TailwindCSS
- Konsisten dengan design system yang ada
- Responsive design untuk semua komponen

### Database
- Gunakan migrations untuk perubahan schema
- Tulis seeders untuk data testing
- Ikuti naming conventions Laravel

## 🐛 Melaporkan Bug

### Sebelum Melaporkan
- Pastikan bug belum dilaporkan sebelumnya
- Coba reproduksi di environment yang bersih
- Kumpulkan informasi yang diperlukan

### Template Bug Report
```markdown
**Deskripsi Bug**
Deskripsi singkat dan jelas tentang bug.

**Langkah Reproduksi**
1. Buka halaman '...'
2. Klik pada '....'
3. Scroll ke bawah '....'
4. Lihat error

**Hasil yang Diharapkan**
Deskripsi tentang apa yang seharusnya terjadi.

**Hasil Aktual**
Deskripsi tentang apa yang benar-benar terjadi.

**Screenshots**
Jika applicable, tambahkan screenshots.

**Environment:**
- OS: [e.g. Windows 10]
- Browser: [e.g. Chrome 91]
- PHP Version: [e.g. 8.2]
- Laravel Version: [e.g. 12.0]
```

## 💡 Mengusulkan Fitur

### Template Feature Request
```markdown
**Apakah feature request terkait dengan masalah? Jelaskan.**
Deskripsi jelas tentang masalah yang ingin diselesaikan.

**Solusi yang Diinginkan**
Deskripsi jelas tentang apa yang Anda inginkan.

**Alternatif yang Dipertimbangkan**
Deskripsi tentang solusi atau fitur alternatif.

**Konteks Tambahan**
Tambahkan konteks atau screenshots tentang feature request.
```

## 🧪 Testing

### Menjalankan Tests
```bash
# Unit tests
php artisan test

# Feature tests
php artisan test --testsuite=Feature

# Specific test
php artisan test tests/Feature/BlacklistTest.php
```

### Menulis Tests
- Tulis tests untuk fitur baru
- Update tests yang ada jika diperlukan
- Pastikan semua tests pass sebelum submit PR

## 📚 Dokumentasi

### Update Dokumentasi
- Update README.md jika ada perubahan setup
- Update docs/ jika ada perubahan API
- Update CHANGELOG.md untuk setiap release

### Menulis Dokumentasi
- Gunakan bahasa Indonesia yang jelas
- Sertakan contoh kode jika diperlukan
- Tambahkan screenshots untuk UI changes

## 🎯 Prioritas Kontribusi

### High Priority
- 🐛 Bug fixes
- 🔒 Security improvements
- 📱 Mobile responsiveness
- ⚡ Performance optimizations

### Medium Priority
- ✨ New features
- 🎨 UI/UX improvements
- 📝 Documentation updates
- 🧪 Test coverage

### Low Priority
- 🧹 Code refactoring
- 📦 Dependency updates
- 🎭 Code style improvements

## 💬 Komunikasi

### Channels
- **GitHub Issues**: Bug reports dan feature requests
- **GitHub Discussions**: Diskusi umum dan Q&A
- **Pull Requests**: Code review dan diskusi implementasi

### Etika
- Bersikap sopan dan profesional
- Berikan feedback yang konstruktif
- Hormati pendapat dan kontribusi orang lain
- Fokus pada kode, bukan personal

## 🏆 Recognition

Semua kontributor akan diakui dalam:
- README.md contributors section
- CHANGELOG.md untuk kontribusi spesifik
- GitHub contributors page

## 📞 Bantuan

Jika Anda membutuhkan bantuan:
1. Baca dokumentasi terlebih dahulu
2. Cari di GitHub Issues
3. Buat issue baru dengan label "question"
4. Atau mulai diskusi di GitHub Discussions

---

**Terima kasih atas kontribusi Anda! 🙏**
