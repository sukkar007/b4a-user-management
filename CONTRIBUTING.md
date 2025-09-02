# دليل المساهمة

شكراً لاهتمامك بالمساهمة في مشروع نظام إدارة المستخدمين! نحن نرحب بجميع أنواع المساهمات.

## 🤝 كيفية المساهمة

### 1. الإبلاغ عن الأخطاء

إذا وجدت خطأ، يرجى إنشاء Issue جديد مع:
- وصف واضح للمشكلة
- خطوات إعادة إنتاج الخطأ
- البيئة المستخدمة (PHP version, Laravel version, etc.)
- لقطات شاشة إن أمكن

### 2. اقتراح ميزات جديدة

لاقتراح ميزة جديدة:
- تأكد من أن الميزة غير موجودة
- اشرح الحاجة للميزة
- قدم مثالاً على الاستخدام
- ناقش التنفيذ المقترح

### 3. المساهمة بالكود

#### خطوات المساهمة:

1. **Fork المشروع**
   ```bash
   # انقر على زر Fork في GitHub
   ```

2. **استنساخ المشروع**
   ```bash
   git clone https://github.com/your-username/b4a-user-management.git
   cd b4a-user-management
   ```

3. **إنشاء فرع جديد**
   ```bash
   git checkout -b feature/new-feature-name
   # أو
   git checkout -b fix/bug-description
   ```

4. **تثبيت التبعيات**
   ```bash
   composer install
   npm install
   ```

5. **إجراء التغييرات**
   - اتبع معايير الكود المذكورة أدناه
   - أضف تعليقات واضحة
   - اختبر التغييرات

6. **Commit التغييرات**
   ```bash
   git add .
   git commit -m "Add: وصف واضح للتغيير"
   ```

7. **Push إلى GitHub**
   ```bash
   git push origin feature/new-feature-name
   ```

8. **إنشاء Pull Request**
   - اذهب إلى GitHub
   - انقر على "New Pull Request"
   - اكتب وصفاً واضحاً للتغييرات

## 📝 معايير الكود

### PHP/Laravel
- اتبع PSR-12 coding standards
- استخدم أسماء متغيرات واضحة
- أضف تعليقات للدوال المعقدة
- استخدم Type Hints
- اتبع نمط Laravel conventions

```php
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ExampleController extends Controller
{
    /**
     * عرض قائمة الموارد
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        // كود واضح ومنظم
        return response()->json(['data' => $data]);
    }
}
```

### JavaScript
- استخدم ES6+ features
- أضف تعليقات للدوال
- استخدم أسماء متغيرات واضحة
- اتبع نمط consistent formatting

```javascript
/**
 * دالة لتحديث بيانات المستخدم
 * @param {number} userId - معرف المستخدم
 * @param {object} data - البيانات الجديدة
 * @returns {Promise} - وعد بالنتيجة
 */
async function updateUser(userId, data) {
    try {
        const response = await fetch(`/api/users/${userId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        return await response.json();
    } catch (error) {
        console.error('خطأ في تحديث المستخدم:', error);
        throw error;
    }
}
```

### CSS
- استخدم BEM methodology
- اكتب CSS منظم ومعلق
- استخدم متغيرات CSS
- اتبع mobile-first approach

```css
/* مكون البطاقة */
.card {
    border-radius: 10px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    transition: transform 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.card__header {
    padding: 1rem;
    border-bottom: 1px solid #eee;
}

.card__title {
    margin: 0;
    font-size: 1.25rem;
    font-weight: 600;
}
```

### Blade Templates
- استخدم تخطيط منظم
- أضف تعليقات للأقسام
- اتبع Laravel Blade best practices

```blade
{{-- قسم المحتوى الرئيسي --}}
@section('content')
<div class="container-fluid">
    {{-- عنوان الصفحة --}}
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <h1 class="h3 mb-0 text-gray-800">{{ $title }}</h1>
    </div>
    
    {{-- محتوى الصفحة --}}
    <div class="row">
        @foreach($items as $item)
            @include('partials.item-card', ['item' => $item])
        @endforeach
    </div>
</div>
@endsection
```

## 🧪 الاختبار

### قبل إرسال Pull Request:

1. **اختبر الوظائف الأساسية**
   - تسجيل الدخول/الخروج
   - عرض قائمة المستخدمين
   - إضافة/تعديل/حذف المستخدمين
   - البحث والفلترة

2. **اختبر على متصفحات مختلفة**
   - Chrome
   - Firefox
   - Safari
   - Edge

3. **اختبر الاستجابة**
   - Desktop
   - Tablet
   - Mobile

4. **تحقق من الأخطاء**
   ```bash
   # فحص أخطاء PHP
   php artisan config:clear
   php artisan cache:clear
   
   # فحص JavaScript console
   # تأكد من عدم وجود أخطاء في console
   ```

## 📋 قائمة المراجعة

قبل إرسال Pull Request، تأكد من:

- [ ] الكود يتبع معايير المشروع
- [ ] تم اختبار التغييرات
- [ ] تم إضافة تعليقات واضحة
- [ ] تم تحديث الوثائق إن لزم الأمر
- [ ] لا توجد أخطاء في console
- [ ] التصميم متجاوب
- [ ] يعمل مع RTL
- [ ] متوافق مع المتصفحات الرئيسية

## 🎯 أولويات المساهمة

نحن نبحث عن مساهمات في:

### عالية الأولوية
- إصلاح الأخطاء
- تحسين الأداء
- تحسين الأمان
- تحسين تجربة المستخدم

### متوسطة الأولوية
- ميزات جديدة
- تحسين التصميم
- إضافة اختبارات
- تحسين الوثائق

### منخفضة الأولوية
- تحسينات تجميلية
- إعادة تنظيم الكود
- تحسين التعليقات

## 🌟 أنواع المساهمات المرحب بها

### 🐛 إصلاح الأخطاء
- أخطاء في الوظائف
- مشاكل في التصميم
- مشاكل في الأداء

### ✨ ميزات جديدة
- تحسينات على إدارة المستخدمين
- ميزات إحصائيات جديدة
- تحسينات على العمليات المجمعة
- ميزات أمان إضافية

### 📚 تحسين الوثائق
- إضافة أمثلة
- توضيح التعليمات
- ترجمة المحتوى
- إضافة لقطات شاشة

### 🎨 تحسينات التصميم
- تحسين UI/UX
- إضافة رسوم متحركة
- تحسين الألوان
- تحسين الاستجابة

### ⚡ تحسين الأداء
- تحسين استعلامات قاعدة البيانات
- تحسين JavaScript
- تحسين CSS
- تحسين الصور

## 💬 التواصل

### للأسئلة والمناقشات:
- افتح Issue للمناقشة
- استخدم Discussions في GitHub
- تواصل عبر البريد الإلكتروني

### للمساعدة:
- راجع الوثائق أولاً
- ابحث في Issues الموجودة
- اسأل في المجتمع

## 🏆 الاعتراف بالمساهمين

جميع المساهمين سيتم ذكرهم في:
- ملف README
- قسم Contributors في GitHub
- ملف CHANGELOG

## 📄 الرخصة

بمساهمتك في هذا المشروع، فإنك توافق على أن مساهمتك ستكون مرخصة تحت نفس رخصة MIT المستخدمة في المشروع.

---

**شكراً لك على اهتمامك بالمساهمة! 🙏**

كل مساهمة، مهما كانت صغيرة، تساعد في تحسين المشروع وتفيد المجتمع.

