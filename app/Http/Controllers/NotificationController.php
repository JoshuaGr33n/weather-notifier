<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    protected $notificationService;
    protected $user;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
        $this->user = Auth::user(); 
    }

    public function pauseNotifications(Request $request)
    {
        $this->validatePauseDuration($request);

        $pauseDuration = (int) $request->pause_duration;
        $this->updateNotificationPause($this->user, now()->addHours($pauseDuration));

        return back()->with('status', 'Notifications paused successfully!');
    }

    public function restoreNotifications()
    {
        $this->updateNotificationPause($this->user, null); 

        return back()->with('status', 'Notifications restored successfully!');
    }

    public function showPauseNotificationsForm()
    {
        return inertia('Profile/Partials/PauseNotificationsForm', [
            'notification_paused_until' => $this->user->notification_paused_until,
        ]);
    }

    // Helper function to validate pause duration
    private function validatePauseDuration(Request $request)
    {
        $request->validate([
            'pause_duration' => 'required|integer|min:1',
        ]);
    }

    // Helper function to update the notification_paused_until field
    private function updateNotificationPause($user, $pauseUntil)
    {
        $user->update(['notification_paused_until' => $pauseUntil]);
    }
}
