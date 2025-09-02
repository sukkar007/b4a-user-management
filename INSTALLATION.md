# 🚀 دليل التثبيت - نظام إدارة المستخدمين Laravel + Back4App

## 📋 متطلبات النظام

### البرامج المطلوبة:
- **PHP**: 8.1 أو أحدث
- **Composer**: أحدث إصدار
- **Git**: لتحميل المشروع
- **حساب Back4App**: نشط ومجاني

### التحقق من المتطلبات:
```bash
# التحقق من إصدار PHP
php -v

# التحقق من Composer
composer --version

# التحقق من Git
git --version
```

## 📥 تحميل المشروع

### 1. استنساخ المستودع:
```bash
git clone https://github.com/your-username/b4a-user-management.git
cd b4a-user-management
```

### 2. تثبيت التبعيات:
```bash
composer install
```

## ⚙️ إعداد البيئة

### 1. نسخ ملف البيئة:
```bash
cp .env.example .env
```

### 2. توليد مفتاح التطبيق:
```bash
php artisan key:generate
```

### 3. إعداد Back4App:

#### أ. إنشاء تطبيق جديد:
1. اذهب إلى [Back4App](https://www.back4app.com/)
2. سجل دخولك أو أنشئ حساب جديد
3. اضغط "Create a new app"
4. اختر "Build a Parse app"
5. أدخل اسم التطبيق (مثل: "User Management")

#### ب. الحصول على مفاتيح API:
1. في لوحة تحكم التطبيق، اذهب إلى **Settings > App Keys**
2. انسخ المفاتيح التالية:
   - **Application ID**
   - **REST API Key** 
   - **Master Key**

#### ج. تحديث ملف `.env`:
```env
PARSE_APP_ID=your_application_id_here
PARSE_REST_KEY=your_rest_api_key_here
PARSE_MASTER_KEY=your_master_key_here
PARSE_SERVER_URL=https://parseapi.back4app.com
```

## 🗄️ إعداد قاعدة البيانات

### 1. إعداد فئة المستخدم في Back4App:

#### أ. الوصول إلى Database Browser:
1. في لوحة تحكم Back4App، اذهب إلى **Database > Browser**
2. ستجد فئة `_User` موجودة بالفعل

#### ب. إضافة الحقول المطلوبة:
اضغط على **Add Column** وأضف الحقول التالية:

**الحقول الأساسية:**
- `role` (String) - الدور (admin/user)
- `fullName` (String) - الاسم الكامل
- `bio` (String) - النبذة الشخصية
- `avatar` (File) - الصورة الشخصية
- `cover` (File) - صورة الغلاف

**معلومات شخصية:**
- `gender` (String) - الجنس (male/female)
- `age` (Number) - العمر
- `country` (String) - البلد
- `city` (String) - المدينة
- `birthday` (Date) - تاريخ الميلاد

**الحالة المالية:**
- `coins` (Number) - العملات (افتراضي: 0)
- `diamonds` (Number) - الماس (افتراضي: 0)
- `userPoints` (Number) - النقاط (افتراضي: 0)

**حالة VIP:**
- `isVip` (Boolean) - حالة VIP عامة
- `normalVip` (Boolean) - VIP عادي
- `superVip` (Boolean) - VIP سوبر
- `diamondVip` (Boolean) - VIP ماسي
- `premiumLifeTime` (Boolean) - VIP مدى الحياة

**إعدادات الحساب:**
- `accountHidden` (Boolean) - حساب مخفي
- `emailVerified` (Boolean) - بريد مؤكد
- `accountDeleted` (Boolean) - حساب محذوف
- `lastOnline` (Date) - آخر ظهور

### 2. إنشاء مستخدم admin:

#### أ. عبر Back4App Console:
1. اذهب إلى **Database > Browser > _User**
2. اضغط **Add Row**
3. أدخل البيانات التالية:
   - `username`: admin
   - `password`: Password123!
   - `email`: admin@example.com
   - `role`: admin
   - `fullName`: المدير العام
   - `emailVerified`: true

#### ب. عبر Cloud Code (اختياري):
```javascript
// في Back4App Cloud Code
Parse.Cloud.define("createAdmin", async (request) => {
  const user = new Parse.User();
  user.set("username", "admin");
  user.set("password", "Password123!");
  user.set("email", "admin@example.com");
  user.set("role", "admin");
  user.set("fullName", "المدير العام");
  user.set("emailVerified", true);
  user.set("coins", 1000);
  user.set("diamonds", 100);
  user.set("userPoints", 500);
  
  try {
    await user.signUp();
    return "Admin user created successfully";
  } catch (error) {
    throw error;
  }
});
```

## 🚀 تشغيل النظام

### 1. مسح الكاش:
```bash
php artisan config:clear
php artisan cache:clear
```

### 2. تشغيل الخادم:
```bash
php artisan serve
```

### 3. الوصول للنظام:
- **الرابط**: http://localhost:8000
- **تسجيل الدخول**: http://localhost:8000/login
- **اسم المستخدم**: admin
- **كلمة المرور**: Password123!

## ✅ التحقق من التثبيت

### 1. اختبار تسجيل الدخول:
1. اذهب إلى http://localhost:8000/login
2. أدخل بيانات المدير
3. يجب أن تصل إلى لوحة التحكم

### 2. اختبار الميزات:
- **قائمة المستخدمين**: `/admin/users`
- **الإحصائيات**: `/admin/users-statistics`
- **العمليات المجمعة**: `/admin/bulk-actions`

## 🔧 استكشاف الأخطاء

### مشاكل شائعة وحلولها:

#### 1. خطأ "Parse error":
```bash
# تأكد من صحة مفاتيح Back4App في .env
# امسح الكاش
php artisan config:clear
```

#### 2. خطأ "Class not found":
```bash
# أعد تثبيت التبعيات
composer install
composer dump-autoload
```

#### 3. خطأ "Application key not set":
```bash
php artisan key:generate
```

#### 4. مشاكل تسجيل الدخول:
- تأكد من وجود مستخدم admin في Back4App
- تأكد من أن `role` = "admin"
- تأكد من صحة كلمة المرور

#### 5. مشاكل الاتصال بـ Back4App:
- تحقق من صحة المفاتيح في `.env`
- تأكد من الاتصال بالإنترنت
- تحقق من حالة خدمة Back4App

### فحص السجلات:
```bash
# عرض سجلات Laravel
tail -f storage/logs/laravel.log

# فحص إعدادات Parse
php artisan tinker
>>> config('app.parse_app_id')
```

## 🔒 إعدادات الأمان

### 1. في بيئة الإنتاج:
```env
APP_ENV=production
APP_DEBUG=false
```

### 2. حماية ملف `.env`:
```bash
# تأكد من أن .env في .gitignore
echo ".env" >> .gitignore
```

### 3. إعدادات Back4App للإنتاج:
1. فعل **Require revocable sessions**
2. فعل **Require authentication for all classes**
3. إعداد **Class Level Permissions** للفئات الحساسة

## 📚 الخطوات التالية

### 1. تخصيص النظام:
- تعديل الألوان في `resources/views/layouts/admin.blade.php`
- إضافة حقول جديدة حسب احتياجاتك
- تخصيص رسائل التنبيه

### 2. إضافة ميزات:
- نظام الإشعارات
- تصدير التقارير
- نظام الصلاحيات المتقدم

### 3. النشر:
- استخدام خدمات مثل Heroku أو DigitalOcean
- إعداد قاعدة بيانات للجلسات
- إعداد خادم ويب (Apache/Nginx)

## 🆘 الحصول على المساعدة

### الموارد المفيدة:
- [وثائق Laravel](https://laravel.com/docs)
- [وثائق Parse PHP SDK](https://docs.parseplatform.org/php/guide/)
- [وثائق Back4App](https://www.back4app.com/docs)

### الإبلاغ عن المشاكل:
إذا واجهت أي مشاكل، يرجى:
1. التحقق من السجلات
2. مراجعة هذا الدليل
3. البحث في الوثائق الرسمية
4. إنشاء Issue في المستودع

---

**🎉 تهانينا! لقد أكملت تثبيت نظام إدارة المستخدمين بنجاح! 🎉**

