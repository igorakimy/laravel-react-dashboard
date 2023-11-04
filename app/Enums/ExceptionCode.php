<?php

namespace App\Enums;

enum ExceptionCode: int
{
    // Users
    case USER_ALREADY_EXISTS = 10_000;

    // Invitations
    case INVITATION_ALREADY_ACCEPTED = 40_000;
    case INVITATION_ALREADY_EXPIRED = 40_001;
    case INVITATION_ALREADY_REVOKED = 40_002;
    case INVITATION_CAN_NOT_BE_REVOKED = 40_003;
    case INVITATION_NOT_FOUND = 40_004;

    /**
     * Get HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int
    {
        $value = $this->value;

        return match (true) {
            $value >= 10_000 && $value <= 50_000 => 400,
            default => 500,
        };
    }

    /**
     * Get message, e.g. with translations.
     *
     * @return string
     */
    public function getMessage(): string
    {
        return "Occur some unknown error";
    }
}
