<?php

namespace App\Data\Integration\ZohoCrm\Metadata\Field;

use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

final class FieldData extends Data
{
    public function __construct(
        public AssociatedModule|null $associated_module,

        public bool $webhook,

        public OperationType|null $operation_type,

        public bool $colour_code_enabled_by_system,
        public string $field_label,
        public string|null $tooltip,
        public string $type,
        public bool $field_read_only,
        public string $display_label,
        public bool $read_only,
        public array|null $association_details,
        public bool $businesscard_supported,
        public array|null $multi_module_lookup,
        public string $id,
        public string|null $quick_sequence_number,
        public string|null $created_time,
        public bool $filterable,
        public bool $visible,

        #[DataCollectionOf(Profile::class)]
        public DataCollection|null $profiles,

        public ViewType|null $view_type,

        public bool $separator,
        public bool $searchable,
        public string|null $external,
        public string $api_name,
        public array|null $unique,
        public bool $enable_colour_code,
        public array|null $pick_list_values,
        public bool $system_mandatory,
        public bool $virtual_field,
        public string $json_type,
        public string|null $crypt,
        public string|null $created_source,
        public int $display_type,
        public int $ui_type,
        public string|null $modified_time,

        public EmailParser|null $email_parser,

        public array|null $currency,
        public bool $custom_field,
        public array|null $lookup,
        public array|null $rollup_summary,
        public float $length,
        public bool $display_field,
        public bool $pick_list_values_sorted_lexically,
        public bool $sortable,
        public string|null $global_picklist,
        public string|null $history_tracking,
        public string $data_type,
        public array|null $formula,
        public string|null $decimal_place,
        public bool $mass_update,
        public array|null $multiselectlookup,
        public array|null $auto_number,
    ) {
    }
}
