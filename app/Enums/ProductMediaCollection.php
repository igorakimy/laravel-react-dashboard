<?php

namespace App\Enums;

enum ProductMediaCollection: string
{
    case CATALOG_IMAGES = 'catalog_images';
    case PRODUCT_IMAGES = 'product_images';
    case VECTOR_IMAGE   = 'vector_image';

    public static function values(): array
    {
        $values = [];

        foreach (self::cases() as $value) {
            $values[] = $value->value;
        }

        return $values;
    }
}
