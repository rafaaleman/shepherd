<?php
namespace App\Http\Traits;
use DateTime;
use App\Models\Tourjs;
use App\Models\loveone;
use App\Models\careteam;
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
    /**
     * Query notifications table using slug and user_id and put the notifications on session
     */
    public function areNewNotifications($slug, $user_id)
    {
        
        if(empty($slug)) return 0; 
        
        $loveone = loveone::whereSlug($slug)->first();
        if($loveone){

            $from    = new DateTime('-30 days');
            $from    = $from->format('Y-m-d').' 00:00:00';
            $to      = new DateTime('tomorrow');
            $to      = $to->format('Y-m-d').' 23:59:00';

            $notifications = notification::where('user_id', $user_id)
                                            ->where('loveone_id', $loveone->id)
                                            ->where('read', 0)
                                            ->whereBetween('event_date', [$from, $to])
                                            ->get()->count();
                                            // dd($user_id, $loveone->id);
            Session::put('notifications',  $notifications);
        }
    }

    /**
     * Query the users related to a lovedone and with specific permission
     */
    public function getLovedoneMembersToBeNotified($loveone_id, $permission)
    {
        $authorized_members = [];
        $members = careteam::where('loveone_id', $loveone_id)->where('status', 1)->get();
        foreach ($members as $member) {
            $permissions = unserialize($member->permissions);
        
            if($permissions[$permission] == 1){
                $authorized_members[] = $member->user_id;
            }
        }
        return $authorized_members;
    }

    /**
     * Return boolean if the user has been pass the tourJS
     */
    public function  alreadyReadTour($section_name)
    {
        return (Tourjs::where('user_id', Auth::user()->id)->where('section_name', $section_name)->first()) ? true : false;
    }
}