<?php

namespace App\Data\Invitation;

use App\Data\Role\RoleData;
use Carbon\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class InvitationUpdateData extends Data
{
    public function __construct(

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public Carbon $expires_at,

        #[WithCast(DateTimeInterfaceCast::class, format: "Y-m-d\TH:i:s.u\Z")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'Y-m-d H:i:s')]
        public Carbon $created_at,

        public string $url_token,
    ) {
    }
}
