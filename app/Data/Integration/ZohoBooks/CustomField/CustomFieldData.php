<?php

namespace App\Data\Integration\ZohoBooks\CustomField;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class CustomFieldData extends Data
{
    public function __construct(
        public string|Optional $customfield_id,
        public string|null|Optional $value,
        public string|Optional $field_id,
        public bool|Optional $show_in_store,
        public bool|Optional $show_in_portal,
        public bool|Optional $is_active,
        public string|Optional $approval_status_formatted,
        public string|Optional $approval_status,
        public int|string|Optional $index,
        public string|Optional $label,
        public bool|Optional $show_on_pdf,
        public string|Optional $source,
        public bool|Optional $edit_on_portal,
        public string|Optional $source_formatted,
        public bool|Optional $edit_on_store,
        public string|Optional $approved_by,
        public string|Optional $api_name,
        public bool|Optional $show_in_all_pdf,
        public string|Optional $file_type,
        public string|Optional $value_formatted,
        public string|Optional $search_entity,
        public string|Optional $data_type,
        public string|Optional $placeholder,
        public bool|Optional $is_dependent_field,
    ) {
    }
}
