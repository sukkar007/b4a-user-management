<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Parse\ParseClient;
use Parse\ParseQuery;
use Parse\ParseObject;
use Parse\ParseException;
use Parse\ParseUser;
use Illuminate\Support\Facades\Log;

class AgencyController extends Controller
{
    public function __construct()
    {
        // تهيئة Parse Client
        ParseClient::initialize(
            env('PARSE_APP_ID'),
            env('PARSE_REST_KEY'),
            env('PARSE_MASTER_KEY')
        );
        ParseClient::setServerURL(env('PARSE_SERVER_URL'), '/parse');
    }

    /**
     * عرض صفحة إدارة الوكالات الرئيسية
     */
    public function index(Request $request)
    {
        try {
            // إحصائيات سريعة
            $stats = $this->getAgencyStats();
            
            // جلب الوكالات (المضيفين مع أعضائهم)
            $agencies = $this->getAgencies($request);
            
            return view('admin.agencies.index', compact('stats', 'agencies'));
            
        } catch (ParseException $e) {
            Log::error('Agency index error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في جلب بيانات الوكالات: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض تفاصيل وكالة محددة
     */
    public function show($hostId, Request $request)
    {
        try {
            // جلب بيانات المضيف
            $hostQuery = new ParseQuery('_User');
            $host = $hostQuery->get($hostId);
            
            // جلب أعضاء الوكالة
            $membersQuery = new ParseQuery('AgencyMember');
            $membersQuery->equalTo('host_id', $hostId);
            $membersQuery->includeKey('agent');
            $membersQuery->descending('total_points_earnings');
            
            // فلترة حسب الحالة
            if ($request->has('status') && $request->status != 'all') {
                $membersQuery->equalTo('client_status', $request->status);
            }
            
            // البحث
            if ($request->has('search') && !empty($request->search)) {
                $userQuery = new ParseQuery('_User');
                $userQuery->contains('username', $request->search);
                $membersQuery->matchesQuery('agent', $userQuery);
            }
            
            $members = $membersQuery->find();
            
            // جلب الدعوات المعلقة
            $invitationsQuery = new ParseQuery('AgencyInvitation');
            $invitationsQuery->equalTo('host_id', $hostId);
            $invitationsQuery->equalTo('invitation_status', 'pending');
            $invitationsQuery->includeKey('agent');
            $pendingInvitations = $invitationsQuery->find();
            
            // إحصائيات الوكالة
            $agencyStats = $this->getAgencyDetailedStats($hostId);
            
            return view('admin.agencies.show', compact('host', 'members', 'pendingInvitations', 'agencyStats'));
            
        } catch (ParseException $e) {
            Log::error('Agency show error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في جلب تفاصيل الوكالة: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض صفحة إدارة الدعوات
     */
    public function invitations(Request $request)
    {
        try {
            $query = new ParseQuery('AgencyInvitation');
            $query->includeKey('agent');
            $query->includeKey('host');
            $query->descending('createdAt');
            
            // فلترة حسب الحالة
            if ($request->has('status') && $request->status != 'all') {
                $query->equalTo('invitation_status', $request->status);
            }
            
            // البحث في اسم المستخدم
            if ($request->has('search') && !empty($request->search)) {
                $userQuery = new ParseQuery('_User');
                $userQuery->contains('username', $request->search);
                $query->matchesQuery('agent', $userQuery);
            }
            
            $invitations = $query->find();
            
            // إحصائيات الدعوات
            $invitationStats = $this->getInvitationStats();
            
            return view('admin.agencies.invitations', compact('invitations', 'invitationStats'));
            
        } catch (ParseException $e) {
            Log::error('Invitations error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في جلب الدعوات: ' . $e->getMessage()]);
        }
    }

    /**
     * قبول دعوة
     */
    public function acceptInvitation($invitationId)
    {
        try {
            // جلب الدعوة
            $query = new ParseQuery('AgencyInvitation');
            $invitation = $query->get($invitationId);
            
            if ($invitation->get('invitation_status') !== 'pending') {
                return back()->withErrors(['error' => 'هذه الدعوة تم التعامل معها مسبقاً']);
            }
            
            // تحديث حالة الدعوة
            $invitation->set('invitation_status', 'accepted');
            $invitation->save();
            
            // إنشاء عضوية جديدة
            $member = new ParseObject('AgencyMember');
            $member->set('agent_id', $invitation->get('agent_id'));
            $member->set('agent', $invitation->get('agent'));
            $member->set('host_id', $invitation->get('host_id'));
            $member->set('host', $invitation->get('host'));
            $member->set('client_status', 'joined');
            $member->set('level', 1);
            
            // تهيئة الإحصائيات بالصفر
            $member->set('live_duration', 0);
            $member->set('party_host_duration', 0);
            $member->set('party_crown_duration', 0);
            $member->set('matching_duration', 0);
            $member->set('total_points_earnings', 0);
            $member->set('live_earnings', 0);
            $member->set('match_earnings', 0);
            $member->set('party_earnings', 0);
            $member->set('game_gratuities', 0);
            $member->set('platform_reward', 0);
            $member->set('p_coin_earnings', 0);
            
            $member->save();
            
            return back()->with('success', 'تم قبول الدعوة وإضافة العضو بنجاح');
            
        } catch (ParseException $e) {
            Log::error('Accept invitation error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في قبول الدعوة: ' . $e->getMessage()]);
        }
    }

    /**
     * رفض دعوة
     */
    public function declineInvitation($invitationId)
    {
        try {
            $query = new ParseQuery('AgencyInvitation');
            $invitation = $query->get($invitationId);
            
            if ($invitation->get('invitation_status') !== 'pending') {
                return back()->withErrors(['error' => 'هذه الدعوة تم التعامل معها مسبقاً']);
            }
            
            $invitation->set('invitation_status', 'declined');
            $invitation->save();
            
            return back()->with('success', 'تم رفض الدعوة');
            
        } catch (ParseException $e) {
            Log::error('Decline invitation error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في رفض الدعوة: ' . $e->getMessage()]);
        }
    }

    /**
     * إزالة عضو من الوكالة
     */
    public function removeMember($memberId)
    {
        try {
            $query = new ParseQuery('AgencyMember');
            $member = $query->get($memberId);
            
            $member->set('client_status', 'left');
            $member->save();
            
            return back()->with('success', 'تم إزالة العضو من الوكالة');
            
        } catch (ParseException $e) {
            Log::error('Remove member error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في إزالة العضو: ' . $e->getMessage()]);
        }
    }

    /**
     * تحديث مستوى العضو
     */
    public function updateMemberLevel(Request $request, $memberId)
    {
        $request->validate([
            'level' => 'required|integer|min:1|max:10'
        ]);
        
        try {
            $query = new ParseQuery('AgencyMember');
            $member = $query->get($memberId);
            
            $member->set('level', $request->level);
            $member->save();
            
            return back()->with('success', 'تم تحديث مستوى العضو بنجاح');
            
        } catch (ParseException $e) {
            Log::error('Update member level error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في تحديث مستوى العضو: ' . $e->getMessage()]);
        }
    }

    /**
     * تحديث أرباح العضو
     */
    public function updateMemberEarnings(Request $request, $memberId)
    {
        $request->validate([
            'earning_type' => 'required|in:live_earnings,match_earnings,party_earnings,game_gratuities,platform_reward,p_coin_earnings',
            'amount' => 'required|integer',
            'action' => 'required|in:add,subtract'
        ]);
        
        try {
            $query = new ParseQuery('AgencyMember');
            $member = $query->get($memberId);
            
            $currentValue = $member->get($request->earning_type) ?? 0;
            $newValue = $request->action === 'add' 
                ? $currentValue + $request->amount 
                : max(0, $currentValue - $request->amount);
            
            $member->set($request->earning_type, $newValue);
            
            // تحديث إجمالي النقاط
            $this->updateTotalEarnings($member);
            
            $member->save();
            
            return back()->with('success', 'تم تحديث أرباح العضو بنجاح');
            
        } catch (ParseException $e) {
            Log::error('Update member earnings error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في تحديث أرباح العضو: ' . $e->getMessage()]);
        }
    }

    /**
     * عرض إحصائيات الوكالات
     */
    public function statistics()
    {
        try {
            $stats = $this->getDetailedAgencyStats();
            return view('admin.agencies.statistics', compact('stats'));
            
        } catch (ParseException $e) {
            Log::error('Agency statistics error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في جلب الإحصائيات: ' . $e->getMessage()]);
        }
    }

    /**
     * تصدير بيانات الوكالات
     */
    public function export(Request $request)
    {
        try {
            $format = $request->get('format', 'csv');
            
            if ($format === 'csv') {
                return $this->exportToCSV();
            } elseif ($format === 'json') {
                return $this->exportToJSON();
            }
            
            return back()->withErrors(['error' => 'صيغة التصدير غير مدعومة']);
            
        } catch (ParseException $e) {
            Log::error('Export error: ' . $e->getMessage());
            return back()->withErrors(['error' => 'خطأ في تصدير البيانات: ' . $e->getMessage()]);
        }
    }

    // ==================== Helper Methods ====================

    /**
     * جلب إحصائيات الوكالات العامة
     */
    private function getAgencyStats()
    {
        // عدد المضيفين (الوكالات)
        $hostsQuery = new ParseQuery('AgencyMember');
        $hostsQuery->select(['host_id']);
        $hostsQuery->equalTo('client_status', 'joined');
        $allMembers = $hostsQuery->find();
        
        $uniqueHosts = [];
        foreach ($allMembers as $member) {
            $hostId = $member->get('host_id');
            if (!in_array($hostId, $uniqueHosts)) {
                $uniqueHosts[] = $hostId;
            }
        }
        
        // إجمالي الأعضاء النشطين
        $activeMembersQuery = new ParseQuery('AgencyMember');
        $activeMembersQuery->equalTo('client_status', 'joined');
        $activeMembers = $activeMembersQuery->count();
        
        // إجمالي الدعوات المعلقة
        $pendingInvitationsQuery = new ParseQuery('AgencyInvitation');
        $pendingInvitationsQuery->equalTo('invitation_status', 'pending');
        $pendingInvitations = $pendingInvitationsQuery->count();
        
        // إجمالي الأرباح
        $totalEarnings = 0;
        foreach ($allMembers as $member) {
            $totalEarnings += $member->get('total_points_earnings') ?? 0;
        }
        
        return [
            'total_agencies' => count($uniqueHosts),
            'total_active_members' => $activeMembers,
            'pending_invitations' => $pendingInvitations,
            'total_earnings' => $totalEarnings
        ];
    }

    /**
     * جلب الوكالات مع الفلترة
     */
    private function getAgencies($request)
    {
        $query = new ParseQuery('AgencyMember');
        $query->equalTo('client_status', 'joined');
        $query->includeKey('host');
        $query->includeKey('agent');
        
        $members = $query->find();
        
        // تجميع الأعضاء حسب المضيف
        $agencies = [];
        foreach ($members as $member) {
            $hostId = $member->get('host_id');
            
            if (!isset($agencies[$hostId])) {
                $agencies[$hostId] = [
                    'host' => $member->get('host'),
                    'members' => [],
                    'total_earnings' => 0,
                    'member_count' => 0
                ];
            }
            
            $agencies[$hostId]['members'][] = $member;
            $agencies[$hostId]['total_earnings'] += $member->get('total_points_earnings') ?? 0;
            $agencies[$hostId]['member_count']++;
        }
        
        return array_values($agencies);
    }

    /**
     * جلب إحصائيات وكالة محددة
     */
    private function getAgencyDetailedStats($hostId)
    {
        $query = new ParseQuery('AgencyMember');
        $query->equalTo('host_id', $hostId);
        $query->equalTo('client_status', 'joined');
        $members = $query->find();
        
        $stats = [
            'total_members' => count($members),
            'total_earnings' => 0,
            'total_live_duration' => 0,
            'total_party_duration' => 0,
            'total_match_duration' => 0,
            'avg_level' => 0,
            'top_earners' => []
        ];
        
        $totalLevel = 0;
        $earningsData = [];
        
        foreach ($members as $member) {
            $earnings = $member->get('total_points_earnings') ?? 0;
            $stats['total_earnings'] += $earnings;
            $stats['total_live_duration'] += $member->get('live_duration') ?? 0;
            $stats['total_party_duration'] += $member->get('party_host_duration') ?? 0;
            $stats['total_match_duration'] += $member->get('matching_duration') ?? 0;
            $totalLevel += $member->get('level') ?? 1;
            
            $earningsData[] = [
                'member' => $member,
                'earnings' => $earnings
            ];
        }
        
        if (count($members) > 0) {
            $stats['avg_level'] = round($totalLevel / count($members), 1);
        }
        
        // ترتيب أفضل الأعضاء
        usort($earningsData, function($a, $b) {
            return $b['earnings'] - $a['earnings'];
        });
        
        $stats['top_earners'] = array_slice($earningsData, 0, 5);
        
        return $stats;
    }

    /**
     * جلب إحصائيات الدعوات
     */
    private function getInvitationStats()
    {
        $pendingQuery = new ParseQuery('AgencyInvitation');
        $pendingQuery->equalTo('invitation_status', 'pending');
        $pending = $pendingQuery->count();
        
        $acceptedQuery = new ParseQuery('AgencyInvitation');
        $acceptedQuery->equalTo('invitation_status', 'accepted');
        $accepted = $acceptedQuery->count();
        
        $declinedQuery = new ParseQuery('AgencyInvitation');
        $declinedQuery->equalTo('invitation_status', 'declined');
        $declined = $declinedQuery->count();
        
        return [
            'pending' => $pending,
            'accepted' => $accepted,
            'declined' => $declined,
            'total' => $pending + $accepted + $declined
        ];
    }

    /**
     * تحديث إجمالي الأرباح
     */
    private function updateTotalEarnings($member)
    {
        $total = 0;
        $total += $member->get('live_earnings') ?? 0;
        $total += $member->get('match_earnings') ?? 0;
        $total += $member->get('party_earnings') ?? 0;
        $total += $member->get('game_gratuities') ?? 0;
        $total += $member->get('platform_reward') ?? 0;
        $total += $member->get('p_coin_earnings') ?? 0;
        
        $member->set('total_points_earnings', $total);
    }

    /**
     * جلب إحصائيات مفصلة للوكالات
     */
    private function getDetailedAgencyStats()
    {
        // سيتم تنفيذها في المرحلة التالية
        return [];
    }

    /**
     * تصدير إلى CSV
     */
    private function exportToCSV()
    {
        // سيتم تنفيذها في المرحلة التالية
        return response()->json(['message' => 'CSV export coming soon']);
    }

    /**
     * تصدير إلى JSON
     */
    private function exportToJSON()
    {
        // سيتم تنفيذها في المرحلة التالية
        return response()->json(['message' => 'JSON export coming soon']);
    }
}

