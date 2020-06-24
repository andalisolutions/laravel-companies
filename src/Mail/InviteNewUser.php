<?php

declare(strict_types=1);

namespace Andali\Companies\Mail;

use Andali\Companies\Contracts\CompanyInvitation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InviteNewUser extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public CompanyInvitation $invitation;

    public function __construct(CompanyInvitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function build()
    {
        return $this
            ->subject('New Invitation!')
            ->markdown('companies::mails.invite-existing-user');
    }
}
