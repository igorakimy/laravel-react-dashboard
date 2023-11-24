<?php

namespace App\Enums;

enum ZohoBooksFieldType: string
{
    case STRING = 'string';
    case EMAIL = 'email';
    case URL = 'url';
    case PHONE = 'phone';
    case NUMBER = 'number';
    case DECIMAL = 'decimal';
    case AMOUNT = 'amount';
    case PERCENT = 'percent';
    case DATE = 'date';
    case DATETIME = 'date_time';
    case CHECKBOX = 'check_box';
    case AUTONUMBER = 'autonumber';
    case DROPDOWN = 'dropdown';
    case MULTISELECT = 'multiselect';
    case LOOKUP = 'lookup';
    case MULTILINE = 'multiline';
    case ATTACHMENT = 'attachment';
    case FORMULA = 'formula';
    case CUSTOM = 'custom';

    /**
     * Convert to local field type.
     *
     * @return FieldType
     */
    public function toLocalType(): FieldType
    {
        return match ($this) {
            self::STRING,
            self::URL,
            self::PHONE,
            self::AMOUNT,
            self::FORMULA => FieldType::TEXT,

            self::EMAIL => FieldType::EMAIL,

            self::NUMBER,
            self::DECIMAL,
            self::PERCENT,
            self::AUTONUMBER => FieldType::NUMBER,

            self::DATE => FieldType::DATE,

            self::DATETIME => FieldType::DATETIME,

            self::CHECKBOX => FieldType::CHECKBOX,

            self::DROPDOWN,
            self::LOOKUP => FieldType::SELECT,

            self::MULTISELECT => FieldType::MULTISELECT,

            self::MULTILINE => FieldType::TEXTAREA,

            self::ATTACHMENT,
            self::CUSTOM => FieldType::FILE,
        };
    }
}
