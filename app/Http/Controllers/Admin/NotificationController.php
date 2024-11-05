<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $notifications = Notification::with('user')
            ->orderBy('id', 'DESC')
            ->get();
        return view('admin.pages.notifications.index', compact('notifications'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $users = User::all();
        return view('admin.pages.notifications.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            // Validate the request
            $request->validate([
                'title' => 'required|string|max:255',
                'message' => 'required|string',
                'notification_for' => 'required|in:all,specific',
                'user_id' => $request->notification_for == 'specific' ? 'required|exists:users,id' : 'nullable'
            ]);

            // Create notification
            $notification = new Notification();
            $notification->title = $request->title;
            $notification->message = $request->message;
            $notification->user_id = $request->notification_for === 'specific' ? $request->user_id : null;
            $notification->is_read = 0;
            $notification->save();
            $output = ['success' => true, 'msg' => 'Notification created successfully!'];
            return redirect()->back()
                ->with('status', $output);

        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
            return redirect()->back()
                ->with('status', $output)
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $notification = Notification::findOrFail($id);
            $notification->delete();

            $output = ['success' => true, 'msg' => 'Notification deleted successfully!'];
            return redirect()->route('admin.notifications.index')
                ->with('status', $output);

        } catch (\Exception $e) {
            $output = ['success' => false, 'msg' => $e->getMessage()];
            return redirect()->route('admin.notifications.index')
                ->with('status', $output);
        }
    }

    // For regular users notifications code

    public function fetchNotifications(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // Fetch notifications specific to the user or global notifications
            $notifications = Notification::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhereNull('user_id'); // Include global notifications
                })
                ->with(['userNotifications' => function($query) use ($userId) {
                    $query->where('user_id', $userId); // Check if the user has read the notification
                }])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function($notification) use ($userId) {
                    // Add a flag to show if the notification is read or unread
                    $notification->is_read = $notification->userNotifications->isNotEmpty();
                    return $notification;
                });

            return response()->json(['notifications' => $notifications]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching notifications',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function unreadCount(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            $count = Notification::where(function($query) use ($userId) {
                $query->where('user_id', $userId)
                    ->orWhereNull('user_id'); // Include global notifications
            })
                ->whereDoesntHave('userNotifications', function($query) use ($userId) {
                    $query->where('user_id', $userId)
                        ->where('is_read', true); // Exclude already read notifications
                })
                ->count();

            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error fetching unread count',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function markAllAsRead(Request $request)
    {
        try {
            $userId = $request->input('user_id');

            // Mark user-specific notifications as read
            UserNotification::where('user_id', $userId)
                ->where('is_read', false)
                ->update(['is_read' => true]);

            // For global notifications, add user-specific read entries if they don't exist
            $globalNotifications = Notification::whereNull('user_id')
                ->whereDoesntHave('userNotifications', function($query) use ($userId) {
                    $query->where('user_id', $userId)->where('is_read', true);
                })
                ->get();

            foreach ($globalNotifications as $notification) {
                UserNotification::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'notification_id' => $notification->id,
                    ],
                    [
                        'is_read' => true,
                    ]
                );
            }

            return response()->json(['message' => 'All notifications marked as read']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error marking all as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function markAsRead(Request $request, $id)
    {
        try {
            $userId = $request->input('user_id');
            $notification = Notification::findOrFail($id);

            if ($notification->user_id === null) {
                // It's a global notification, create a user-specific read entry if not already marked as read
                UserNotification::firstOrCreate(
                    [
                        'user_id' => $userId,
                        'notification_id' => $notification->id,
                    ],
                    [
                        'is_read' => true,
                    ]
                );
            } else {
                // It's a user-specific notification, ensure a read entry is created or updated if it already exists
                UserNotification::updateOrCreate(
                    [
                        'user_id' => $userId,
                        'notification_id' => $notification->id,
                    ],
                    [
                        'is_read' => true,
                    ]
                );
            }

            return response()->json(['message' => 'Notification marked as read']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error marking as read',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
