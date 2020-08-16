<?php

namespace YupForms\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use YupForms\YupFormData;

class YupFormSubmissionAccepted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create yupform from form submission
     *
     * @var YupFormData
     */
    public $yupFormData;

    /**
     * YupFormCreated constructor.
     *
     * @param YupFormData $yupFormData
     * @return void
     */
    public function __construct(YupFormData $yupFormData)
    {
        $this->yupFormData = $yupFormData;
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
