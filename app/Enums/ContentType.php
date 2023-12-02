<?php

namespace App\Enums;

enum ContentType: string
{
    case HTML = 'text/html';
    case CSV = 'application/csv';
    case XLSX = 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet';

    public static function fromExtension(string $extension): self
    {
        return match ($extension) {
            'csv' => self::CSV,
            'xlsx' => self::XLSX,
            default => self::HTML,
        };
    }
}
