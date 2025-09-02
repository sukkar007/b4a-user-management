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

ParseClient::setServerURL('https://parseapi.back4app.com/parse', '/');

echo "🔍 البحث عن المستخدمين الموجودين...\n";

try {
    // البحث عن جميع المستخدمين
    $query = new Parse\ParseQuery('_User');
    $users = $query->find();
    
    echo "👥 عدد المستخدمين الموجودين: " . count($users) . "\n";
    
    foreach ($users as $user) {
        echo "- المستخدم: " . $user->get('username') . " | البريد: " . $user->get('email') . " | Admin: " . ($user->get('isAdmin') ? 'نعم' : 'لا') . "\n";
    }
    
} catch (Exception $e) {
    echo "❌ خطأ في البحث: " . $e->getMessage() . "\n";
}

echo "\n🔧 محاولة إنشاء/تحديث مستخدم الأدمن...\n";

try {
    // محاولة البحث عن المستخدم الموجود
    $query = new Parse\ParseQuery('_User');
    $query->equalTo('username', 'admin');
    $existingUser = $query->first();
    
    if ($existingUser) {
        echo "⚠️ المستخدم 'admin' موجود مسبقاً. سيتم تحديث صلاحياته...\n";
        
        // تحديث صلاحيات المستخدم الموجود
        $existingUser->set('isAdmin', true);
        $existingUser->set('role', 'admin');
        $existingUser->setArray('permissions', ['users', 'agencies', 'statistics', 'settings']);
        $existingUser->save();
        
        echo "✅ تم تحديث صلاحيات المستخدم بنجاح!\n";
        echo "🆔 معرف المستخدم: " . $existingUser->getObjectId() . "\n";
        
    } else {
        // إنشاء مستخدم جديد
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
        echo "🆔 معرف المستخدم: " . $user->getObjectId() . "\n";
    }
    
    echo "📧 البريد الإلكتروني: admin@b4a-demo.com\n";
    echo "🔑 كلمة المرور: admin123\n";
    echo "👤 اسم المستخدم: admin\n";
    
} catch (ParseException $e) {
    echo "❌ خطأ Parse: " . $e->getMessage() . "\n";
    echo "🔍 كود الخطأ: " . $e->getCode() . "\n";
} catch (Exception $e) {
    echo "❌ خطأ عام: " . $e->getMessage() . "\n";
}

echo "\n🧪 اختبار تسجيل الدخول...\n";

try {
    $testUser = ParseUser::logIn('admin', 'admin123');
    echo "✅ تسجيل الدخول نجح!\n";
    echo "👤 المستخدم: " . $testUser->get('username') . "\n";
    echo "📧 البريد: " . $testUser->get('email') . "\n";
    echo "🔐 Admin: " . ($testUser->get('isAdmin') ? 'نعم' : 'لا') . "\n";
    
    // تسجيل الخروج
    ParseUser::logOut();
    echo "🚪 تم تسجيل الخروج بنجاح\n";
    
} catch (Exception $e) {
    echo "❌ فشل تسجيل الدخول: " . $e->getMessage() . "\n";
}

echo "\n🚀 يمكنك الآن تسجيل الدخول إلى النظام!\n";
?>

