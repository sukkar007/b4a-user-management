<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Parse\ParseQuery;
use Parse\ParseUser;
use Parse\ParseException;
use Parse\ParseFile;

class UserController extends Controller
{
    /**
     * عرض قائمة المستخدمين
     */
    public function index(Request $request)
    {
        try {
            // إنشاء استعلام للمستخدمين
            $query = new ParseQuery("_User");
            
            // إعداد الترتيب (الأحدث أولاً)
            $query->descending("createdAt");
            
            // إعداد عدد النتائج لكل صفحة
            $perPage = $request->get('per_page', 20);
            $page = $request->get('page', 1);
            $skip = ($page - 1) * $perPage;
            
            $query->limit($perPage);
            $query->skip($skip);
            
            // تطبيق الفلاتر إذا وجدت
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                // البحث في اسم المستخدم أو البريد الإلكتروني أو الاسم الكامل
                $query->matches("username", $searchTerm, "i");
            }
            
            if ($request->has('gender') && !empty($request->gender)) {
                $query->equalTo("gender", $request->gender);
            }
            
            if ($request->has('role') && !empty($request->role)) {
                $query->equalTo("role", $request->role);
            }
            
            if ($request->has('vip_status')) {
                if ($request->vip_status === 'vip') {
                    // المستخدمون الذين لديهم أي نوع من VIP
                    $query->exists("normal_vip");
                } elseif ($request->vip_status === 'regular') {
                    // المستخدمون العاديون
                    $query->doesNotExist("normal_vip");
                }
            }
            
            // تنفيذ الاستعلام
            $users = $query->find(true); // استخدام Master Key
            
            // حساب العدد الإجمالي للمستخدمين
            $totalQuery = new ParseQuery("_User");
            if ($request->has('search') && !empty($request->search)) {
                $totalQuery->matches("username", $request->search, "i");
            }
            if ($request->has('gender') && !empty($request->gender)) {
                $totalQuery->equalTo("gender", $request->gender);
            }
            if ($request->has('role') && !empty($request->role)) {
                $totalQuery->equalTo("role", $request->role);
            }
            
            $totalUsers = $totalQuery->count(true);
            
            // تحويل البيانات إلى مصفوفة قابلة للاستخدام
            $usersData = [];
            foreach ($users as $user) {
                $usersData[] = $this->formatUserData($user);
            }
            
            // حساب معلومات الترقيم
            $totalPages = ceil($totalUsers / $perPage);
            $hasNextPage = $page < $totalPages;
            $hasPrevPage = $page > 1;
            
            return view('admin.users.index', compact(
                'usersData', 
                'totalUsers', 
                'page', 
                'totalPages', 
                'hasNextPage', 
                'hasPrevPage',
                'perPage'
            ));
            
        } catch (ParseException $e) {
            return back()->with('error', 'خطأ في جلب بيانات المستخدمين: ' . $e->getMessage());
        }
    }
    
    /**
     * عرض تفاصيل مستخدم محدد
     */
    public function show($id)
    {
        try {
            $query = new ParseQuery("_User");
            $user = $query->get($id, true);
            
            $userData = $this->formatUserData($user, true); // مع التفاصيل الكاملة
            
            return view('admin.users.show', compact('userData'));
            
        } catch (ParseException $e) {
            return back()->with('error', 'لم يتم العثور على المستخدم: ' . $e->getMessage());
        }
    }
    
    /**
     * عرض نموذج تعديل المستخدم
     */
    public function edit($id)
    {
        try {
            $query = new ParseQuery("_User");
            $user = $query->get($id, true);
            
            $userData = $this->formatUserData($user, true);
            
            return view('admin.users.edit', compact('userData'));
            
        } catch (ParseException $e) {
            return back()->with('error', 'لم يتم العثور على المستخدم: ' . $e->getMessage());
        }
    }
    
    /**
     * تحديث بيانات المستخدم
     */
    public function update(Request $request, $id)
    {
        try {
            $query = new ParseQuery("_User");
            $user = $query->get($id, true);
            
            // التحقق من صحة البيانات
            $validatedData = $request->validate([
                'name' => 'sometimes|string|max:255',
                'email' => 'sometimes|email|max:255',
                'bio' => 'sometimes|string|max:500',
                'role' => 'sometimes|in:user,admin',
                'gender' => 'sometimes|in:male,female',
                'age' => 'sometimes|integer|min:18|max:100',
                'city' => 'sometimes|string|max:100',
                'country' => 'sometimes|string|max:100',
            ]);
            
            // تحديث البيانات
            foreach ($validatedData as $key => $value) {
                $user->set($key, $value);
            }
            
            // حفظ التغييرات
            $user->save(true); // استخدام Master Key
            
            return redirect()->route('admin.users.show', $id)
                           ->with('success', 'تم تحديث بيانات المستخدم بنجاح');
            
        } catch (ParseException $e) {
            return back()->with('error', 'خطأ في تحديث البيانات: ' . $e->getMessage())
                         ->withInput();
        }
    }
    
    /**
     * حذف المستخدم (تعطيل الحساب)
     */
    public function destroy($id)
    {
        try {
            $query = new ParseQuery("_User");
            $user = $query->get($id, true);
            
            // بدلاً من الحذف الفعلي، نقوم بتعطيل الحساب
            $user->set('accountDeleted', true);
            $user->set('accountDeletedReason', 'تم حذفه بواسطة المدير');
            $user->save(true);
            
            return redirect()->route('admin.users.index')
                           ->with('success', 'تم تعطيل حساب المستخدم بنجاح');
            
        } catch (ParseException $e) {
            return back()->with('error', 'خطأ في حذف المستخدم: ' . $e->getMessage());
        }
    }
    
    /**
     * استعادة المستخدم المحذوف
     */
    public function restore($id)
    {
        try {
            $query = new ParseQuery("_User");
            $user = $query->get($id, true);
            
            $user->set('accountDeleted', false);
            $user->unset('accountDeletedReason');
            $user->save(true);
            
            return redirect()->route('admin.users.show', $id)
                           ->with('success', 'تم استعادة حساب المستخدم بنجاح');
            
        } catch (ParseException $e) {
            return back()->with('error', 'خطأ في استعادة المستخدم: ' . $e->getMessage());
        }
    }
    
    /**
     * تحديث حالة VIP للمستخدم
     */
    public function updateVipStatus(Request $request, $id)
    {
        try {
            $query = new ParseQuery("_User");
            $user = $query->get($id, true);
            
            $vipType = $request->input('vip_type');
            $duration = $request->input('duration', 30); // افتراضي 30 يوم
            
            $expiryDate = new \DateTime();
            $expiryDate->add(new \DateInterval('P' . $duration . 'D'));
            
            switch ($vipType) {
                case 'normal':
                    $user->set('normal_vip', $expiryDate);
                    break;
                case 'super':
                    $user->set('super_vip', $expiryDate);
                    break;
                case 'diamond':
                    $user->set('diamond_vip', $expiryDate);
                    break;
                case 'remove':
                    $user->unset('normal_vip');
                    $user->unset('super_vip');
                    $user->unset('diamond_vip');
                    break;
            }
            
            $user->save(true);
            
            return redirect()->route('admin.users.show', $id)
                           ->with('success', 'تم تحديث حالة VIP بنجاح');
            
        } catch (ParseException $e) {
            return back()->with('error', 'خطأ في تحديث حالة VIP: ' . $e->getMessage());
        }
    }
    
    /**
     * تنسيق بيانات المستخدم للعرض
     */
    private function formatUserData($parseUser, $detailed = false)
    {
        $data = [
            'id' => $parseUser->getObjectId(),
            'username' => $parseUser->get('username'),
            'email' => $parseUser->get('email'),
            'name' => $parseUser->get('name') ?? $parseUser->get('first_name') . ' ' . $parseUser->get('last_name'),
            'age' => $parseUser->get('age'),
            'gender' => $parseUser->get('gender'),
            'role' => $parseUser->get('role') ?? 'user',
            'created_at' => $parseUser->getCreatedAt(),
            'updated_at' => $parseUser->getUpdatedAt(),
            'last_online' => $parseUser->get('lastOnline'),
            'account_deleted' => $parseUser->get('accountDeleted') ?? false,
            'email_verified' => $parseUser->get('emailVerified') ?? false,
        ];
        
        // إضافة معلومات VIP
        $data['is_vip'] = false;
        $data['vip_type'] = 'regular';
        $data['vip_expiry'] = null;
        
        if ($parseUser->get('diamond_vip')) {
            $data['is_vip'] = true;
            $data['vip_type'] = 'diamond';
            $data['vip_expiry'] = $parseUser->get('diamond_vip');
        } elseif ($parseUser->get('super_vip')) {
            $data['is_vip'] = true;
            $data['vip_type'] = 'super';
            $data['vip_expiry'] = $parseUser->get('super_vip');
        } elseif ($parseUser->get('normal_vip')) {
            $data['is_vip'] = true;
            $data['vip_type'] = 'normal';
            $data['vip_expiry'] = $parseUser->get('normal_vip');
        }
        
        // إضافة الصور
        if ($parseUser->get('avatar')) {
            $data['avatar_url'] = $parseUser->get('avatar')->getURL();
        }
        
        if ($parseUser->get('cover')) {
            $data['cover_url'] = $parseUser->get('cover')->getURL();
        }
        
        // إضافة التفاصيل الإضافية إذا طُلبت
        if ($detailed) {
            $data = array_merge($data, [
                'bio' => $parseUser->get('bio'),
                'city' => $parseUser->get('city'),
                'country' => $parseUser->get('country'),
                'phone_number' => $parseUser->get('phone_number'),
                'popularity' => $parseUser->get('popularity') ?? 0,
                'coins' => $parseUser->get('credit') ?? 0,
                'diamonds' => $parseUser->get('diamonds') ?? 0,
                'followers' => count($parseUser->get('followers') ?? []),
                'following' => count($parseUser->get('following') ?? []),
                'user_points' => $parseUser->get('userPoints') ?? 0,
                'gifts_amount' => $parseUser->get('received_gifts_amount') ?? 0,
                'account_hidden' => $parseUser->get('account_hidden') ?? false,
                'photo_verified' => $parseUser->get('photo_verified') ?? false,
            ]);
        }
        
        return $data;
    }
    
    /**
     * إحصائيات المستخدمين
     */
    public function stats()
    {
        try {
            $totalQuery = new ParseQuery("_User");
            $totalUsers = $totalQuery->count(true);
            
            $activeQuery = new ParseQuery("_User");
            $activeQuery->equalTo('accountDeleted', false);
            $activeUsers = $activeQuery->count(true);
            
            $vipQuery = new ParseQuery("_User");
            $vipQuery->exists('normal_vip');
            $vipUsers = $vipQuery->count(true);
            
            $maleQuery = new ParseQuery("_User");
            $maleQuery->equalTo('gender', 'male');
            $maleUsers = $maleQuery->count(true);
            
            $femaleQuery = new ParseQuery("_User");
            $femaleQuery->equalTo('gender', 'female');
            $femaleUsers = $femaleQuery->count(true);
            
            $stats = [
                'total_users' => $totalUsers,
                'active_users' => $activeUsers,
                'deleted_users' => $totalUsers - $activeUsers,
                'vip_users' => $vipUsers,
                'regular_users' => $totalUsers - $vipUsers,
                'male_users' => $maleUsers,
                'female_users' => $femaleUsers,
            ];
            
            return response()->json($stats);
            
        } catch (ParseException $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}

