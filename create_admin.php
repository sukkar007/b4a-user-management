<?php

require_once 'vendor/autoload.php';

use Parse\ParseClient;
use Parse\ParseUser;
use Parse\ParseException;

// إعداد Parse Client
ParseClient::initialize(
    'dr1zqC6eOjj6k6gkszwfp6DzU1S4vwtzuWuawV0s', // Application ID
    'z6QeuDfuZuv58sP7uS5GxDkv8oVw0RaVwMQtrbaH', // REST API Key
    '1lL3Bvc9QWxV10xx3tzWodPGkKhgMLJUfX5HuTQD'  // Master Key
);

ParseClient::setServerURL('https://parseapi.back4app.com', 'parse');

try {
    // إنشاء مستخدم أدمن جديد
    $user = new ParseUser();
    $user->set('username', 'admin');
    $user->set('email', 'admin@b4a-demo.com');
    $user->set('password', 'admin123');
    
    // إضافة صلاحيات الأدمن
    $user->set('isAdmin', true);
    $user->set('role', 'admin');
    $user->setArray('permissions', ['users', 'agencies', 'statistics', 'settings']);
    
    // حفظ المستخدم
    $user->signUp();
    
    echo "✅ تم إنشاء مستخدم الأدمن بنجاح!\n";
    echo "📧 البريد الإلكتروني: admin@b4a-demo.com\n";
    echo "🔑 كلمة المرور: admin123\n";
    echo "🆔 معرف المستخدم: " . $user->getObjectId() . "\n";
    
} catch (ParseException $e) {
    if ($e->getCode() == 202) {
        echo "⚠️  المستخدم موجود مسبقاً. يمكنك استخدام البيانات التالية:\n";
        echo "📧 البريد الإلكتروني: admin@b4a-demo.com\n";
        echo "🔑 كلمة المرور: admin123\n";
    } else {
        echo "❌ خطأ في إنشاء المستخدم: " . $e->getMessage() . "\n";
        echo "🔍 كود الخطأ: " . $e->getCode() . "\n";
    }
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
}

// اختبار الاتصال بـ Back4App
try {
    echo "\n🔍 اختبار الاتصال بـ Back4App...\n";
    
    $query = new Parse\ParseQuery('_User');
    $query->limit(1);
    $results = $query->find();
    
    echo "✅ الاتصال بـ Back4App يعمل بشكل صحيح!\n";
    echo "👥 عدد المستخدمين في قاعدة البيانات: " . count($results) . "\n";
    
} catch (Exception $e) {
    echo "❌ خطأ في الاتصال بـ Back4App: " . $e->getMessage() . "\n";
}

echo "\n🚀 يمكنك الآن تسجيل الدخول إلى النظام!\n";
?>

