<?php

namespace App\Models;

use App\Enums\InvitationState;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * App\Models\Invitation
 *
 * @property int $id
 * @property string $email
 * @property string $url_token
 * @property int $sender_id
 * @property int|null $invitee_id
 * @property string|null $message_text
 * @property Carbon $expires_at
 * @property Carbon|null $accepted_at
 * @property Carbon|null $declined_at
 * @property Carbon|null $revoked_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Role> $allowedRoles
 * @property-read int|null $allowed_roles_count
 * @property-read InvitationState $state
 * @property-read \App\Models\User|null $invitee
 * @property-read \App\Models\User $sender
 * @method static Builder|Invitation newModelQuery()
 * @method static Builder|Invitation newQuery()
 * @method static Builder|Invitation pending()
 * @method static Builder|Invitation query()
 * @method static Builder|Invitation whereAcceptedAt($value)
 * @method static Builder|Invitation whereCreatedAt($value)
 * @method static Builder|Invitation whereDeclinedAt($value)
 * @method static Builder|Invitation whereEmail($value)
 * @method static Builder|Invitation whereExpiresAt($value)
 * @method static Builder|Invitation whereId($value)
 * @method static Builder|Invitation whereInviteeId($value)
 * @method static Builder|Invitation whereMessageText($value)
 * @method static Builder|Invitation whereRevokedAt($value)
 * @method static Builder|Invitation whereSenderId($value)
 * @method static Builder|Invitation whereUpdatedAt($value)
 * @method static Builder|Invitation whereUrlToken($value)
 * @method static Builder|Invitation orderByInvitee(string $direction = 'asc')
 * @method static Builder|Invitation orderByRoles(string $direction = 'asc')
 * @method static Builder|Invitation orderBySender(string $direction = 'asc')
 * @mixin \Eloquent
 */
class Invitation extends Model
{
    protected $fillable = [
        'email',
        'url_token',
        'sender_id',
        'invitee_id',
        'message_text',
        'expires_at',
        'accepted_at',
        'declined_at',
        'revoked_at',
    ];

    protected $appends = [
        'state'
    ];

    protected $casts = [
        'expires_at'  => 'datetime',
        'accepted_at' => 'datetime',
        'declined_at' => 'datetime',
        'revoked_at'  => 'datetime',
    ];

    // =================== //
    //      RELATIONS      //
    // =================== //

    /**
     * The user who sent the invitation.
     *
     * @return BelongsTo
     */
    public function sender(): BelongsTo
    {
        return $this->belongsTo(User::class, 'sender_id')
                    ->withTrashed();
    }

    /**
     * The user who accepted the invitation.
     *
     * @return BelongsTo
     */
    public function invitee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'invitee_id')
                    ->withTrashed();
    }

    /**
     * Roles that attached to the invitation.
     *
     * @return BelongsToMany
     */
    public function allowedRoles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'invitation_role');
    }

    // ================ //
    //      SCOPES      //
    // ================ //

    /**
     * Scope a query to only include pending invitations.
     *
     * @param  Builder  $query
     *
     * @return Builder
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->whereNull(['accepted_at', 'declined_at'])
                     ->whereDate('expires_at', '>=', Carbon::now());
    }

    /**
     * Scope a query to order invitations by sender.
     *
     * @param  Builder  $query
     * @param  string  $direction
     *
     * @return void
     */
    public function scopeOrderBySender(Builder $query, string $direction = 'asc'): void
    {
        $query->orderBy(
            User::query()->selectRaw("CONCAT(first_name, ' ', last_name)")
                ->whereColumn('users.id', '=', 'invitations.sender_id'),
            $direction
        );
    }

    /**
     * Scope a query to order invitations by invitee.
     *
     * @param  Builder  $query
     * @param  string  $direction
     *
     * @return void
     */
    public function scopeOrderByInvitee(Builder $query, string $direction = 'asc'): void
    {
        if ($this->invitee) {
            $query->orderBy(
                User::query()->select('email')
                    ->whereColumn('users.id', '=', 'invitations.invitee_id'),
                $direction
            );
        } else {
            $query->orderBy('email', $direction);
        }
    }

    /**
     * Scope a query to order invitations by state.
     *
     * @param  Builder  $query
     * @param  string  $direction
     *
     * @return void
     */
    public function scopeOrderByStatus(Builder $query, string $direction = 'asc'): void
    {
        $sorted = self::get()
                       ->sortBy(function ($el) {
                           return $el->state->value;
                       }, SORT_STRING, $direction === 'desc')
                       ->pluck('id')
                       ->toArray();


        $orderedIDs = implode(', ', $sorted);

        $rawSql = DB::raw("unnest(array[$orderedIDs]) WITH ORDINALITY AS t(id, ord)");
        $query->join($rawSql, 'invitations.id', '=', 't.id')
              ->orderBy('t.ord');
    }

    /**
     * Scope a query to order invitations by allowed roles.
     *
     * @param  Builder  $query
     * @param  string  $direction
     *
     * @return void
     */
    public function scopeOrderByRoles(Builder $query, string $direction = 'asc'): void
    {
        $query->orderBy('allowedRoles.name', $direction);
    }

    // ======================= //
    //      OTHER METHODS      //
    // ======================= //

    /**
     * Check if an invitation is pending.
     *
     * @return bool
     */
    public function isPending(): bool
    {
        return ! $this->isAccepted()
               && ! $this->isDeclined()
               && ! $this->isRevoked()
               && Carbon::parse($this->expires_at) >= Carbon::now();
    }

    /**
     * Check if an invitation is expired.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return ! $this->isAccepted()
               && ! $this->isDeclined()
               && ! $this->isRevoked()
               && Carbon::parse($this->expires_at) < Carbon::now();
    }

    /**
     * Check if an invitation is accepted.
     *
     * @return bool
     */
    public function isAccepted(): bool
    {
        return ! ! $this->accepted_at;
    }

    /**
     * Check if an invitation is declined.
     *
     * @return bool
     */
    public function isDeclined(): bool
    {
        return ! ! $this->declined_at;
    }

    /**
     * Check if an invitation is revoked.
     *
     * @return bool
     */
    public function isRevoked(): bool
    {
        return ! ! $this->revoked_at;
    }

    /**
     * Get invitation state
     *
     * @return InvitationState
     */
    public function getStateAttribute(): InvitationState
    {
        return match (true) {
            $this->isAccepted() => InvitationState::ACCEPTED,
            $this->isDeclined() => InvitationState::DECLINED,
            $this->isRevoked() => InvitationState::REVOKED,
            $this->isPending() => InvitationState::PENDING,
            $this->isExpired() => InvitationState::EXPIRED,
            default => InvitationState::PENDING
        };
    }

    /**
     * Generate new register token
     *
     * @return string
     */
    public static function generateToken(): string
    {
        return md5(Str::uuid());
    }
}
