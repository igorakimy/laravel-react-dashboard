<?php

namespace App\Data\Integration\ZohoCrm\Auth;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

final class CheckGrantCodeData extends Data
{
    public function __construct(
        public string|null $code
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            code: $request->input('code')
        );
    }
}
