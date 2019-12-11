<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;

class NotifyAdmin extends Notification
{
    use Queueable;

    public $GlobalDatos;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(array $datos)
    {
        $this->GlobalDatos = $datos;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        //devolver la info a almacenar en el campo data
        return [
            'datos' => $this->GlobalDatos
        ];
    }

    public function toDatabase($notifiable){

        return [
            'datos' => $this->GlobalDatos
        ];
    }

    public function toBroadcast($notifiable)
    {
        //devolver la info a almacenar en el campo data
        return new BroadcastMessage([
            'datos' => $this->GlobalDatos
        ]);

        // return [
        //     'datos' => $this->GlobalDatos
        // ];
    }
}
