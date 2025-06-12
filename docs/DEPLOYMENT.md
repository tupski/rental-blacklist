# 🚀 Panduan Deployment

Panduan lengkap untuk deploy Sistem Blacklist Rental Indonesia ke berbagai platform.

## 📋 Persyaratan

### Minimum Requirements
- **PHP**: 8.2 atau lebih tinggi
- **Composer**: 2.0+
- **Node.js**: 18.0+
- **NPM**: 8.0+
- **Database**: SQLite/MySQL/PostgreSQL
- **Web Server**: Apache/Nginx

### PHP Extensions
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML

## 🌐 Shared Hosting (cPanel)

### 1. Persiapan File
```bash
# Di lokal, build assets
npm run build

# Compress semua file kecuali node_modules
zip -r rental-blacklist.zip . -x "node_modules/*" ".git/*"
```

### 2. Upload ke Hosting
1. Login ke cPanel
2. Buka File Manager
3. Upload `rental-blacklist.zip` ke folder `public_html`
4. Extract file

### 3. Setup Environment
```bash
# Copy environment file
cp .env.example .env

# Edit .env sesuai hosting
nano .env
```

### 4. Konfigurasi .env
```env
APP_NAME="Rental Blacklist"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

MAIL_MAILER=smtp
MAIL_HOST=your_smtp_host
MAIL_PORT=587
MAIL_USERNAME=your_email
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
```

### 5. Install Dependencies
```bash
# Via SSH atau Terminal cPanel
composer install --optimize-autoloader --no-dev

# Generate app key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database
php artisan db:seed

# Create storage link
php artisan storage:link

# Cache config
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Set Permissions
```bash
chmod -R 755 storage
chmod -R 755 bootstrap/cache
```

## ☁️ VPS/Cloud Server

### 1. Server Setup (Ubuntu 22.04)
```bash
# Update system
sudo apt update && sudo apt upgrade -y

# Install PHP 8.2
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.2 php8.2-fpm php8.2-mysql php8.2-xml php8.2-mbstring php8.2-curl php8.2-zip php8.2-bcmath php8.2-gd

# Install Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer

# Install Node.js
curl -fsSL https://deb.nodesource.com/setup_18.x | sudo -E bash -
sudo apt-get install -y nodejs

# Install Nginx
sudo apt install nginx

# Install MySQL
sudo apt install mysql-server
```

### 2. Database Setup
```bash
# Secure MySQL
sudo mysql_secure_installation

# Create database
sudo mysql -u root -p
CREATE DATABASE rental_blacklist;
CREATE USER 'rental_user'@'localhost' IDENTIFIED BY 'strong_password';
GRANT ALL PRIVILEGES ON rental_blacklist.* TO 'rental_user'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

### 3. Deploy Application
```bash
# Clone repository
cd /var/www
sudo git clone https://github.com/tupski/rental-blacklist.git
sudo chown -R www-data:www-data rental-blacklist
cd rental-blacklist

# Install dependencies
sudo -u www-data composer install --optimize-autoloader --no-dev
sudo -u www-data npm install
sudo -u www-data npm run build

# Setup environment
sudo -u www-data cp .env.example .env
sudo nano .env

# Generate key and migrate
sudo -u www-data php artisan key:generate
sudo -u www-data php artisan migrate --force
sudo -u www-data php artisan db:seed
sudo -u www-data php artisan storage:link

# Cache optimization
sudo -u www-data php artisan config:cache
sudo -u www-data php artisan route:cache
sudo -u www-data php artisan view:cache
```

### 4. Nginx Configuration
```nginx
# /etc/nginx/sites-available/rental-blacklist
server {
    listen 80;
    server_name yourdomain.com www.yourdomain.com;
    root /var/www/rental-blacklist/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

```bash
# Enable site
sudo ln -s /etc/nginx/sites-available/rental-blacklist /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### 5. SSL Certificate (Let's Encrypt)
```bash
# Install Certbot
sudo apt install certbot python3-certbot-nginx

# Get certificate
sudo certbot --nginx -d yourdomain.com -d www.yourdomain.com

# Auto-renewal
sudo crontab -e
# Add: 0 12 * * * /usr/bin/certbot renew --quiet
```

## 🐳 Docker Deployment

### 1. Dockerfile
```dockerfile
FROM php:8.2-fpm

# Install dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . /var/www

# Install dependencies
RUN composer install --optimize-autoloader --no-dev
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
```

### 2. Docker Compose
```yaml
version: '3.8'

services:
  app:
    build: .
    container_name: rental-blacklist-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
    networks:
      - rental-network

  webserver:
    image: nginx:alpine
    container_name: rental-blacklist-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx:/etc/nginx/conf.d
    networks:
      - rental-network

  db:
    image: mysql:8.0
    container_name: rental-blacklist-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: rental_blacklist
      MYSQL_ROOT_PASSWORD: root_password
      MYSQL_PASSWORD: user_password
      MYSQL_USER: rental_user
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - rental-network

volumes:
  dbdata:

networks:
  rental-network:
    driver: bridge
```

### 3. Deploy dengan Docker
```bash
# Build dan run
docker-compose up -d

# Setup application
docker-compose exec app php artisan key:generate
docker-compose exec app php artisan migrate --force
docker-compose exec app php artisan db:seed
docker-compose exec app php artisan storage:link
```

## 🔧 Post-Deployment

### 1. Security Checklist
- [ ] Set `APP_DEBUG=false`
- [ ] Set strong `APP_KEY`
- [ ] Configure proper file permissions
- [ ] Setup SSL certificate
- [ ] Configure firewall
- [ ] Setup backup system
- [ ] Monitor logs

### 2. Performance Optimization
```bash
# Cache optimization
php artisan config:cache
php artisan route:cache
php artisan view:cache

# OPcache (php.ini)
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=4000
opcache.revalidate_freq=60
```

### 3. Monitoring
```bash
# Setup log rotation
sudo nano /etc/logrotate.d/laravel

# Monitor disk space
df -h

# Monitor memory usage
free -m

# Check application logs
tail -f storage/logs/laravel.log
```

### 4. Backup Strategy
```bash
# Database backup script
#!/bin/bash
DATE=$(date +%Y%m%d_%H%M%S)
mysqldump -u rental_user -p rental_blacklist > backup_$DATE.sql
gzip backup_$DATE.sql

# File backup
tar -czf files_backup_$DATE.tar.gz storage/app/public

# Automated backup (crontab)
0 2 * * * /path/to/backup_script.sh
```

## 🚨 Troubleshooting

### Common Issues

**1. Permission Denied**
```bash
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 755 storage bootstrap/cache
```

**2. Storage Link Error**
```bash
php artisan storage:link --force
```

**3. Database Connection Error**
- Check database credentials in `.env`
- Verify database server is running
- Check firewall settings

**4. 500 Internal Server Error**
- Check `storage/logs/laravel.log`
- Verify file permissions
- Check PHP error logs

**5. Assets Not Loading**
```bash
npm run build
php artisan view:clear
```

## 📞 Support

Jika mengalami masalah deployment:
1. Check logs: `storage/logs/laravel.log`
2. Verify requirements
3. Check GitHub Issues
4. Create new issue dengan detail error

---

**Happy Deploying! 🚀**
