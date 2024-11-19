<?php

namespace App\Notifications;

use App\Http\Middleware\applicant;
use App\Models\applicants;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicantAccountCreation extends Notification
{
    use Queueable;

    protected $applicant;

    /**
     * Create a new notification instance.
     */
    public function __construct(applicants $applicant)
    {
        //
        $this->applicant = $applicant;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Application Status Updated')
            ->greeting('Hello ' . $this->applicant->name . '!')
            ->line('Your application status has been updated, please check your account for full details:')
            ->action('Login to Your Account', url('/login'))
            ->line('Thank you for being part of our platform!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
