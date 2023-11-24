<?php

namespace App\Enums;

enum Integration: string
{
    case ZOHO_BOOKS = 'zoho_books';
    case ZOHO_CRM = 'zoho_crm';
    case ZOHO_INVENTORY = 'zoho_inventory';

    public function label(): string
    {
        return match ($this) {
            self::ZOHO_BOOKS => 'Zoho Books',
            self::ZOHO_CRM => 'Zoho CRM',
            self::ZOHO_INVENTORY => 'Zoho Inventory',
        };
    }
}
