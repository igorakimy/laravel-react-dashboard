<?php

namespace App\Data\Integration\ZohoBooks\Document;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Attributes\DataCollectionOf;
use Spatie\LaravelData\Attributes\WithCast;
use Spatie\LaravelData\Attributes\WithTransformer;
use Spatie\LaravelData\Casts\DateTimeInterfaceCast;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;
use Spatie\LaravelData\Optional;
use Spatie\LaravelData\Transformers\DateTimeInterfaceTransformer;

final class DocumentData extends Data
{
    public function __construct(
        public string $document_id,
        public string $file_name,
        public float $file_size,
        public string $file_size_formatted,
        public string $file_type,
        public string $source,
        public string $source_formatted,
        public string|Optional $folder_name,
        public bool|Optional $can_delete_statement,
        public bool|Optional $can_upload_statement,
        public bool|Optional $can_send_in_mail,
        public int|Optional $attachment_order,
        public string|Optional $created_time,
        public string|Optional $created_time_formatted,
        public string|array|Optional $document_entity_ids,
        public string|Optional $document_scan_status,
        public string|Optional $document_scan_status_formatted,
        public string|Optional $document_status,
        public string|Optional $document_status_formatted,
        public bool|Optional $has_more_entity,
        public bool|Optional $is_statement,

        public string $uploaded_by,
        public string|Optional $uploaded_by_id,

        #[DataCollectionOf(TransactionData::class)]
        public DataCollection|Optional $transactions,

        #[WithCast(DateTimeInterfaceCast::class, format: "d M Y H:i A")]
        #[WithTransformer(DateTimeInterfaceTransformer::class, format: 'd M Y H:i A')]
        public Carbon|Optional $uploaded_on,

        public string|Optional $alter_text,
    ) {
    }
}
