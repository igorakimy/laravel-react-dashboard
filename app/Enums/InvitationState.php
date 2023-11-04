<?php

namespace App\Enums;

enum InvitationState: string
{
    case PENDING = 'pending';
    case ACCEPTED = 'accepted';
    case DECLINED = 'declined';
    case REVOKED = 'revoked';
    case EXPIRED = 'expired';
}
