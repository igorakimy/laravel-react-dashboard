<?php

namespace App\Data\Import;

use Illuminate\Http\Request;
use Spatie\LaravelData\Data;

final class ImportData extends Data
{
    public function __construct(
        public string $filepath,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('filepath'),
        );
    }
}
