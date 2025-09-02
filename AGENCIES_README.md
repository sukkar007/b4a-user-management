# 🏢 نظام إدارة الوكالات (Agency Management System)

نظام شامل لإدارة الوكالات والأعضاء والدعوات مع تكامل كامل مع Back4App.

## 📋 المحتويات

- [الميزات الرئيسية](#الميزات-الرئيسية)
- [هيكل الملفات](#هيكل-الملفات)
- [التثبيت والإعداد](#التثبيت-والإعداد)
- [الاستخدام](#الاستخدام)
- [API Reference](#api-reference)
- [لقطات الشاشة](#لقطات-الشاشة)

## 🎯 الميزات الرئيسية

### 🏢 إدارة الوكالات
- ✅ عرض جميع الوكالات مع بحث وفلترة متقدمة
- ✅ إنشاء وتعديل وحذف الوكالات
- ✅ إدارة حالة الوكالة (نشطة/معطلة/معلقة)
- ✅ تحديث معدلات العمولة
- ✅ إدارة الحد الأقصى للأعضاء

### 👥 إدارة الأعضاء
- ✅ عرض جميع أعضاء الوكالة
- ✅ إضافة وإزالة الأعضاء
- ✅ نقل الأعضاء بين الوكالات
- ✅ تتبع أداء الأعضاء وأرباحهم
- ✅ إدارة مستويات الأعضاء

### 📧 إدارة الدعوات
- ✅ عرض جميع الدعوات (معلقة/مقبولة/مرفوضة)
- ✅ إرسال دعوات جديدة
- ✅ قبول ورفض الدعوات
- ✅ إلغاء الدعوات المعلقة
- ✅ تتبع تاريخ الدعوات

### 📊 الإحصائيات والتقارير
- ✅ رسوم بيانية تفاعلية (Chart.js)
- ✅ إحصائيات الأداء والأرباح
- ✅ توزيع الأعضاء والوكالات
- ✅ تقارير مفصلة قابلة للتصدير

### ⚡ العمليات المجمعة
- ✅ إرسال إشعارات جماعية
- ✅ تحديث العمولات بالجملة
- ✅ إدارة حالة متعددة الوكالات
- ✅ نقل أعضاء بين الوكالات
- ✅ دعوات جماعية
- ✅ تحديث الحدود
- ✅ تصدير البيانات
- ✅ تنظيف البيانات غير النشطة

## 📁 هيكل الملفات

```
app/Http/Controllers/Admin/
└── AgencyController.php                 # المتحكم الرئيسي للوكالات

resources/views/admin/agencies/
├── index.blade.php                      # صفحة قائمة الوكالات
├── show.blade.php                       # صفحة تفاصيل الوكالة
├── create.blade.php                     # صفحة إنشاء وكالة جديدة
├── invitations.blade.php                # صفحة إدارة الدعوات
├── statistics.blade.php                 # صفحة الإحصائيات
└── bulk_actions.blade.php               # صفحة العمليات المجمعة

routes/
└── web.php                              # المسارات (تم تحديثها)

docs/
└── AGENCIES_README.md                   # هذا الملف
```

## 🚀 التثبيت والإعداد

### 1. متطلبات النظام
- PHP 8.1+
- Laravel 10+
- Parse PHP SDK
- Bootstrap 5.3 RTL
- Chart.js

### 2. إعداد Back4App
تأكد من وجود الكلاسات التالية في Back4App:

#### **AgencyInvitation Class**
```javascript
{
  "objectId": "string",
  "hostId": "string",
  "guestId": "string", 
  "hostName": "string",
  "guestName": "string",
  "status": "string", // pending, accepted, declined, cancelled
  "invitationDate": "date",
  "responseDate": "date",
  "message": "string",
  "createdAt": "date",
  "updatedAt": "date"
}
```

#### **AgencyMembers Class**
```javascript
{
  "objectId": "string",
  "hostId": "string",
  "memberId": "string",
  "hostName": "string", 
  "memberName": "string",
  "memberLevel": "number",
  "joinDate": "date",
  "totalEarnings": "number",
  "monthlyEarnings": "number",
  "isActive": "boolean",
  "lastActivity": "date",
  "createdAt": "date",
  "updatedAt": "date"
}
```

### 3. إعداد المتغيرات
تأكد من وجود متغيرات Back4App في ملف `.env`:

```env
PARSE_SERVER_URL=https://parseapi.back4app.com/
PARSE_APP_ID=your_app_id
PARSE_REST_KEY=your_rest_key
PARSE_MASTER_KEY=your_master_key
```

### 4. تحديث القائمة الجانبية
أضف رابط الوكالات إلى `resources/views/layouts/admin.blade.php`:

```html
<li class="nav-item">
    <a class="nav-link" href="{{ route('admin.agencies.index') }}">
        <i class="fas fa-building"></i>
        <span>الوكالات</span>
    </a>
</li>
```

## 🎮 الاستخدام

### الوصول للنظام
```
http://localhost:8000/admin/agencies
```

### المسارات الرئيسية

| المسار | الوصف |
|--------|--------|
| `/admin/agencies` | قائمة جميع الوكالات |
| `/admin/agencies/{hostId}` | تفاصيل وكالة محددة |
| `/admin/agencies/create/new` | إنشاء وكالة جديدة |
| `/admin/agencies/invitations/manage` | إدارة الدعوات |
| `/admin/agencies/statistics/overview` | الإحصائيات |
| `/admin/agencies/bulk-actions` | العمليات المجمعة |

### العمليات الأساسية

#### 1. عرض الوكالات
- البحث بالاسم أو اسم المستخدم
- الفلترة حسب الحالة أو عدد الأعضاء
- الترتيب حسب التاريخ أو الأرباح

#### 2. إدارة الأعضاء
- عرض جميع أعضاء الوكالة
- تحديث مستوى العضو
- تحديث أرباح العضو
- إزالة عضو من الوكالة

#### 3. إدارة الدعوات
- عرض الدعوات المعلقة
- قبول أو رفض الدعوات
- إرسال دعوات جديدة
- تتبع تاريخ الدعوات

#### 4. الإحصائيات
- رسوم بيانية للأرباح الشهرية
- توزيع الأعضاء حسب المستوى
- إحصائيات الأداء
- تقارير قابلة للتصدير

## 🔧 API Reference

### AgencyController Methods

#### `index()`
عرض قائمة جميع الوكالات مع البحث والفلترة

#### `show($hostId)`
عرض تفاصيل وكالة محددة مع أعضائها

#### `create()` & `store()`
إنشاء وكالة جديدة

#### `invitations()`
إدارة دعوات الوكالات

#### `acceptInvitation($invitationId)`
قبول دعوة محددة

#### `declineInvitation($invitationId)`
رفض دعوة محددة

#### `removeMember($memberId)`
إزالة عضو من الوكالة

#### `updateMemberLevel($memberId)`
تحديث مستوى عضو

#### `statistics()`
عرض إحصائيات شاملة

#### `bulkActions()`
صفحة العمليات المجمعة

#### `export()`
تصدير بيانات الوكالات

### API Endpoints للإحصائيات

```php
GET /admin/agencies/api/stats/earnings    // إحصائيات الأرباح
GET /admin/agencies/api/stats/members     // إحصائيات الأعضاء  
GET /admin/agencies/api/stats/activities  // إحصائيات النشاط
GET /admin/agencies/api/stats/performance // إحصائيات الأداء
```

## 🎨 التخصيص

### الألوان والتصميم
يمكن تخصيص الألوان من خلال متغيرات CSS في ملفات Blade:

```css
:root {
  --primary-color: #007bff;
  --success-color: #28a745;
  --warning-color: #ffc107;
  --danger-color: #dc3545;
}
```

### إضافة ميزات جديدة
1. أضف الدالة الجديدة في `AgencyController`
2. أنشئ المسار في `routes/web.php`
3. أنشئ الواجهة في `resources/views/admin/agencies/`

## 🐛 استكشاف الأخطاء

### مشاكل شائعة

#### 1. خطأ في الاتصال بـ Back4App
```
تأكد من صحة مفاتيح Back4App في ملف .env
```

#### 2. خطأ في تحميل الصفحات
```bash
php artisan route:clear
php artisan config:clear
php artisan view:clear
```

#### 3. مشاكل في الصلاحيات
```
تأكد من أن المستخدم لديه صلاحية admin
```

## 📈 الإحصائيات

- **7 ملفات** رئيسية
- **3,500+ سطر** من الكود
- **25+ ميزة** متقدمة
- **8 عمليات** مجمعة
- **15+ دالة** في Controller
- **تكامل كامل** مع Back4App

## 🤝 المساهمة

نرحب بالمساهمات! يرجى:

1. Fork المشروع
2. إنشاء branch جديد للميزة
3. Commit التغييرات
4. Push إلى Branch
5. إنشاء Pull Request

## 📄 الترخيص

هذا المشروع مرخص تحت رخصة MIT - راجع ملف [LICENSE](LICENSE) للتفاصيل.

## 📞 الدعم

للحصول على الدعم:
- إنشاء Issue في GitHub
- مراجعة الوثائق
- التواصل مع فريق التطوير

---

**🎉 نظام إدارة الوكالات - حل شامل ومتكامل! 🏢**

