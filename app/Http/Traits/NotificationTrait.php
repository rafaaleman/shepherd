<?php
namespace App\Http\Traits;
use DateTime;
use App\Models\loveone;
use App\Models\notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

trait NotificationTrait {

    /**
     * 
     */
    public function createNotification($notification)
    {
        $notification['read'] = 0;
        return (notification::create($notification)) ? true : false;
    }

    // TODO: Ver la manera de ejecutar este metodo en toda la app para marcar el menu principal
    public function areNewNotifications($slug, $user_id)
    {
        if(empty($slug)) return 0; 
        
        $loveone = loveone::whereSlug($slug)->first();
        $from    = date('Y-m-d 00:00:00');
        $to      = new DateTime('tomorrow');
        $to      = $to->format('Y-m-d').' 23:59:00';


        $notifications = notification::where('user_id', $user_id)
                                        ->where('loveone_id', $loveone->id)
                                        ->whereBetween('event_date', [$from, $to])
                                        ->get()->count();
        Session::put('notifications',  $notifications);
    }
}