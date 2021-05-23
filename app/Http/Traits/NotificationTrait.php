<?php
namespace App\Http\Traits;
use App\Models\notification;

trait NotificationTrait {

    /**
     * 
     */
    public function createNotification($notification)
    {
        $notification['read'] = 0;
        return (notification::create($notification)) ? true : false;
    }
}