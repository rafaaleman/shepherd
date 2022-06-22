<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Queue\SerializesModels;

class EventNotification extends Notification
{
    use Queueable, SerializesModels;
    public $event;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($event)
    {
        $this->event = $event;
        //dd($this->event);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
       // return (new MailMessage)->markdown('emails.sendNewEventMail')->with('event', $this->event);
        
    /*     ->view(
            'emails.sendNewEventMail', ['event' => $this->event]
        );*/
        return (new MailMessage)
                    ->line("You have a new task for your Loved One ".$this->event->loveone_name."")
                    ->line('Task called "'.$this->event->name.'" with location at "'.$this->event->location.'" on "'.date('M, d Y', strtotime($this->event->date)).'" at "'.date('h:i a' , strtotime($this->event->time)).'"')
                    ->action('View task', url('/'));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
