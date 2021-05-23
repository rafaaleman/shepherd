<?php

namespace App\Http\Controllers;

use DateTime;
use App\Models\loveone;
use App\Models\notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * 
     */
    public function index(Request $request)
    {
        $loveone = loveone::whereSlug($request->slug)->first();
        $from    = date('Y-m-d 00:00:00');
        $to      = new DateTime('tomorrow');
        $to      = $to->format('Y-m-d').' 23:59:00';


        $notifications = notification::where('user_id', Auth::user()->id)
                                        ->where('loveone_id', $loveone->id)
                                        ->whereBetween('event_date', [$from, $to])
                                        ->get();
        
        $user_notifications = [];
        foreach ($notifications as $n) {
            $notification = DB::table($n->table)->where('id', $n->table_id)->first();
            $notification->type = $n->table;
            $notification->read = $n->read;
            $user_notifications[] = $notification;
        }

        // dd($user_notifications);
        return view('notifications.index', compact('user_notifications'));
    }
}
