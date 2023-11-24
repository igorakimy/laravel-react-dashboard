<?php

namespace App\Helpers;

final class AttachmentHelper
{
    public function __construct(
        public string $name,
        public $content,
        public string|null $filename,
    ) {
    }

    public static function create(string $name, $content, string $filename = null): self
    {
        return new self(
            name: $name,
            content: $content,
            filename: $filename
        );
    }
}
