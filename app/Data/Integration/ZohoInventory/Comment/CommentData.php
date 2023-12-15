<?php

namespace App\Data\Integration\ZohoInventory\Comment;

use Spatie\LaravelData\Data;

final class CommentData extends Data
{
    public function __construct(
        public string $comment_id,
        public string $comment_type,
        public string $commented_by,
        public string $commented_by_id,
        public string $date,
        public string $date_description,
        public string $date_formatted,
        public string $description,
        public string $operation_type,
        public string $time,
    ) {
    }
}
