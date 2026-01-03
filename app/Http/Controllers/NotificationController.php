<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = Notifikasi::forUser($user->id)
            ->with(['animal', 'perkawinan'])
            ->orderBy('tanggal_kirim', 'desc');

        // Filter by status
        if ($request->has('filter')) {
            $filter = $request->filter;
            if (in_array($filter, ['belum_dibaca', 'sudah_dibaca', 'pending'])) {
                $query->where('status', $filter);
            }
        }

        $notifications = $query->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead($id)
    {
        $notification = Notifikasi::forUser(Auth::id())->findOrFail($id);
        $notification->update(['status' => 'sudah_dibaca']);

        return back()->with('success', 'Notifikasi ditandai sudah dibaca');
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        Notifikasi::forUser(Auth::id())
            ->unread()
            ->update(['status' => 'sudah_dibaca']);

        return back()->with('success', 'Semua notifikasi ditandai sudah dibaca');
    }

    /**
     * Get unread count (for AJAX)
     */
    public function getUnreadCount()
    {
        return response()->json([
            'count' => Notifikasi::forUser(Auth::id())->unread()->count()
        ]);
    }

    /**
     * Get latest langganan notification (for showing success message)
     */
    public function getLatestLanggananNotification()
    {
        $notification = Notifikasi::forUser(Auth::id())
            ->where('jenis_notifikasi', 'langganan')
            ->where('status', 'belum_dibaca')
            ->latest('created_at')
            ->first();

        if (!$notification) {
            return response()->json(null);
        }

        // Mark as read
        $notification->update(['status' => 'sudah_dibaca']);

        return response()->json($notification);
    }
}
