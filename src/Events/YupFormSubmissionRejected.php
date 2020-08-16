<?php

namespace YupForms\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class YupFormSubmissionRejected
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Submitted data in form
     *
     * @var
     */
    public $formData;

    /**
     * Request server data
     *
     * @var
     */
    public $serverData;

    /**
     * Error message. Reason for rejecting submission.
     *
     * @var
     */
    public $message;

    /**
     * Create a new event instance.
     *
     * YupFormRejected constructor.
     * @param $formData
     * @param $serverData
     * @param $messsage
     *
     * @return void
     */
    public function __construct($formData, $serverData, $messsage)
    {
        $this->formData = $formData;
        $this->serverData = $serverData;
        $this->message = $messsage;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('yupform');
    }
}
