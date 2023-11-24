<?php

namespace App\Data\Integration\ZohoBooks\Setting;

use Spatie\LaravelData\Data;
use Spatie\LaravelData\Optional;

final class FieldData extends Data
{
    public function __construct(
        public bool $can_disable,
        public bool $can_show_in_pdf,
        public string $data_type,
        public string $data_type_formatted,
        public Optional|bool $edit_on_portal,
        public Optional|bool $edit_on_store,
        public string $entity,
        public string $entity_formatted,
        public string $field_id,
        public string $field_name,
        public string $field_name_formatted,
        public string $index,
        public bool $is_active,
        public bool $is_configure_permission,
        public bool $is_custom_field,
        public Optional|bool $is_enabled_in_transactions,
        public bool $is_from_plugin,
        public bool $is_mandatory,
        public Optional|bool $is_mandatory_in_hp,
        public Optional|bool $is_mandatory_in_storefront,
        public Optional|string $pii_type,
        public Optional|string $label,
        public bool $show_in_all_pdf,
        public string $status,
        public Optional|bool $can_edit_system_field,
        public Optional|bool $can_mark_mandatory,
        public Optional|string $value,
        public Optional|array $values,
    ) {
    }
}
