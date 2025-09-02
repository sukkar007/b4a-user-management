# دليل الإصلاح السريع - نظام إدارة المستخدمين والوكالات

## 🚨 المشاكل الشائعة وحلولها

### 1. خطأ "This page is currently unavailable"

**السبب**: مشاكل في إعدادات Laravel الأساسية

**الحل**:
```bash
# 1. التأكد من وجود ملف .env
cp .env.example .env

# 2. توليد مفتاح التطبيق
php artisan key:generate

# 3. إنشاء مجلدات التخزين
mkdir -p storage/framework/{sessions,views,cache}
chmod -R 775 storage bootstrap/cache

# 4. تنظيف الكاش
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```

### 2. خطأ "View paths not found"

**السبب**: ملف `config/view.php` مفقود

**الحل**: إنشاء الملف مع المحتوى التالي:
```php
<?php
return [
    'paths' => [
        resource_path('views'),
    ],
    'compiled' => env(
        'VIEW_COMPILED_PATH',
        realpath(storage_path('framework/views'))
    ),
];
```

### 3. خطأ "Failed opening required autoload.php"

**السبب**: ملف `public/index.php` تالف

**الحل**: استبدال المحتوى بكود Laravel الصحيح:
```php
<?php
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Kernel::class);

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
```

### 4. مشاكل التبعيات

**الحل**:
```bash
# تحديث Composer
composer install --no-dev --optimize-autoloader

# تحديث NPM
npm install
npm run build

# إعادة تحميل autoloader
composer dump-autoload
```

## 🔧 إعدادات الإنتاج

### 1. متغيرات البيئة (.env)
```env
APP_NAME="نظام إدارة المستخدمين والوكالات"
APP_ENV=production
APP_KEY=base64:YOUR_GENERATED_KEY
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Back4App Settings
PARSE_APP_ID=dr1zqC6eOjj6k6gkszwfp6DzU1S4vwtzuWuawV0s
PARSE_REST_KEY=z6QeuDfuZuv58sP7uS5GxDkv8oVw0RaVwMQtrbaH
PARSE_MASTER_KEY=1lL3Bvc9QWxV10xx3tzWodPGkKhgMLJUfX5HuTQD
PARSE_SERVER_URL=https://parseapi.back4app.com/parse
```

### 2. أوامر التحسين
```bash
# تحسين للإنتاج
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize

# تنظيف الكاش عند التطوير
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

### 3. صلاحيات الملفات
```bash
# إعداد الصلاحيات الصحيحة
sudo chown -R www-data:www-data /path/to/project
sudo chmod -R 755 /path/to/project
sudo chmod -R 775 /path/to/project/storage
sudo chmod -R 775 /path/to/project/bootstrap/cache
```

## 🌐 إعداد خادم الويب

### Apache (.htaccess)
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### Nginx
```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /path/to/project/public;
    
    index index.php;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## 🔍 اختبار النظام

### 1. اختبار الاتصال بـ Back4App
```bash
# تشغيل اختبار سريع
php artisan tinker
```

```php
// في Tinker
use Parse\ParseClient;
ParseClient::initialize(env('PARSE_APP_ID'), env('PARSE_REST_KEY'), env('PARSE_MASTER_KEY'));
ParseClient::setServerURL(env('PARSE_SERVER_URL'));

// اختبار الاتصال
$query = new Parse\ParseQuery('_User');
$query->limit(1);
$results = $query->find();
echo "Connection successful!";
```

### 2. اختبار المسارات
```bash
# عرض جميع المسارات
php artisan route:list

# اختبار مسار محدد
curl http://localhost:8000/login
```

### 3. فحص السجلات
```bash
# عرض آخر الأخطاء
tail -f storage/logs/laravel.log

# فحص سجلات الخادم
tail -f /var/log/apache2/error.log  # Apache
tail -f /var/log/nginx/error.log    # Nginx
```

## 📱 اختبار الواجهة

### صفحات يجب اختبارها:
- ✅ `/login` - صفحة تسجيل الدخول
- ✅ `/admin/dashboard` - لوحة التحكم
- ✅ `/admin/users` - إدارة المستخدمين
- ✅ `/admin/agencies` - إدارة الوكالات
- ✅ `/admin/agencies/statistics` - إحصائيات الوكالات
- ✅ `/admin/agencies/invitations` - إدارة الدعوات

### بيانات اختبار:
```
Username: admin@example.com
Password: password123
```

## 🆘 الحصول على المساعدة

### سجلات مفيدة:
- `storage/logs/laravel.log` - سجلات Laravel
- `/var/log/apache2/error.log` - سجلات Apache
- `/var/log/nginx/error.log` - سجلات Nginx
- Browser Developer Tools - أخطاء JavaScript

### أوامر التشخيص:
```bash
# فحص إصدار PHP
php -v

# فحص extensions المثبتة
php -m

# فحص إعدادات Laravel
php artisan about

# فحص حالة التبعيات
composer diagnose
```

---

**ملاحظة**: هذا الدليل يغطي المشاكل الأكثر شيوعاً. للمشاكل المعقدة، راجع سجلات الأخطاء أو اتصل بالدعم التقني.

