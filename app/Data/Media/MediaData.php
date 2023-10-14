<?php

namespace App\Data\Media;

use Spatie\LaravelData\Data;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

final class MediaData extends Data
{
    public function __construct(
        public int $id,
        public string $uuid,
        public string $collection_name,
        public string $name,
        public string $file_name,
        public string $mime_type,
        public int $size,
        public string $url,
    ) {
    }

    public static function fromModel(Media $media): self
    {
        return new self(
            id: $media->id,
            uuid: $media->uuid,
            collection_name: $media->collection_name,
            name: $media->name,
            file_name: $media->file_name,
            mime_type: $media->mime_type,
            size: $media->size,
            url: $media->getUrl()
        );
    }
}
