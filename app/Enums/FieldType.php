<?php

namespace App\Enums;

enum FieldType: string
{
    case TEXT = 'text';
    case EMAIL = 'email';
    case NUMBER = 'number';
    case TEXTAREA = 'textarea';
    case DATE = 'date_picker';
    case DATETIME = 'datetime_picker';
    case CHECKBOX = 'checkbox';
    case SELECT = 'select';
    case MULTISELECT = 'multiselect';
    case FILE = 'file';
    case IMAGE = 'image';
}
