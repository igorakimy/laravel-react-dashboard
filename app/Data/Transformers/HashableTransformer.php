<?php

namespace App\Data\Transformers;

use Spatie\LaravelData\Support\DataProperty;
use Spatie\LaravelData\Transformers\Transformer;

final class HashableTransformer implements Transformer
{
    public function transform(DataProperty $property, mixed $value): string
    {
        return bcrypt($value);
    }
}
