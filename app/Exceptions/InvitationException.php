<?php

namespace App\Exceptions;

use App\Enums\ExceptionCode;

final class InvitationException extends AppException
{
    public static function invitationAlreadyAccepted(): self
    {
        return self::new(
            ExceptionCode::INVITATION_ALREADY_ACCEPTED,
            "invitation already accepted",
        );
    }

    public static function invitationAlreadyExpired(): self
    {
        return self::new(
            ExceptionCode::INVITATION_ALREADY_EXPIRED,
            "invitation already expired",
        );
    }

    public static function invitationAlreadyRevoked(): self
    {
        return self::new(
            ExceptionCode::INVITATION_ALREADY_REVOKED,
            "invitation already revoked",
        );
    }

    public static function userAlreadyExistsInSystem(): self
    {
        return self::new(
            ExceptionCode::USER_ALREADY_EXISTS,
            "invitation could not be sent, because user already exist in system",
        );
    }

    public static function invitationCanNotBeRevoked(): self
    {
        return self::new(
            ExceptionCode::INVITATION_CAN_NOT_BE_REVOKED,
            "invitation already revoked",
        );
    }

    public static function invitationNotFound(): self
    {
        return self::new(
            ExceptionCode::INVITATION_NOT_FOUND,
            "invitation not found",
        );
    }
}
