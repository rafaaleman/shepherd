<?php

namespace App\Http\Controllers;

use DateTime;
use Carbon\Carbon;
use App\Models\loveone;
use App\Models\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Traits\NotificationTrait;

class NotificationController extends Controller
{
    use NotificationTrait;


    /**
     * 
     */
    public function index(Request $request)
    {
        $this->areNewNotifications($request->slug, Auth::user()->id);

        $loveone = loveone::whereSlug($request->slug)->first();
        $from    = date('Y-m-d 00:00:00');
        $to      = new DateTime('tomorrow');
        $to      = $to->format('Y-m-d').' 23:59:00';


        $notifications = notification::where('user_id', Auth::user()->id)
                                        ->where('loveone_id', $loveone->id)
                                        ->whereBetween('event_date', [$from, $to])
                                        ->orderBy('event_date', 'desc')
                                        ->get();
        
        $user_notifications = [];
        foreach ($notifications as $n) {
            $notification = DB::table($n->table)->where('id', $n->table_id)->first();
            $notification->type = $n->table;
            $notification->read = $n->read;
            $notification->event_date = (date('Y-m-d', strtotime($n->event_date)) == date('Y-m-d')) ? 'Today' : 'Tomorrow';
            $notification->event_date .= ' at ' . Carbon::parse($n->event_date)->format('g:i a');
            // dd(date('Y-m-d', strtotime($n->event_date)), date('Y-m-d'));

            if($n->table == 'events'){
                $notification->description = $notification->location;
                $notification->icon = 'far fa-calendar-plus';

            } else if($n->table == 'lockbox'){
                $notification->description = '';
                $notification->icon = 'fas fa-file-medical';
                
            } else { 
                $notification->description = '';
                $notification->icon = 'far fa-calendar-alt';
            }
            $user_notifications[] = $notification;
        }

        // dd($user_notifications);
        return view('notifications.index', compact('user_notifications'));
    }

    
}
