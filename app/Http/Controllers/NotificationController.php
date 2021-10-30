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
        if($loveone){
            $from    = new DateTime('-30 days');
            $from    = $from->format('Y-m-d').' 00:00:00';
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
                $notification->nid = $n->id;
                $notification->read = $n->read;
                $notification->event_date = (date('Y-m-d', strtotime($n->event_date)) == date('Y-m-d')) ? 'Today' : date('M, d Y g:i a', strtotime($n->event_date));
                // $notification->event_date .= ' at ' . Carbon::parse($n->event_date)->format('g:i a');
                // $notification->event_date = date('M, d Y g:i a', strtotime($n->event_date));
                // dd(date('Y-m-d', strtotime($n->event_date)), date('Y-m-d'));

                if($n->table == 'events'){
                    $notification->title = 'You have a new event';
                    $notification->description = $notification->location;
                    $notification->icon = 'far fa-calendar-plus';
                    $notification->type = 'event';

                } else if($n->table == 'lockbox'){
                    $notification->title = 'A new file is available';
                    $notification->description = '';
                    $notification->type = 'lockbox';
                    $notification->icon = 'fas fa-file-medical';
                    
                } else if($n->table == 'medications'){
                    $notification->title = 'It\'s time for medication';
                    $notification->name = $notification->medicine . ' ' .$notification->dosage;
                    $notification->description = '';
                    $notification->type = 'medlist';
                    $notification->icon = 'fas fa-prescription-bottle-alt';
                    
                } else { 
                    $notification->description = '';
                    $notification->icon = 'far fa-calendar-alt';
                }
                $user_notifications[] = $notification;
            }

            // dd($user_notifications);
            return view('notifications.index', compact('user_notifications'));
        } else {
            return redirect()->route('home');
        }
        
    }

    /**
     * 
     */
    public function readNotification(Request $request)
    {
        notification::where('id', $request->id)->update(['read' => 1]);
        return response()->json(['success' => true]);
    }
}
